<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;


class GoogleSheetController extends Controller
{

    static function index(){

        $spreadSheetId = config('google_sheet.google_sheet_id_vek');

        $client = new \Google_Client();
        $client->setAuthConfig(storage_path('credentials.json'));
        $client->addScope("https://www.googleapis.com/auth/spreadsheets");

        $googleSheetService = new \Google_Service_Sheets($client);

        // создаем новый лист, в котором будем формировать новый прайс
        $temp_title = "temp_list_".rand(1000, 9999);
        $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
           'requests' => array('addSheet' => array('properties' => array('title' => $temp_title )))));
        $googleSheetService->spreadsheets->batchUpdate($spreadSheetId, $body);

        // вставим пустые значения размером 29 на 3000
        // !!! временно убираем цены в $ !!!
        $values = [];
        for ($i=0; $i < 3000; $i++) { 
            $values[] = [
                // '',
                // '',
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',];
        }
        $body = new \Google_Service_Sheets_ValueRange(['values' => $values,]);
        $params =['valueInputOption' => 'USER_ENTERED',];
        $range = $temp_title."!A1";
        $googleSheetService->spreadsheets_values->update($spreadSheetId, $range, $body, $params);

        // добавляем заголовки
        $values = [
            [
                "Код",
                "Категория",
                "Наименование товара",
                // "Цена USD",
                "Цена BYN",
                // "Розн USD",
                "Розн BYN",
                "Наличие",
                "Дата поступления",
                "Сообщение i",
                "Сообщение %",
                "Комплектация",
                "Характеристики",
                "Преимущества",
                "Производитель",
                "Назначение",
                "Срок годности",
                "Страна изготовления",
                "Бренд",
                "Штрих-код",
                "Сертификат",
                "Габариты",
                "Вес с упаковкой",
                "Артикул",
                "Гарантийный срок (месяцев)",
                "Новинка",
                "Код ТН ВЭД",
                "Поставщик",
                "Оптовая надбавка",
                '=hyperlink("http://alfastok.by/shopsvec";"ОБНОВИТЬ КАТАЛОГ")',
            ],
        ];
        $body = new \Google_Service_Sheets_ValueRange(['values' => $values,]);
        $params =['valueInputOption' => 'USER_ENTERED',];
        $range = $temp_title."!A1";
        $googleSheetService->spreadsheets_values->update($spreadSheetId, $range, $body, $params);

        // собираем категории
        $categories = Category::where('parent_1c_id', 0)
            ->whereNotIn('1c_id', [193, 20070])
            ->orderBy('default_sort')
            ->get();

        // собираем товары
        $price = [];
        $num_row = 2;
        foreach ($categories as $mc) {

            foreach ($mc->subCategory as $sc) {

                if ($sc->subCategory->count()) {    

                    foreach ($sc->items as $item) {

                        // пропускаем архивные, кроме тех у которых in_price = 0
                        if ($item->in_archive == 1 && $item->in_price == 0) {
                            continue;
                        }

                        $count = $item->count;
                        if ($item->count > 10) {
                            $count = '> 10';
                        }
                        if ($item->count <= 2) {
                            $count = 'Нет';
                        }
                        if ($item->count_type == 5) {
                            $count = 'Нет';
                        }

                        if ($item->more_about == 'Подробнее о товаре:') {
                            $item->more_about = '';
                        }

                        $message_2 = $item->viewPriceList();

                        $price_list_usd = $item->createListPricesUSD();
                        $price_list_byn = $item->createListPricesBYN();

                        $price_usd = str_replace('.', ',', $item->price_usd);
                        $price_byn = str_replace('.', ',', $item->price_bel);

                        if($price_list_usd && $price_list_usd[0]['count'] == 1){
                            if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
                                $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
                            }
                        }

                        preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
                        if(isset($matches[1]) && isset($matches[2])){
                            if($matches[1] == 1)
                            {
                                //$price_byn = $matches[2];
                            }
                        }


                        // удаляем лишние пробелы в Комплектация
                        $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                        // Формируем Характеристики
                        $characteristics = "";
                        foreach($item->charValues as $charValues) {
                            if($charValues->characteristic) {
                                $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
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
                            $weight ="";
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
                            $item->code,
                            $sc->name,
                            $item->name,
                            // $price_usd,
                            $price_byn,//(string)$price_byn,
                            // str_replace('.', ',', $item->price_mr_usd),
                            str_replace('.', ',', $item->price_mr_bel),
                            $count,
                            $item->count_text,
                            $item->more_about,
                            $item->mini_text . ' ' . $message_2,
                            $equipment,
                            $characteristics,
                            $item->content,
                            $item->factory,
                            $item->apply,
                            $item->shelf_life,
                            $item->country,
                            $brand,
                            "'".$item->barcode,
                            $item->certificate,
                            $dimensions,
                            $weight,
                            "'".$item->vendor_code,
                            $guarantee_period,
                            $is_new_item,
                            $item->codeTNVD,
                            $importer,
                            $adjustable,
                        ];
                    }

                    foreach ($sc->subCategory as $scc) {

                        foreach ($scc->items as $item) {

                            // пропускаем архивные, кроме тех у которых in_price = 0
                            if ($item->in_archive == 1 && $item->in_price == 0) {
                                continue;
                            }

                            $count = $item->count;
                            if ($item->count > 10) {
                                $count = '> 10';
                            }
                            if ($item->count <= 2) {
                                $count = 'Нет';
                            }
                            if ($item->count_type == 5) {
                                $count = 'Нет';
                            }

                            if ($item->more_about == 'Подробнее о товаре:') {
                                $item->more_about = '';
                            }

                            $categoryName = $sc->name;
                            if ($scc->name) {
                                $categoryName = $scc->name;
                            }

                            $message_2 = $item->viewPriceList();

                            $price_list_usd = $item->createListPricesUSD();
                            $price_list_byn = $item->createListPricesBYN();

                            $price_usd = str_replace('.', ',', $item->price_usd);
                            $price_byn = str_replace('.', ',', $item->price_bel);

                            if($price_list_usd && $price_list_usd[0]['count'] == 1){
                                if($price_list_byn && $price_list_byn[0]['count'] == 1){
                                    $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
                                    $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
                                }
                            }

                            preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
                            if(isset($matches[1]) && isset($matches[2])){
                                if($matches[1] == 1)
                                {
                                    //$price_byn = $matches[2];
                                    //$price_usd = str_replace('.', ',', $item->price_usd);
                                }
                            }


                            // удаляем лишние пробелы в Комплектация
                            $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                            // Формируем Характеристики
                            $characteristics = "";
                            foreach($item->charValues as $charValues) {
                                if($charValues->characteristic) {
                                    $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
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
                                $weight ="";
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
                                $item->code,
                                $sc->name,
                                $item->name,
                                // $price_usd,
                                $price_byn,
                                // str_replace('.', ',', $item->price_mr_usd),
                                str_replace('.', ',', $item->price_mr_bel),
                                $count,
                                $item->count_text,
                                $item->more_about,
                                $item->mini_text . ' ' . $message_2,
                                $equipment,
                                $characteristics,
                                $item->content,
                                $item->factory,
                                $item->apply,
                                $item->shelf_life,
                                $item->country,
                                $brand,
                                "'".$item->barcode,
                                $item->certificate,
                                $dimensions,
                                $weight,
                                "'".$item->vendor_code,
                                $guarantee_period,
                                $is_new_item,
                                $item->codeTNVD,
                                $importer,
                                $adjustable,
                            ];
                        }
                    }
                } else {

                    foreach ($sc->items as $item) {

                        // пропускаем архивные, кроме тех у которых in_price = 0
                        if ($item->in_archive == 1 && $item->in_price == 0) {
                            continue;
                        }

                       $count = $item->count;
                        if ($item->count > 10) {
                            $count = '> 10';
                        }
                        if ($item->count <= 2) {
                            $count = 'Нет';
                        }
                        if ($item->count_type == 5) {
                            $count = 'Нет';
                        }

                        if ($item->more_about == 'Подробнее о товаре:') {
                            $item->more_about = '';
                        }

                        $message_2 = $item->viewPriceList();

                        $price_list_usd = $item->createListPricesUSD();
                        $price_list_byn = $item->createListPricesBYN();

                        $price_usd = str_replace('.', ',', $item->price_usd);
                        $price_byn = str_replace('.', ',', $item->price_bel);

                        if($price_list_usd && $price_list_usd[0]['count'] == 1){
                            if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
                                $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
                            }
                        }

                        preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
                        if(isset($matches[1]) && isset($matches[2])){
                            if($matches[1] == 1)
                            {
                                //$price_byn = $matches[2];
                            }
                        }


                        // удаляем лишние пробелы в Комплектация
                        $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                        // Формируем Характеристики
                        $characteristics = "";
                        foreach($item->charValues as $charValues) {
                            if($charValues->characteristic) {
                                $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
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
                            $weight ="";
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
                            $item->code,
                            $sc->name,
                            $item->name,
                            // $price_usd,
                            $price_byn,
                            // str_replace('.', ',', $item->price_mr_usd),
                            str_replace('.', ',', $item->price_mr_bel),
                            $count,
                            $item->count_text,
                            $item->more_about,
                            $item->mini_text . ' ' . $message_2,
                            $equipment,
                            $characteristics,
                            $item->content,
                            $item->factory,
                            $item->apply,
                            $item->shelf_life,
                            $item->country,
                            $brand,
                            "'".$item->barcode,
                            $item->certificate,
                            $dimensions,
                            $weight,
                            "'".$item->vendor_code,
                            $guarantee_period,
                            $is_new_item,
                            $item->codeTNVD,
                            $importer,
                            $adjustable,
                        ];
                    }
                }
            }
            // добавим пустую строку
            $price[] = [''];
            // отправляем в Гугл таблицу
            $body = new \Google_Service_Sheets_ValueRange(['values' => $price,]);
            $params =['valueInputOption' => 'USER_ENTERED',];
            $range = $temp_title."!A".$num_row;
            $googleSheetService->spreadsheets_values->update($spreadSheetId, $range, $body, $params);
            // обновляем номер начальной строки
            $num_row += count($price);
            // обнуляем массив
            $price = [];

        }

        // собираем все листы
        $sheets = $googleSheetService->spreadsheets->get($spreadSheetId)->getSheets();

        // узнаем индекс последнего листа
        $last_sheet_index = count($sheets) - 1;

        // переименовываем последний лист
        $title = date('d.m.Y H:i');
        $titleProp = new \Google_Service_Sheets_SheetProperties();
        $titleProp->setSheetId($sheets[$last_sheet_index]->getProperties()['sheetId']);
        $titleProp->setTitle($title);
        $renameReq = new \Google_Service_Sheets_UpdateSheetPropertiesRequest();
        $renameReq->setProperties($titleProp);
        $renameReq->setFields('title');
        $request = new \Google_Service_Sheets_Request();
        $request->setUpdateSheetProperties($renameReq);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
        $batchUpdateRequest->setRequests([$request]);
        $googleSheetService->spreadsheets->batchUpdate($spreadSheetId, $batchUpdateRequest);

        // удаляем все листы кроме последнего
        for ($i=0; $i < $last_sheet_index; $i++) {
            // узнаем индекс листа
            $list_id = $sheets[$i]->getProperties()['sheetId'];
            // удаляем лист
            $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
               'requests' => array('deleteSheet' => array('sheetId'=> $list_id))));
            $googleSheetService->spreadsheets->batchUpdate($spreadSheetId, $body);
       }

       exit('Прайс 21век сформирован!');

    }



//*****************************************************************************************************************


    static function shops(){

        $spreadSheetId = config('google_sheet.google_sheet_id_dealer');

        $client = new \Google_Client();
        $client->setAuthConfig(storage_path('credentials.json'));
        $client->addScope("https://www.googleapis.com/auth/spreadsheets");

        $googleSheetService = new \Google_Service_Sheets($client);

        // создаем новый лист, в котором будем формировать новый прайс
        $temp_title = "temp_list_".rand(1000, 9999);
        $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
           'requests' => array('addSheet' => array('properties' => array('title' => $temp_title )))));
        $googleSheetService->spreadsheets->batchUpdate($spreadSheetId, $body);

        // вставим пустые значения размером 28 на 3000

        $values = [];
        for ($i=0; $i < 3000; $i++) { 
            $values[] = [
                // '',
                // '',
                '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',];
        }
        $body = new \Google_Service_Sheets_ValueRange(['values' => $values,]);
        $params =['valueInputOption' => 'USER_ENTERED',];
        $range = $temp_title."!A1";
        $googleSheetService->spreadsheets_values->update($spreadSheetId, $range, $body, $params);

        // добавляем заголовки
        $values = [
            [
                "Код",
                "Категория",
                "Наименование товара",
                // "Цена USD",
                "Цена BYN",
                // "Розн USD",
                "Розн BYN",
                "Наличие",
                "Дата поступления",
                "Сообщение i",
                "Сообщение %",
                "Комплектация",
                "Характеристики",
                "Преимущества",
                "Производитель",
                "Назначение",
                "Срок годности",
                "Страна изготовления",
                "Бренд",
                "Штрих-код",
                "Сертификат",
                "Габариты",
                "Вес с упаковкой",
                "Артикул",
                "Гарантийный срок (месяцев)",
                "Новинка",
                "Код ТН ВЭД",
                "Поставщик",
                "Оптовая надбавка",
            ],
        ];
        $body = new \Google_Service_Sheets_ValueRange(['values' => $values,]);
        $params =['valueInputOption' => 'USER_ENTERED',];
        $range = $temp_title."!A1";
        $googleSheetService->spreadsheets_values->update($spreadSheetId, $range, $body, $params);

        // собираем категории
        $categories = Category::where('parent_1c_id', 0)
            ->whereNotIn('1c_id', [193, 20070])
            ->orderBy('default_sort')
            ->get();

        // собираем товары
        $price = [];
        $num_row = 2;
        foreach ($categories as $mc) {

            foreach ($mc->subCategory as $sc) {

                if ($sc->subCategory->count()) {    

                    foreach ($sc->items as $item) {

                        // пропускаем архивные, кроме тех у которых in_price = 0
                        if ($item->in_archive == 1 && $item->in_price == 0) {
                            continue;
                        }

                        $count = $item->count;
                        if ($item->count_type == 5) {
                            $count = 'Нет';
                        } elseif ($item->count > 10) {
                            $count = '> 10';
                        } elseif ($item->count <= 0) {
                            if ($item->count_type == 3) {
                                $count = 'Нет';
                            } elseif ($item->count_type == 2) {
                                $count = 'Резерв';
                            } else {
                                $count = 'Нет';
                            }
                        }

                        if ($item->more_about == 'Подробнее о товаре:') {
                            $item->more_about = '';
                        }

                        $message_2 = $item->viewPriceList();

                        $price_list_usd = $item->createListPricesUSD();
                        $price_list_byn = $item->createListPricesBYN();

                        $price_usd = str_replace('.', ',', $item->price_usd);
                        $price_byn = str_replace('.', ',', $item->price_bel);

                        if($price_list_usd && $price_list_usd[0]['count'] == 1){
                            if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
                                $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
                            }
                        }

                        preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
                        if(isset($matches[1]) && isset($matches[2])){
                            if($matches[1] == 1)
                            {
                                //$price_byn = $matches[2];
                            }
                        }


                        // удаляем лишние пробелы в Комплектация
                        $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                        // Формируем Характеристики
                        $characteristics = "";
                        foreach($item->charValues as $charValues) {
                            if($charValues->characteristic) {
                                $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
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
                            $weight ="";
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
                            $item->code,
                            $sc->name,
                            $item->name,
                            // $price_usd,
                            $price_byn,//(string)$price_byn,
                            // str_replace('.', ',', $item->price_mr_usd),
                            str_replace('.', ',', $item->price_mr_bel),
                            $count,
                            $item->count_text,
                            $item->more_about,
                            $item->mini_text . ' ' . $message_2,
                            $equipment,
                            $characteristics,
                            $item->content,
                            $item->factory,
                            $item->apply,
                            $item->shelf_life,
                            $item->country,
                            $brand,
                            "'".$item->barcode,
                            $item->certificate,
                            $dimensions,
                            $weight,
                            "'".$item->vendor_code,
                            $guarantee_period,
                            $is_new_item,
                            $item->codeTNVD,
                            $importer,
                            $adjustable,
                        ];
                    }

                    foreach ($sc->subCategory as $scc) {

                        foreach ($scc->items as $item) {

                            // пропускаем архивные, кроме тех у которых in_price = 0
                            if ($item->in_archive == 1 && $item->in_price == 0) {
                                continue;
                            }

                            $count = $item->count;
                            if ($item->count_type == 5) {
                                $count = 'Нет';
                            } elseif ($item->count > 10) {
                                $count = '> 10';
                            } elseif ($item->count <= 0) {
                                if ($item->count_type == 3) {
                                    $count = 'Нет';
                                } elseif ($item->count_type == 2) {
                                    $count = 'Резерв';
                                } else {
                                    $count = 'Нет';
                                }
                            }

                            if ($item->more_about == 'Подробнее о товаре:') {
                                $item->more_about = '';
                            }

                            $categoryName = $sc->name;
                            if ($scc->name) {
                                $categoryName = $scc->name;
                            }

                            $message_2 = $item->viewPriceList();

                            $price_list_usd = $item->createListPricesUSD();
                            $price_list_byn = $item->createListPricesBYN();

                            $price_usd = str_replace('.', ',', $item->price_usd);
                            $price_byn = str_replace('.', ',', $item->price_bel);

                            if($price_list_usd && $price_list_usd[0]['count'] == 1){
                                if($price_list_byn && $price_list_byn[0]['count'] == 1){
                                    $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
                                    $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
                                }
                            }

                            preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
                            if(isset($matches[1]) && isset($matches[2])){
                                if($matches[1] == 1)
                                {
                                    //$price_byn = $matches[2];
                                    //$price_usd = str_replace('.', ',', $item->price_usd);
                                }
                            }


                            // удаляем лишние пробелы в Комплектация
                            $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                            // Формируем Характеристики
                            $characteristics = "";
                            foreach($item->charValues as $charValues) {
                                if($charValues->characteristic) {
                                    $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
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
                                $weight ="";
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
                                $item->code,
                                $sc->name,
                                $item->name,
                                // $price_usd,
                                $price_byn,
                                // str_replace('.', ',', $item->price_mr_usd),
                                str_replace('.', ',', $item->price_mr_bel),
                                $count,
                                $item->count_text,
                                $item->more_about,
                                $item->mini_text . ' ' . $message_2,
                                $equipment,
                                $characteristics,
                                $item->content,
                                $item->factory,
                                $item->apply,
                                $item->shelf_life,
                                $item->country,
                                $brand,
                                "'".$item->barcode,
                                $item->certificate,
                                $dimensions,
                                $weight,
                                "'".$item->vendor_code,
                                $guarantee_period,
                                $is_new_item,
                                $item->codeTNVD,
                                $importer,
                                $adjustable,
                            ];
                        }
                    }
                } else {

                    foreach ($sc->items as $item) {

                        // пропускаем архивные, кроме тех у которых in_price = 0
                        if ($item->in_archive == 1 && $item->in_price == 0) {
                            continue;
                        }

                        $count = $item->count;
                        if ($item->count_type == 5) {
                            $count = 'Нет';
                        } elseif ($item->count > 10) {
                            $count = '> 10';
                        } elseif ($item->count <= 0) {
                            if ($item->count_type == 3) {
                                $count = 'Нет';
                            } elseif ($item->count_type == 2) {
                                $count = 'Резерв';
                            } else {
                                $count = 'Нет';
                            }
                        }

                        if ($item->more_about == 'Подробнее о товаре:') {
                            $item->more_about = '';
                        }

                        $message_2 = $item->viewPriceList();

                        $price_list_usd = $item->createListPricesUSD();
                        $price_list_byn = $item->createListPricesBYN();

                        $price_usd = str_replace('.', ',', $item->price_usd);
                        $price_byn = str_replace('.', ',', $item->price_bel);

                        if($price_list_usd && $price_list_usd[0]['count'] == 1){
                            if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
                                $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
                            }
                        }

                        preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
                        if(isset($matches[1]) && isset($matches[2])){
                            if($matches[1] == 1)
                            {
                                //$price_byn = $matches[2];
                            }
                        }

                        // удаляем лишние пробелы в Комплектация
                        $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

                        // Формируем Характеристики
                        $characteristics = "";
                        foreach($item->charValues as $charValues) {
                            if($charValues->characteristic) {
                                $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
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
                            $weight ="";
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
                            $item->code,
                            $sc->name,
                            $item->name,
                            // $price_usd,
                            $price_byn,
                            // str_replace('.', ',', $item->price_mr_usd),
                            str_replace('.', ',', $item->price_mr_bel),
                            $count,
                            $item->count_text,
                            $item->more_about,
                            $item->mini_text . ' ' . $message_2,
                            $equipment,
                            $characteristics,
                            $item->content,
                            $item->factory,
                            $item->apply,
                            $item->shelf_life,
                            $item->country,
                            $brand,
                            "'".$item->barcode,
                            $item->certificate,
                            $dimensions,
                            $weight,
                            "'".$item->vendor_code,
                            $guarantee_period,
                            $is_new_item,
                            $item->codeTNVD,
                            $importer,
                            $adjustable,
                        ];
                    }
                }
            }
            // добавим пустую строку
            $price[] = [''];
            // отправляем в Гугл таблицу
            $body = new \Google_Service_Sheets_ValueRange(['values' => $price,]);
            $params =['valueInputOption' => 'USER_ENTERED',];
            $range = $temp_title."!A".$num_row;
            $googleSheetService->spreadsheets_values->update($spreadSheetId, $range, $body, $params);
            // обновляем номер начальной строки
            $num_row += count($price);
            // обнуляем массив
            $price = [];

        }

        // собираем все листы
        $sheets = $googleSheetService->spreadsheets->get($spreadSheetId)->getSheets();

        // узнаем индекс последнего листа
        $last_sheet_index = count($sheets) - 1;


        // удаляем все листы кроме последнего
        for ($i=0; $i < $last_sheet_index; $i++) {
            // узнаем индекс листа
            $list_id = $sheets[$i]->getProperties()['sheetId'];
            // удаляем лист
            $body = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
               'requests' => array('deleteSheet' => array('sheetId'=> $list_id))));
            $googleSheetService->spreadsheets->batchUpdate($spreadSheetId, $body);
       }

        // переименовываем лист
        $title = 'price_alfastok';
        $titleProp = new \Google_Service_Sheets_SheetProperties();
        $titleProp->setSheetId($sheets[$last_sheet_index]->getProperties()['sheetId']);
        $titleProp->setTitle($title);
        $renameReq = new \Google_Service_Sheets_UpdateSheetPropertiesRequest();
        $renameReq->setProperties($titleProp);
        $renameReq->setFields('title');
        $request = new \Google_Service_Sheets_Request();
        $request->setUpdateSheetProperties($renameReq);
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
        $batchUpdateRequest->setRequests([$request]);
        $googleSheetService->spreadsheets->batchUpdate($spreadSheetId, $batchUpdateRequest);

       exit('Прайс для дилеров сформирован!');

    }

}
