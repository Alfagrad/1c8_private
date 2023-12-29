<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use URL;


class MakePriceController extends Controller
{

    public function index(Request $request)
    {
        // $data = $this->getUserData($request);

        // собираем категории
        $categories = Category::with('subCategory')
            ->where('parent_1c_id', 0)
            ->whereNotIn('id_1c', [7212])
            ->orderBy('default_sort')
            ->get(['name', 'id_1c']);
        $data['categories'] = $categories;

        return view('make_price_page')->with($data);
    }

    public function downloadPrice(Request $request)
    {

        // берем массив кодов категорий
        $categories = $request->cat_info;

        // если пусто, возвращаемся
        if(!$categories) {
            return back();
        }

        // если запрос на прайс excel
        if(isset($request->price_excel)) {

            // генерируем прайс (с учетом наценки)
            $price = $this->generatePrice($categories);

            Excel::create('price_from_' . Carbon::now()->format('d.m.Y'), function ($excel) use ($price) {
                $excel->sheet('Alfastok', function ($sheet) use ($price) {
                    $sheet->fromArray($price);
                    $sheet->setWidth('A', 5);
                    $sheet->setWidth('B', 40);
                    $sheet->setWidth('C', 50);
                });
            })->export('xls');
        }

        // если запрос на прайс yml
        if (isset($request->price_yml)) {

            // генерируем прайс
            $price = $this->generateYML($categories);

        }


    }

    public static function generateYML($categories)
    {
        // создаем новый массив только коды
        foreach ($categories as $val) {

            // делим
            $arr = explode(';', $val);

            // выделяем код категории, добавляем в массив
            $cat_codes[] = $arr[0];
        }

        // создаем информацию для файла
        $text = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date('Y-m-d H:i').'">
    <shop>
        <name>Общество с ограниченной ответственностью «Альфасад»</name>
        <company>Общество с ограниченной ответственностью «Альфасад»</company>
        <url>https://alfastok.by/</url>
        <currencies>
            <currency id="BYN" rate="1" />
        </currencies>
        <categories>';

        // блок категорий
        // собираем категории
        $main_categories = Category::where('parent_1c_id', 0)
            ->whereNotIn('id_1c', [7166, 7212])
            ->get(['name', 'id_1c', 'parent_1c_id']);

        foreach ($main_categories as $cat) {
            // главные категории
            $text .= '
            <category id="'.$cat->id_1c.'">'.$cat->name.'</category>';

            // 2-й уровень
            // собираем категории
            $sub_categories = Category::where('parent_1c_id', $cat->id_1c)->get(['name', 'id_1c', 'parent_1c_id']);

            // если не пусто
            if($sub_categories->count()) {
                // дописываем
                foreach($sub_categories as $sub_cat) {
                    $text .= '
            <category id="'.$sub_cat->id_1c.'" parentId="'.$sub_cat->parent_1c_id.'">'.$sub_cat->name.'</category>';

                    // 3-й уровень
                    // собираем категории
                    $sub_sub_categories = Category::where('parent_1c_id', $sub_cat->id_1c)->get(['name', 'id_1c', 'parent_1c_id']);

                    // если не пусто
                    if($sub_sub_categories->count()) {
                        // дописываем
                        foreach($sub_sub_categories as $sub_sub_cat) {
                            $text .= '
            <category id="'.$sub_sub_cat->id_1c.'" parentId="'.$sub_sub_cat->parent_1c_id.'">'.$sub_sub_cat->name.'</category>';

                            // 4-й уровень
                            // собираем категории
                            $sub_sub_sub_categories = Category::where('parent_1c_id', $sub_sub_cat->id_1c)->get(['name', 'id_1c', 'parent_1c_id']);

                            // если не пусто
                            if($sub_sub_sub_categories->count()) {
                                // дописываем
                                foreach($sub_sub_sub_categories as $sub_sub_sub_cat) {
                                    $text .= '
            <category id="'.$sub_sub_sub_cat->id_1c.'" parentId="'.$sub_sub_sub_cat->parent_1c_id.'">'.$sub_sub_sub_cat->name.'</category>';
                                }
                            }
                        }
                    }
                }
            }
        }

        $text .= '
        </categories>
        <offers>';

        // блок товаров
        // собираем товары категорий
        $items = Item::whereIn('1c_category_id', $cat_codes)
            ->where('in_archive', 0)
            ->orWhere([['in_archive', 1], ['in_price', 1]])
            ->with('images', 'charValues', 'brand')
            ->get();

        // формируем оффер
        foreach ($items as $item) {

            // доступность товара
            $available = ($item->amount > 0) ? "true" : "false";

            $text .= '
            <offer available="'.$available.'" id="'.$item->id_1c.'">';

            // изображения
            if ($item->image) {
                $text .= '
                <picture>'.URL::to('/').'/storage/ut_1c8/item-images/'.$item->image.'</picture>';
            }
            if ($item->images->count()) {
                foreach($item->images as $img) {
                    $text .= '
                <picture>'.URL::to('/').'/storage/ut_1c8/item-images/'.$img->image.'</picture>';
                }
            }

            // курс валюты
            $usd_mr = setting('header_usd_mrc');

            // определяем цену
            if ($item->adjustable == 1) { // если товар регулируемый
                $item_price_mr = $item->price_mr_rub;
            } else {
                // считаем по оптовому курсу
                $item_price_mr = number_format($item->price_mr_usd * $usd_mr, 2, '.', '');
            }
            $item_price_mr = number_format($item_price_mr * 1.2, 2, '.', '');

            // Бренд
            if($item->brand()->first()) {
                $brand = $item->brand()->first()->name;
            } else {
                $brand = "";
            }

            $text .= '
                <name>'.htmlspecialchars($item->name).'</name>
                <price>'.$item_price_mr.'</price>
                <currencyId>BYN</currencyId>
                <categoryId>'.$item->{'1c_category_id'}.'</categoryId>
                <description>'.htmlspecialchars($item->more_about).'</description>
                <announce>'.htmlspecialchars($item->content).'</announce>
                <barcode>'.$item->barcode.'</barcode>
                <tn-ved-code>'.$item->codeTNVD.'</tn-ved-code>
                <vendorCode>'.$item->vendor_code.'</vendorCode>
                <vendor>'.htmlspecialchars($item->factory).'</vendor>
                <brand>'.$brand.'</brand>
                <dimensions>'.($item->width / 10).'/'.($item->depth / 10).'/'.($item->height / 10).'</dimensions>
                <weight>'.$item->weight.'</weight>
                <country_of_origin>'.$item->country.'</country_of_origin>';

            // Формируем Характеристики
            $characteristics = "";

            // берем значения характеристик
            $chars = $item->charValues;
            // если есть
            if ($chars->count()) {
                foreach ($chars as $char) {

                    // берем имя характеристики, если есть
                    $charName = $char->charName;
                    if ($charName) {

                        $text .= '
                <param name="'.$charName->name.'" unit="'.$char->value.'"</param>';

                    }
                }
            }

            $text .= '
            </offer>';
        }

        $text .= '
        </offers>
    </shop>
</yml_catalog>';

        // путь к файлу
        $file = "storage/temp/Alfastok_".date('d_m_Y').".yml";

        // записываем файл
        $yml_file = fopen($file, 'w');
        fwrite($yml_file, $text);
        fclose($yml_file);

        // скачиваем
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($file));
        readfile($file);

        // удаляем
        unlink($file);
    }


    public static function generatePrice($categories)
    {

        foreach ($categories as $cat) {

            // делим
            $cat = explode(';', $cat);

            // код категории
            $cat_code = $cat[0];

            // имя категории
            $cat_name = $cat[1];

            // собираем товары категории
            $items = Item::where([['1c_category_id', $cat_code], ['in_price', 0], ['in_archive', 0]])->with('brand')->get();

            // если есть
            if($items->count()) {

                foreach($items as $item) {

                    $count = $item->amount;
                    if ($item->amount > 10) {
                        $count = '> 10';
                    } elseif ($item->amount <= 0) {
                        if ($item->reserve > 0) {
                            $count = 'Резерв';
                        } else {
                            $count = 'Нет';
                        }
                    }

                    // дата поступления
                    if ($count == "Нет" && $item->expected_date > 0) {
                        $expected_date = date('d.m.Y', strtotime($item->expected_date));
                    } else {
                        $expected_date = '';
                    }

                    //
                    if ($item->more_about == 'Подробнее о товаре:') {
                        $item->more_about = '';
                    }

                    // курсы валют
                    $usd_opt = setting('header_usd');
                    $usd_mr = setting('header_usd_mrc');

                    // определяем стандартную цену
                    if ($item->adjustable == 1) { // если товар регулируемый
                        $item_price = $item->price_rub;
                        $item_price_mr = $item->price_mr_rub;
                    } else {
                        // считаем по оптовому курсу
                        $item_price = number_format($item->price_usd * $usd_opt, 2, '.', '');
                        $item_price_mr = number_format($item->price_mr_usd * $usd_mr, 2, '.', '');
                    }

                    // если есть акция
                    if($item->discount_str) {

                        // делим скидки
                        $discounts = explode(';', $item->discount_str);

                        // формируем строку дисконта
                        $discount_str = '';
                        $i = 0;

                        foreach ($discounts as $element) {

                            // делим элементы скидки
                            $element = explode('|', $element);

                            // мин кол-во на которое действует
                            $condition = $element[0];
                            // цена
                            $discount_price = $element[1];
                            // дата начала
                            $date_start = $element[2];
                            // дата окончания
                            $date_end = $element[3];
                            // процент скидки
                            $discount_purcent = $element[4];

                            // формируем строку скидок для акционных товаров
                            if (date('Y-m-d') >= $date_start && ($date_end == '0000-00-00' || date('Y-m-d') <= $date_end)) {

                                $discount_str .= "от {$condition} шт ".number_format($discount_price * 1.2, 2, '.', '')." руб, ";
                                // для изменения цены
                                $d_price = $discount_price;
                                $cond = $condition;

                                $i++;
                            }

                        }

                        // удаляем запятую и пробел в конце строки
                        $discount_str = preg_replace('/, $/', '', $discount_str);

                        if ($discount_str) {

                            if ($i == 1 && $cond == 1) {
                                $item_price = $d_price;
                            }
                        }
                    } else {
                        $discount_str = '';
                    }

                    $item_price = number_format($item_price * 1.2, 2, '.', '');
                    $item_price_mr = number_format($item_price_mr * 1.2, 2, '.', '');

                    // удаляем лишние пробелы в Комплектация
                    $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                    // Формируем Характеристики
                    $characteristics = "";

                    // берем значения характеристик
                    $chars = $item->charValues;
                    // если есть
                    if ($chars->count()) {
                        foreach ($chars as $char) {

                            // берем имя характеристики, если есть
                            $charName = $char->charName;
                            if ($charName) {
                                $char_name = $charName->name;

                                // добавляем в коллекцию
                                $characteristics .= $char_name.": ".$char->value."\n";
                            }
                        }
                    }

                    // Формируем Габариты
                    if($item->depth && $item->width && $item->height) {
                        $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
                    } else {
                        $dimensions ="";
                    }

                    // Формируем Вес с упаковкой
                    if($item->weight != '0.00') {
                        $weight = str_replace('.', ',', $item->weight).' кг';
                    } else {
                        $weight = "";
                    }

                    // Гарантийный срок меняем 0 на пусто
                    if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
                        else $guarantee_period = '';

                    // Если новинка, пишем да
                    if($item->is_new_item == 1) $is_new_item = 'Да';
                        else $is_new_item = '';

                    // Бренд
                    if($item->brand()->first()) {
                        $brand = $item->brand()->first()->name;
                    } else {
                        $brand = "";
                    }

                    // Поставщик + Надбавка
                    $importer = intval($item->importer);
                    $adjustable = intval($item->adjustable);

                    if($importer == 0 && $adjustable == 0) {
                        $importer = "Оптовик";
                        $adjustable = "Не регулируется";
                    } elseif($importer == 0 && $adjustable == 1) {
                        $importer = "Оптовик";
                        if($item->price_usd == 0) {
                            if($item->cost_rub > 0) {
                                $adjustable = number_format((($item->price_bel - $item->cost_rub) / $item->cost_rub) * 100, 2, ',', '')."%";
                            } else {
                                $adjustable = "";
                            }
                        } else {
                            $adjustable = "Остатки на 26.10.2022";
                        }
                    } elseif($importer == 1 && $adjustable == 0) {
                        $importer = "Импортер";
                        $adjustable = "0%";
                    } elseif($importer == 1 && $adjustable == 1) {
                        $importer = "Импортер";
                        $adjustable = "0%";
                    }

                    $price[] = [
                        'Код' => $item->code,
                        'Категория' => $cat_name,
                        'Наименование товара' => $item->name,
                        'Цена BYN' => $item_price,
                        'Розн BYN' => $item_price_mr,
                        'Наличие' => $count,
                        'Дата поступления' => $expected_date,
                        'Сообщение i' => $item->more_about,
                        'Сообщение %' => $discount_str,
                        'Комплектация' => $equipment,
                        'Характеристики' => $characteristics,
                        'Преимущества' => $item->content,
                        'Производитель' => $item->factory,
                        'Назначение' => $item->apply,
                        'Срок годности' => $item->shelf_life,
                        'Страна изготовления' => $item->country,
                        'Бренд' => $brand,
                        'Штрих-код' => $item->barcode,
                        'Сертификат' => $item->certificate,
                        'Габариты' => $dimensions,
                        'Вес с упаковкой' => $weight,
                        'Артикул' => $item->vendor_code,
                        'Гарантийный срок (месяцев)' => $guarantee_period,
                        'Новинка' => $is_new_item,
                        'Код ТН ВЭД' => $item->codeTNVD,
                        'Поставщик' => $importer,
                        'Оптовая надбавка' => $adjustable,
                    ];
                }
            }
        }

        return $price;
    }

}
