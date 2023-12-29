<?php

namespace App\Http\Controllers;

use App\Models\ArrivalItem;
use App\Models\BlockAction;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Feedback;
use App\Models\Item;
use App\Models\News;
use App\Models\Profile;
use App\Models\ReviewItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public $user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        if (profile()->isService()) {
            return redirect()->route('catalogue.index');
        }

        $new_items_count = Item::where('is_new_item', 1)->where('amount', '>', 0)->get(['id']);

        // $data = $this->getUserData($request);
        $data['news'] = News::where([['is_active', 1], ['for_opt', 1]])->orderBy('created_at', 'DESC')->get();

        $data['arrivalItems'] =
            ArrivalItem::
                where([['is_active', 1], ['created_at', '>', Carbon::now()->subMonths(2)]])
                ->orderBy('created_at', 'DESC')
                ->get(['title', 'link', 'created_at']);

        $data['reviewItems'] = ReviewItem::where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        $data['blockActions'] = BlockAction::where('is_active', 1)->orderBy('created_at', 'DESC')->get();
        $data['blockNews'] = News::where([['is_active', 1], ['for_opt', 1], ['is_show_main', 1]])->orderBy('created_at', 'DESC')->take(4)->get();
        $data['new_items_count'] = $new_items_count->count();


        return view('home', $data);
    }


    public function emailFeedback(Request $request)
    {

        \Log::info(Carbon::parse('now') . 'Отправка письма :');

        $profile = $request->user()->profile()->with('contact')->first();

        $attachName = '';
        $attachFile = '';
        if ($request->hasFile('attach')) {
            $attach = $request->file('attach');
            $attachName = $profile->id . '_' . time() . '.' . $attach->extension();
            $attachFile = $attach->move(public_path('attach'), $attachName);

        }

        $feedback = [
            'profile_id' => $profile->id,
            'client_name' => $profile->name,
            'company_name' => $profile->company_name,
            'email' => $profile->email,
            'attach' => $attachName,
            'is_confidential' => $request->get('is_confidential', 0),
            'comment' => $request->get('comment', 0),
            'feedback_type' => $request->get('feedback_type', 0),
        ];
        Feedback::create($feedback);
        if (isset($profile->contact)) {
            $feedback['contacts'] = $profile->contact;
        }

        if (!$profile->manager_email) {
            //    return redirect('/pages/thanks_message');
            $profile->manager_email = setting('email_to_other');
        }

       // выделяю только первый емайл
        $email_to = trim(explode(';', $profile->manager_email)[0]);


        if ($request->get('feedback_type') == config('constants.emailTo.manager')) {
            $email_headers['subject'] = 'Обратная связь';
            $email_headers['email_to'] = $email_to; //$user->email;
        } elseif ($request->get('feedback_type') == config('constants.emailTo.head')) {
            $email_headers['subject'] = 'Жалоба директору';
            $email_headers['email_to'] = setting('email_to_head'); //$user->email;
        } elseif ($request->get('feedback_type') == config('constants.emailTo.claim')) {
            $email_headers['subject'] = 'Жалоба на демпинг';
            $email_headers['email_to'] = $email_to; //$user->email;
            if ($request->has('id_1c')) {
                $item = Item::where('1c_id', $request->get('id_1c'))->first();
                $feedback['item_id'] = $item->code;
                $feedback['item_name'] = $item->name;
            }
        } elseif ($request->get('feedback_type') == config('constants.emailTo.discount')) {
            $email_headers['subject'] = 'Хочу дешевле';
            $email_headers['email_to'] = $email_to; //$user->email;
            if ($request->has('id_1c')) {
                $item = Item::where('id', $request->get('id_1c'))->first();
                $feedback['item_id'] = $item->code;
                $feedback['item_name'] = $item->name;
            }
        }

        $email_headers['email_from'] = setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';

        \Log::info(Carbon::parse('now') . 'Feedback : ' . print_r($feedback, true));
        \Log::info(Carbon::parse('now') . 'Header : ' . print_r($email_headers, true));

        \Mail::send('emails.feedback', ['feedback' => $feedback], function ($message) use ($email_headers, $attachFile) {
            $message->from($email_headers['email_from'], $email_headers['headers_from']);
            $message->to($email_headers['email_to'])->subject($email_headers['subject']);
            if ($attachFile) {
                $message->attach($attachFile->getPathname());
            }
        });

        if ($request->get('feedback_type') == config('constants.emailTo.claim')) {
            return redirect()->back();
        }

        return redirect('/pages/thanks_message');
    }


    public function search(Request $request)
    {
        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->first();
        $searchKeyword = $request->search_keywords;

        if($request->type == 'spares')
        {
            $type = 1;
            $take = 0;
        }

        if($request->type == 'products')
        {
            $type = 0;
            $take = 12;
        }

        // Обрезаем массив
        // ищем в цикле

        $searchKeywords = explode(' ', $searchKeyword);

        // узнаем является ли сервисным центром
        $client_type = intval($data['generalProfile']['is_service']); // если сервисный, то 1
        // если севисный центр
        if($client_type == 1) {
            $categories = Category::where('id', '>', 0);
            $items = Item::with('images')->where('id', '>', 0);
        } else {
            // если нет
            $categories = Category::where('1c_id', '!=', 20070)->where('parent_1c_id', '!=', 20070); // исключаем услуги
            $items = Item::with('images')->where([['is_component', '!=', 2], ['in_archive', 0]]); // исключаем услуги и архивные
        }

        $archive = Item::with('images')->where([['is_component', 0], ['in_archive', 1]]);

        if($type == 0) {
            $items = $items->where([['category_id_1c', '>',0]]);
        }
        elseif ($type == 1) {
            $items = $items->where([['is_component', '=', 1]]);
        }

        // $items_yes_low_cost = Item::where('category_id_1c','=','3149')->take(12)->get();

        //$cnt = 0;

        foreach ($searchKeywords as $keyword) {
            $categories->where('name', 'like', '%' . $keyword . '%');
            $items->where('name', 'like', '%' . $keyword . '%');
            $archive->where('name', 'like', '%' . $keyword . '%');
        }

        $items_yes = clone $items;
        $items_no = clone $items;
        $items_yes_low_cost = clone $items;
        $itemCode = clone $items;

        if(count($searchKeywords) == 1) {

            // поиск по коду товара
            $itemCode = $itemCode->OrWhere('1c_id',  $keyword )->first();
            if($itemCode != null && $itemCode->{'1c_id'} == $keyword){
                $findByCode = $itemCode;
            } else {
                $findByCode = [];
            }

            // поиск по артикулу
            $item_article = Item::where('vendor_code', 'like', '%'.$keyword.'%');
            $item_article_count = $item_article->get()->count();
            $item_article = $item_article->take(3)->get();

        }
        else {

            $findByCode = [];
            $item_article = [];
            $item_article_count = 0;

        }

        $itemCount = $items->count();

        $items_yes->where('count', '>', 0)->where('category_id_1c', '<>',  3149);
        $items_yes_count = $items_yes->get()->count();

        $items_no->where('count', '<=', 0);
        $items_no_count = $items_no->get()->count();

        $items_yes_low_cost = $items_yes_low_cost->where([['category_id_1c', '3149'], ['count', '>', 0]]);
        $items_yes_low_cost_count = $items_yes_low_cost->count();
        $items_yes_low_cost = $items_yes_low_cost->take(12)->get();

        $archive_count = $archive->get()->count();

        $categories = $categories->take($take)->get();


// dd($items->get());
        if($type == 0) {
            $itemCount += $archive->count();
        }

        $items = $items->take(20)->get();
        $archive = $archive->take(6)->get();
        $items_yes = $items_yes->take(12)->get();

        if($items_yes->count() < 12)
        {
            $take = 12 - $items_yes->count();
            if($take < 0)
                $take = 0;
        }
        else{ $take = 0; }
        $items_no = $items_no->take($take)->get();

        $idToCart = Cart::where('profile_id', $profile->id)->pluck('count', 'item_1c_id');

        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        // метка для сервисного центра
        $is_service = intval($profile->is_service);

        if (
            $categories->count() or
            $items->count() or
            $archive->count() or
            $findByCode != [] or
            $item_article != []
        ) {
            return view('search',
                [
                    'categories' => $categories,
                    'items_yes' => $items_yes->sortByDesc('price_bel'),
                    'items_yes_count' => $items_yes_count,
                    'itemCode' => $findByCode,
                    'itemArticle' => $item_article,
                    'item_article_count' => $item_article_count,
                    'items_no' => $items_no->sortByDesc('price_bel'),
                    'items_no_count' => $items_no_count,
                    'archive' => $archive->sortByDesc('price_bel'),
                    'archive_count' => $archive_count,
                    'items_yes_low_cost' => $items_yes_low_cost->sortByDesc('price_bel'),
                    'items_yes_low_cost_count' => $items_yes_low_cost_count,
                    'idToCart' => $idToCart,
                    'searchKeywords' => $searchKeywords,
                    'itemCount' => $itemCount,
                    'searchKeyword' => $searchKeyword,
                    'type' => $request->type,
                    'data_markup' => $markup,
                    'is_service' => $is_service,
                ]);
        } else {
            return 'false';
        }


    }


    public function getDiscount(Request $request)
    {
        $profile = $request->user()->profile()->first();

        $item = Item::where('id', $request->item_id)->first();

        if (!$profile->manager_email) {
            $profile->manager_email = setting('email_to_other');
        }

        $email_headers['subject'] = 'Хочу дешевле';
        $email_headers['email_to'] = $profile->manager_email; //$user->email;

        $email_headers['email_from'] = setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';

        \Mail::send('emails.getDiscount', ['item' => $item, 'profile' => $profile], function ($message) use ($email_headers) {
            $message->from($email_headers['email_from'], $email_headers['headers_from']);
            $message->to($email_headers['email_to'])->subject($email_headers['subject']);
        });

        return 'true';

    }


    public function updatePassword(Request $request)
    {

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();
        return 'true';
    }

    /**
     * @description Генерация прайс листа
     * @param Request $request
     */
    public function excelPrice(Request $request)
    {
        $this->getUserData($request);

        // определяем наценку - markup
        $markup = $this->profile->markup/100 + 1;

        // генерируем прайс (с учетом наценки)
        $price = $this->generatePrice($markup);

        Excel::create('price_from_' . Carbon::now()->format('d.m.Y'), function ($excel) use ($price) {
            $excel->sheet('Alfastok', function ($sheet) use ($price) {
                $sheet->fromArray($price);
                $sheet->setWidth('A', 5);
                $sheet->setWidth('B', 40);
                $sheet->setWidth('C', 50);
            });
        })->export('xls');

    }

    /**
     * @description Генерация прайс листа по хешу
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    // public function excelPriceFromHash(Request $request)
    // {
    //     if (!$request->has('userToken')) {
    //         return redirect('/');
    //     }
    //     $user = User::where('login_token', $request->get('userToken'))->first();

    //     if (!$user or $user->profile()->first()->is_blocked) {
    //         return redirect('/');
    //     }
    //     $this->user->profile()->first();
    //     $price = $this->generatePrice();

    //     Excel::create('price_from_' . Carbon::now()->format('d.m.Y'), function ($excel) use ($price) {
    //         $excel->sheet('Alfastok', function ($sheet) use ($price) {
    //             $sheet->fromArray($price);
    //             $sheet->setWidth('A', 5);
    //             $sheet->setWidth('B', 40);
    //             $sheet->setWidth('C', 50);
    //         });
    //     })->export('xls');
    // }


    public function excelPriceFromDirect(Request $request)
    {
        if (!$request->has('token')) {
            return redirect('/');
        }
        $profile = Profile::where('direct_token', $request->get('token'))->first();

        if(!$profile || $profile->is_blocked || !$profile->direct_permission){
            $note = "У Вас нет разрешения на данную операцию!<br>Свяжитесь с Вашим менеджером.";
            return redirect('/')->with(['note' => $note]);
        }

        Item::$priceType = $profile->type_price;

        // определяем наценку - markup
        $markup = $profile->markup/100 + 1;

        // генерируем прайс (с учетом наценки markup)
        $price = $this->generatePrice($markup);

        Excel::create('price_from_' . Carbon::now()->format('d.m.Y'), function ($excel) use ($price) {
            $excel->sheet('Alfastok', function ($sheet) use ($price) {
                $sheet->fromArray($price);
                $sheet->setWidth('A', 5);
                $sheet->setWidth('B', 40);
                $sheet->setWidth('C', 50);
            });
        })->export('xls');
    }

    public function excelPriceFromDirectZoomas(Request $request)
    {
        if (!$request->has('token')) {
            return redirect('/');
        }
        $profile = Profile::where('direct_token', $request->get('token'))->first();
        if (!$profile or $profile->is_blocked) {
            return redirect('/');
        }
        Item::$priceType = $profile->type_price;

        // определяем наценку - markup
        $markup = $profile->markup/100 + 1;

        // генерируем прайс (с учетом наценки markup)
        $price = $this->generatePrice($markup, $zoomas = 1);

        Excel::create('price_from_' . Carbon::now()->format('d.m.Y'), function ($excel) use ($price) {
            $excel->sheet('Alfastok', function ($sheet) use ($price) {
                $sheet->fromArray($price);
                $sheet->setWidth('A', 5);
                $sheet->setWidth('B', 40);
                $sheet->setWidth('C', 50);
            });
        })->export('xls');
    }


    public static function generatePrice($markup = 1, $zoomas = 0)
    {
        $categoriesRequest = Category::with(['items' => function ($query) {
            $query->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC');
        }, 'items.actions', 'items.gift', 'items.cheap_good', 'subCategory.subCategory'])->where('parent_1c_id', 0);

        $categories = $categoriesRequest->orderBy(\DB::raw('LCASE(default_sort)'), 'ASC')->whereNotIn('1c_id', [193, 20070])->get();

        $cheapItems = Item::where('cheap_goods', '!=', '')->pluck('cheap_goods');
        $cheapItemIds = [];
        foreach ($cheapItems as $cItem) {
            $cheapItemIds = array_merge($cheapItemIds, explode(',', $cItem));
        }
        //COM: так же можно сделать удаление дублей если они не удаляются


        // dd($categories);
        $price = [];
        foreach ($categories as $mc) {
            foreach ($mc->subCategory as $sc) {

                if ($sc->subCategory->count()) {

                    foreach ($sc->items as $item) {
                        if (in_array($item->{'1c_id'}, $cheapItemIds) || ($item->in_archive == 1 && $item->in_price == 0)) {
                            continue;
                        }

                        // для zoomas пропускаем позиции, где цена менялась в последние 7 дней
                        if ($zoomas && $item->date_change_price != '0000-00-00') {
                            if(strtotime('-7 day', strtotime('now')) < strtotime($item->date_change_price))
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

                        // вычисляем цены с учетом markup
                        $price_usd = $item->price_usd * $markup;
                        $price_byn = $item->price_bel * $markup;
                        // если получается дороже чем мрц, приравниваем к мрц
                        if($price_usd > $item->price_mr_usd || $price_byn > $item->price_mr_bel) {
                            $price_usd = $item->price_mr_usd;
                            $price_byn = $item->price_mr_bel;
                        }
                        // высчитываем максимальный markup (для формирования строки акций)
                        if($item->price_bel != 0) {
                            $maxMarkupBYN = $price_byn / $item->price_bel;

                            if($maxMarkupBYN < 1) {
                                $maxMarkupBYN = 1;
                            }
                        } else {
                            $maxMarkupBYN = 1;
                        }
                        // если есть акция от 1 шт, берем акционную цену
                        $price_list_usd = $item->createListPricesUSD();
                        $price_list_byn = $item->createListPricesBYN();
                        if($price_list_usd && $price_list_usd[0]['count'] == 1){
                            if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                $price_usd = $price_list_usd[0]['price'] * $maxMarkupBYN;
                                $price_byn = $price_list_byn[0]['price'] * $maxMarkupBYN;
                            }
                        }

                        $price_mr_bel = number_format($item->price_mr_bel, 2, ',', '');

                        // переписываем переменную
                        $price_mr_usd = number_format($item->price_mr_usd, 2, ',', '');
                        // меняем точку на запятую, округляем до 2 знаков
                        $price_usd = number_format(ceil($price_usd * 100) / 100, 2, ',', '');
                        $price_byn = number_format(ceil($price_byn * 100) / 100, 2, ',', '');

                        // формируем строку акционных цен с учетом markup
                        $actions = $item->createListPricesBYN();
                        $message_2 = '';
                        if($actions) {
                            foreach($actions as $key => $action) {
                                $newPrice = number_format(ceil($action['price'] * $maxMarkupBYN * 100) / 100, 2, ',', '');
                                $message_2 .= ' от ' . $action['count'] . ' шт '.$newPrice.' руб, ';
                            }
                        }
                        if ($message_2) {
                            $message_2 = substr($message_2, 0, -2);
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

                        $price[] = [
                            'Код' => $item->code,
                            //'Категория' => $mc->name .' -  '.$sc->name,
                            'Категория' => $sc->name,
                            'Наименование товара' => $item->name,
                            'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
                            'Цена BYN' => $price_byn,//(string)$price_byn,
                            'Розн USD' => $price_mr_usd,
                            'Розн BYN' => $price_mr_bel,
                            'Наличие' => $count,
                            'Дата поступления' => $item->count_text,
                            'Сообщение i' => $item->more_about,
                            'Сообщение %' => $item->mini_text . ' ' . $message_2,
                            'Комплектация' => $equipment,
                            'Характеристики' => $characteristics,
                            'Преимущества' => $item->content,
                            'Производитель' => $item->brand,
                            'Назначение' => $item->apply,
                            'Срок годности' => $item->shelf_life,
                            'Страна изготовления' => $item->country,
                            'Бренд' => $item->brand,
                            'Импортер' => $item->importer,
                            'Штрих-код' => $item->barcode,
                            'Сертификат' => $item->certificate,
                            'Габариты' => $dimensions,
                            'Вес с упаковкой' => $weight,
                            'Артикул' => $item->vendor_code,
                            'Гарантийный срок (месяцев)' => $guarantee_period,
                            'Новинка' => $is_new_item,
                            'Код ТН ВЭД' => $item->codeTNVD,
                        ];
                    }

                    foreach ($sc->subCategory as $scc) {

                        foreach ($scc->items as $item) {
                            if (in_array($item->{'1c_id'}, $cheapItemIds) || ($item->in_archive == 1 && $item->in_price == 0)) {
                                continue;
                            }

                            // для zoomas пропускаем позиции, где цена менялась в последние 7 дней
                            if ($zoomas && $item->date_change_price != '0000-00-00') {
                                if(strtotime('-7 day', strtotime('now')) < strtotime($item->date_change_price))
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

                            // вычисляем цены с учетом markup
                            $price_usd = $item->price_usd * $markup;
                            $price_byn = $item->price_bel * $markup;
                            // если получается дороже чем мрц, приравниваем к мрц
                            if($price_usd > $item->price_mr_usd || $price_byn > $item->price_mr_bel) {
                                $price_usd = $item->price_mr_usd;
                                $price_byn = $item->price_mr_bel;
                            }
                            // высчитываем максимальный markup (для формирования строки акций)
                            if($item->price_bel != 0) {
                                $maxMarkupBYN = $price_byn / $item->price_bel;

                                if($maxMarkupBYN < 1) {
                                    $maxMarkupBYN = 1;
                                }
                            } else {
                                $maxMarkupBYN = 1;
                            }

                            // если есть акция от 1 шт, берем акционную цену
                            $price_list_usd = $item->createListPricesUSD();
                            $price_list_byn = $item->createListPricesBYN();
                            if($price_list_usd && $price_list_usd[0]['count'] == 1){
                                if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                    $price_usd = $price_list_usd[0]['price'] * $maxMarkupBYN;
                                    $price_byn = $price_list_byn[0]['price'] * $maxMarkupBYN;
                                }
                            }

                            $price_mr_bel = number_format($item->price_mr_bel, 2, ',', '');

                            // переписываем переменную
                            $price_mr_usd = number_format($item->price_mr_usd, 2, ',', '');
                            // меняем точку на запятую, округляем до 2 знаков
                            $price_usd = number_format(ceil($price_usd * 100) / 100, 2, ',', '');
                            $price_byn = number_format(ceil($price_byn * 100) / 100, 2, ',', '');

                            // формируем строку акционных цен с учетом markup
                            $actions = $item->createListPricesBYN();
                            $message_2 = '';
                            if($actions) {
                                foreach($actions as $key => $action) {
                                    $newPrice = number_format(ceil($action['price'] * $maxMarkupBYN * 100) / 100, 2, ',', '');
                                    $message_2 .= ' от ' . $action['count'] . ' шт '.$newPrice.' руб, ';
                                }
                            }
                            if ($message_2) {
                                $message_2 = substr($message_2, 0, -2);
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

                            $price[] = [
                                'Код' => $item->code,
                                //'Категория' => $mc->name .' -  '.$sc->name .' - '. $scc->name,
                                //'Категория' => $categoryName,
                                'Категория' => $scc->name,
                                'Наименование товара' => $item->name,
                                'Цена USD' => $price_usd,
                                'Цена BYN' => $price_byn, //round((float)$price_usd * (float)setting('header_usd'), 2),
                                'Розн USD' => $price_mr_usd,
                                'Розн BYN' => $price_mr_bel,
                                'Наличие' => $count,
                                'Дата поступления' => $item->count_text,
                                'Сообщение i' => $item->more_about,
                                'Сообщение %' => $item->mini_text . ' ' . $message_2,
                                'Комплектация' => $equipment,
                                'Характеристики' => $characteristics,
                                'Преимущества' => $item->content,
                                'Производитель' => $item->brand,
                                'Назначение' => $item->apply,
                                'Срок годности' => $item->shelf_life,
                                'Страна изготовления' => $item->country,
                                'Бренд' => $item->brand,
                                'Импортер' => $item->importer,
                                'Штрих-код' => $item->barcode,
                                'Сертификат' => $item->certificate,
                                'Габариты' => $dimensions,
                                'Вес с упаковкой' => $weight,
                                'Артикул' => $item->vendor_code,
                                'Гарантийный срок (месяцев)' => $guarantee_period,
                                'Новинка' => $is_new_item,
                                'Код ТН ВЭД' => $item->codeTNVD,
                             ];
                        }
                    }
                } else {

                    foreach ($sc->items as $item) {
                        if (in_array($item->{'1c_id'}, $cheapItemIds) || ($item->in_archive == 1 && $item->in_price == 0)) {
                            continue;
                        }
                        // для zoomas пропускаем позиции, где цена менялась в последние 7 дней
                        if ($zoomas && $item->date_change_price != '0000-00-00') {
                            if(strtotime('-7 day', strtotime('now')) < strtotime($item->date_change_price))
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

                        // вычисляем цены с учетом markup
                        $price_usd = $item->price_usd * $markup;
                        $price_byn = $item->price_bel * $markup;
                        // если получается дороже чем мрц, приравниваем к мрц
                        if($price_usd > $item->price_mr_usd || $price_byn > $item->price_mr_bel) {
                            $price_usd = $item->price_mr_usd;
                            $price_byn = $item->price_mr_bel;
                        }
                        // высчитываем максимальный markup (для формирования строки акций)
                        if($item->price_bel != 0) {
                            $maxMarkupBYN = $price_byn / $item->price_bel;

                            if($maxMarkupBYN < 1) {
                                $maxMarkupBYN = 1;
                            }
                        } else {
                            $maxMarkupBYN = 1;
                        }

                        // если есть акция от 1 шт, берем акционную цену
                        $price_list_usd = $item->createListPricesUSD();
                        $price_list_byn = $item->createListPricesBYN();
                        if($price_list_usd && $price_list_usd[0]['count'] == 1){
                            if($price_list_byn && $price_list_byn[0]['count'] == 1) {
                                $price_usd = $price_list_usd[0]['price'] * $maxMarkupBYN;
                                $price_byn = $price_list_byn[0]['price'] * $maxMarkupBYN;
                            }
                        }

                        $price_mr_bel = number_format($item->price_mr_bel, 2, ',', '');

                        // переписываем переменную
                        $price_mr_usd = number_format($item->price_mr_usd, 2, ',', '');
                        // меняем точку на запятую, округляем до 2 знаков
                        $price_usd = number_format(ceil($price_usd * 100) / 100, 2, ',', '');
                        $price_byn = number_format(ceil($price_byn * 100) / 100, 2, ',', '');

                        // формируем строку акционных цен с учетом markup
                        $actions = $item->createListPricesBYN();
                        $message_2 = '';
                        if($actions) {
                            foreach($actions as $key => $action) {
                                $newPrice = number_format(ceil($action['price'] * $maxMarkupBYN * 100) / 100, 2, ',', '');
                                $message_2 .= ' от ' . $action['count'] . ' шт '.$newPrice.' руб, ';
                            }
                        }
                        if ($message_2) {
                            $message_2 = substr($message_2, 0, -2);
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

                        $price[] = [
                            'Код' => $item->code,
                            //'Категория' => $mc->name .' -  '.$sc->name,
                            'Категория' => $sc->name,
                            'Наименование товара' => $item->name,
                            'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
                            'Цена BYN' => $price_byn,//(string)$price_byn,
                            'Розн USD' => $price_mr_usd,
                            'Розн BYN' => $price_mr_bel,
                            'Наличие' => $count,
                            'Дата поступления' => $item->count_text,
                            'Сообщение i' => $item->more_about,
                            'Сообщение %' => $item->mini_text . ' ' . $message_2,
                            'Комплектация' => $equipment,
                            'Характеристики' => $characteristics,
                            'Преимущества' => $item->content,
                            'Производитель' => $item->brand,
                            'Назначение' => $item->apply,
                            'Срок годности' => $item->shelf_life,
                            'Страна изготовления' => $item->country,
                            'Бренд' => $item->brand,
                            'Импортер' => $item->importer,
                            'Штрих-код' => $item->barcode,
                            'Сертификат' => $item->certificate,
                            'Габариты' => $dimensions,
                            'Вес с упаковкой' => $weight,
                            'Артикул' => $item->vendor_code,
                            'Гарантийный срок (месяцев)' => $guarantee_period,
                            'Новинка' => $is_new_item,
                            'Код ТН ВЭД' => $item->codeTNVD,
                        ];
                    }
                }
            }

        }
        return $price;
    }

//     public static function generatePriceShops($markup = 1) // для Гугл таблицы
//     {

//         $categories = Category::where('parent_1c_id', 0)->whereNotIn('1c_id', [193, 20070, 16305])->orderBy('default_sort')->with('subCategory')->get();

//         $price = [];
//         foreach ($categories as $mc) {

//             foreach ($mc->subCategory as $sc) {

//                 if ($sc->subCategory->count()) {

//                     foreach ($sc->items->where('in_archive', '!=', 1) as $item) {

//                         $count = $item->count;
//                         if ($item->count > 10) {
//                             $count = '> 10';
//                         } elseif ($item->count <= 0) {
//                             if ($item->count_type == 3) {
//                                 $count = 'Нет';
//                             } elseif ($item->count_type == 2) {
//                                 $count = 'Резерв';
//                             } else {
//                                 $count = 'Нет';
//                             }
//                         }

//                         if ($item->more_about == 'Подробнее о товаре:') {
//                             $item->more_about = '';
//                         }

//                         // вычисляем цены с учетом markup
//                         $price_usd = $item->price_usd * $markup;
//                         $price_byn = $item->price_bel * $markup;
//                         // если получается дороже чем мрц, приравниваем к мрц
//                         if($price_usd > $item->price_mr_usd || $price_byn > $item->price_mr_bel) {
//                             $price_usd = $item->price_mr_usd;
//                             $price_byn = $item->price_mr_bel;
//                         }
//                         // высчитываем максимальный markup (для формирования строки акций)
//                         if($item->price_bel != 0) {
//                             $maxMarkupBYN = $price_byn / $item->price_bel;
//                         } else {
//                             $maxMarkupBYN = 1;
//                         }
//                         // если есть акция от 1 шт, берем акционную цену
//                         $price_list_usd = $item->createListPricesUSD();
//                         $price_list_byn = $item->createListPricesBYN();
//                         if($price_list_usd && $price_list_usd[0]['count'] == 1){
//                             if($price_list_byn && $price_list_byn[0]['count'] == 1) {
//                                 $price_usd = $price_list_usd[0]['price'] * $maxMarkupBYN;
//                                 $price_byn = $price_list_byn[0]['price'] * $maxMarkupBYN;
//                             }
//                         }

//                         $price_mr_bel = number_format($item->price_mr_bel, 2, ',', '');

//                         // переписываем переменную
//                         $price_mr_usd = number_format($item->price_mr_usd, 2, ',', '');
//                         // меняем точку на запятую, округляем до 2 знаков
//                         $price_usd = number_format(ceil($price_usd * 100) / 100, 2, ',', '');
//                         $price_byn = number_format(ceil($price_byn * 100) / 100, 2, ',', '');

//                         // формируем строку акционных цен с учетом markup
//                         $actions = $item->createListPricesBYN();
//                         $message_2 = '';
//                         if($actions) {
//                             foreach($actions as $key => $action) {
//                                 $newPrice = number_format(ceil($action['price'] * $maxMarkupBYN * 100) / 100, 2, ',', '');
//                                 $message_2 .= ' от ' . $action['count'] . ' шт '.$newPrice.' руб, ';
//                             }
//                         }
//                         if ($message_2) {
//                             $message_2 = substr($message_2, 0, -2);
//                         }

//                         preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
//                         if(isset($matches[1]) && isset($matches[2])){
//                             if($matches[1] == 1)
//                             {
//                                 //$price_byn = $matches[2];
//                             }
//                         }

//                         // удаляем лишние пробелы в Комплектация
//                         $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

//                         // Формируем Характеристики
//                         $characteristics = "";
//                         foreach($item->charValues as $charValues) {
//                             if($charValues->characteristic) {
//                                 $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
//                             }
//                         }

//                         // Формируем Габариты
//                         if($item->depth && $item->width && $item->height) {
//                             $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                         } else {
//                             $dimensions ="";
//                         }

//                         // Формируем Вес с упаковкой
//                         if($item->weight != '0.00') {
//                             $weight = str_replace('.', ',', $item->weight).' кг';
//                         } else {
//                             $weight ="";
//                         }

//                         // Гарантийный срок меняем 0 на пусто
//                         if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
//                             else $guarantee_period = '';

//                         // Если новинка, пишем да
//                         if($item->is_new_item == 1) $is_new_item = 'Да';
//                             else $is_new_item = '';

//                         $price[] = [
//                             'Код' => $item->code,
//                             //'Категория' => $mc->name .' -  '.$sc->name,
//                             'Категория' => $sc->name,
//                             'Наименование товара' => $item->name,
//                             'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
//                             'Цена BYN' => $price_byn,//(string)$price_byn,
//                             'Розн USD' => $price_mr_usd,
//                             'Розн BYN' => $price_mr_bel,
//                             'Наличие' => $count,
//                             'Дата поступления' => $item->count_text,
//                             'Сообщение i' => $item->more_about,
//                             'Сообщение %' => $item->mini_text . ' ' . $message_2,
//                             'Комплектация' => $equipment,
//                             'Характеристики' => $characteristics,
//                             'Преимущества' => $item->content,
//                             'Производитель' => $item->brand,
//                             'Назначение' => $item->apply,
//                             'Срок годности' => $item->shelf_life,
//                             'Страна изготовления' => $item->country,
//                             'Бренд' => $item->brand,
//                             'Импортер' => $item->importer,
//                             'Штрих-код' => $item->barcode,
//                             'Сертификат' => $item->certificate,
//                             'Габариты' => $dimensions,
//                             'Вес с упаковкой' => $weight,
//                             'Артикул' => $item->vendor_code,
//                             'Гарантийный срок (месяцев)' => $guarantee_period,
//                             'Новинка' => $is_new_item,
//                         ];
//                     }

//                     foreach ($sc->subCategory as $scc) {

// // ВРЕМЕННО! исключаем запасные части.
// if($scc->{'1c_id'} == 21650 || $scc->{'1c_id'} == 18093 || $scc->{'1c_id'} == 16320 || $scc->{'1c_id'} == 16335 || $scc->{'1c_id'} == 17697) {
//     continue;
// }


//                         foreach ($scc->items->where('in_archive', '!=', 1) as $item) {

//                             $count = $item->count;
//                             if ($item->count > 10) {
//                                 $count = '> 10';
//                             } elseif ($item->count <= 0) {
//                                 if ($item->count_type == 3) {
//                                     $count = 'Нет';
//                                 } elseif ($item->count_type == 2) {
//                                     $count = 'Резерв';
//                                 } else {
//                                     $count = 'Нет';
//                                 }
//                             }

//                             if ($item->more_about == 'Подробнее о товаре:') {
//                                 $item->more_about = '';
//                             }

//                             $categoryName = $sc->name;
//                             if ($scc->name) {
//                                 $categoryName = $scc->name;
//                             }

//                             // вычисляем цены с учетом markup
//                             $price_usd = $item->price_usd * $markup;
//                             $price_byn = $item->price_bel * $markup;
//                             // если получается дороже чем мрц, приравниваем к мрц
//                             if($price_usd > $item->price_mr_usd || $price_byn > $item->price_mr_bel) {
//                                 $price_usd = $item->price_mr_usd;
//                                 $price_byn = $item->price_mr_bel;
//                             }
//                             // высчитываем максимальный markup (для формирования строки акций)
//                             if($item->price_bel != 0) {
//                                 $maxMarkupBYN = $price_byn / $item->price_bel;
//                             } else {
//                                 $maxMarkupBYN = 1;
//                             }
//                             // если есть акция от 1 шт, берем акционную цену
//                             $price_list_usd = $item->createListPricesUSD();
//                             $price_list_byn = $item->createListPricesBYN();
//                             if($price_list_usd && $price_list_usd[0]['count'] == 1){
//                                 if($price_list_byn && $price_list_byn[0]['count'] == 1) {
//                                     $price_usd = $price_list_usd[0]['price'] * $maxMarkupBYN;
//                                     $price_byn = $price_list_byn[0]['price'] * $maxMarkupBYN;
//                                 }
//                             }

//                             $price_mr_bel = number_format($item->price_mr_bel, 2, ',', '');

//                             // переписываем переменную
//                             $price_mr_usd = number_format($item->price_mr_usd, 2, ',', '');
//                             // меняем точку на запятую, округляем до 2 знаков
//                             $price_usd = number_format(ceil($price_usd * 100) / 100, 2, ',', '');
//                             $price_byn = number_format(ceil($price_byn * 100) / 100, 2, ',', '');

//                             // формируем строку акционных цен с учетом markup
//                             $actions = $item->createListPricesBYN();
//                             $message_2 = '';
//                             if($actions) {
//                                 foreach($actions as $key => $action) {
//                                     $newPrice = number_format(ceil($action['price'] * $maxMarkupBYN * 100) / 100, 2, ',', '');
//                                     $message_2 .= ' от ' . $action['count'] . ' шт '.$newPrice.' руб, ';
//                                 }
//                             }
//                             if ($message_2) {
//                                 $message_2 = substr($message_2, 0, -2);
//                             }

//                             preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
//                             if(isset($matches[1]) && isset($matches[2])){
//                                 if($matches[1] == 1)
//                                 {
//                                     //$price_byn = $matches[2];
//                                     //$price_usd = str_replace('.', ',', $item->price_usd);
//                                 }
//                             }

//                             // удаляем лишние пробелы в Комплектация
//                             $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

//                             // Формируем Характеристики
//                             $characteristics = "";
//                             foreach($item->charValues as $charValues) {
//                                 if($charValues->characteristic) {
//                                     $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
//                                 }
//                             }

//                             // Формируем Габариты
//                             if($item->depth && $item->width && $item->height) {
//                                 $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                             }

//                             // Формируем Габариты
//                             if($item->depth && $item->width && $item->height) {
//                                 $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                             } else {
//                                 $dimensions ="";
//                             }

//                             // Формируем Вес с упаковкой
//                             if($item->weight != '0.00') {
//                                 $weight = str_replace('.', ',', $item->weight).' кг';
//                             } else {
//                                 $weight ="";
//                             }

//                             // Гарантийный срок меняем 0 на пусто
//                             if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
//                                 else $guarantee_period = '';

//                             // Если новинка, пишем да
//                             if($item->is_new_item == 1) $is_new_item = 'Да';
//                                 else $is_new_item = '';

//                             $price[] = [
//                                 'Код' => $item->code,
//                                 //'Категория' => $mc->name .' -  '.$sc->name .' - '. $scc->name,
//                                 //'Категория' => $categoryName,
//                                 'Категория' => $scc->name,
//                                 'Наименование товара' => $item->name,
//                                 'Цена USD' => $price_usd,
//                                 'Цена BYN' => $price_byn, //round((float)$price_usd * (float)setting('header_usd'), 2),
//                                 'Розн USD' => $price_mr_usd,
//                                 'Розн BYN' => $price_mr_bel,
//                                 'Наличие' => $count,
//                                 'Дата поступления' => $item->count_text,
//                                 'Сообщение i' => $item->more_about,
//                                 'Сообщение %' => $item->mini_text . ' ' . $message_2,
//                                 'Комплектация' => $equipment,
//                                 'Характеристики' => $characteristics,
//                                 'Преимущества' => $item->content,
//                                 'Производитель' => $item->brand,
//                                 'Назначение' => $item->apply,
//                                 'Срок годности' => $item->shelf_life,
//                                 'Страна изготовления' => $item->country,
//                                 'Бренд' => $item->brand,
//                                 'Импортер' => $item->importer,
//                                 'Штрих-код' => $item->barcode,
//                                 'Сертификат' => $item->certificate,
//                                 'Габариты' => $dimensions,
//                                 'Вес с упаковкой' => $weight,
//                                 'Артикул' => $item->vendor_code,
//                                 'Гарантийный срок (месяцев)' => $guarantee_period,
//                                 'Новинка' => $is_new_item,
//                              ];
//                         }
//                     }
//                 } else {

//                     foreach ($sc->items->where('in_archive', '!=', 1) as $item) {

//                         $count = $item->count;
//                         if ($item->count > 10) {
//                             $count = '> 10';
//                         } elseif ($item->count <= 0) {
//                             if ($item->count_type == 3) {
//                                 $count = 'Нет';
//                             } elseif ($item->count_type == 2) {
//                                 $count = 'Резерв';
//                             } else {
//                                 $count = 'Нет';
//                             }
//                         }

//                         if ($item->more_about == 'Подробнее о товаре:') {
//                             $item->more_about = '';
//                         }

//                         // вычисляем цены с учетом markup
//                         $price_usd = $item->price_usd * $markup;
//                         $price_byn = $item->price_bel * $markup;
//                         // если получается дороже чем мрц, приравниваем к мрц
//                         if($price_usd > $item->price_mr_usd || $price_byn > $item->price_mr_bel) {
//                             $price_usd = $item->price_mr_usd;
//                             $price_byn = $item->price_mr_bel;
//                         }
//                         // высчитываем максимальный markup (для формирования строки акций)
//                         if($item->price_bel != 0) {
//                             $maxMarkupBYN = $price_byn / $item->price_bel;
//                         } else {
//                             $maxMarkupBYN = 1;
//                         }
//                         // если есть акция от 1 шт, берем акционную цену
//                         $price_list_usd = $item->createListPricesUSD();
//                         $price_list_byn = $item->createListPricesBYN();
//                         if($price_list_usd && $price_list_usd[0]['count'] == 1){
//                             if($price_list_byn && $price_list_byn[0]['count'] == 1) {
//                                 $price_usd = $price_list_usd[0]['price'] * $maxMarkupBYN;
//                                 $price_byn = $price_list_byn[0]['price'] * $maxMarkupBYN;
//                             }
//                         }

//                         $price_mr_bel = number_format($item->price_mr_bel, 2, ',', '');

//                         // переписываем переменную
//                         $price_mr_usd = number_format($item->price_mr_usd, 2, ',', '');
//                         // меняем точку на запятую, округляем до 2 знаков
//                         $price_usd = number_format(ceil($price_usd * 100) / 100, 2, ',', '');
//                         $price_byn = number_format(ceil($price_byn * 100) / 100, 2, ',', '');

//                         // формируем строку акционных цен с учетом markup
//                         $actions = $item->createListPricesBYN();
//                         $message_2 = '';
//                         if($actions) {
//                             foreach($actions as $key => $action) {
//                                 $newPrice = number_format(ceil($action['price'] * $maxMarkupBYN * 100) / 100, 2, ',', '');
//                                 $message_2 .= ' от ' . $action['count'] . ' шт '.$newPrice.' руб, ';
//                             }
//                         }
//                         if ($message_2) {
//                             $message_2 = substr($message_2, 0, -2);
//                         }

//                         preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
//                         if(isset($matches[1]) && isset($matches[2])){
//                             if($matches[1] == 1)
//                             {
//                                 //$price_byn = $matches[2];
//                             }
//                         }

//                         // удаляем лишние пробелы в Комплектация
//                         $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

//                         // Формируем Характеристики
//                         $characteristics = "";
//                         foreach($item->charValues as $charValues) {
//                             if($charValues->characteristic) {
//                                 $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
//                             }
//                         }

//                         // Формируем Габариты
//                         if($item->depth && $item->width && $item->height) {
//                             $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                         }

//                         // Формируем Габариты
//                         if($item->depth && $item->width && $item->height) {
//                             $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                         } else {
//                             $dimensions ="";
//                         }

//                         // Формируем Вес с упаковкой
//                         if($item->weight != '0.00') {
//                             $weight = str_replace('.', ',', $item->weight).' кг';
//                         } else {
//                             $weight ="";
//                         }

//                         // Гарантийный срок меняем 0 на пусто
//                         if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
//                             else $guarantee_period = '';

//                         // Если новинка, пишем да
//                         if($item->is_new_item == 1) $is_new_item = 'Да';
//                             else $is_new_item = '';

//                         $price[] = [
//                             'Код' => $item->code,
//                             //'Категория' => $mc->name .' -  '.$sc->name,
//                             'Категория' => $sc->name,
//                             'Наименование товара' => $item->name,
//                             'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
//                             'Цена BYN' => $price_byn,//(string)$price_byn,
//                             'Розн USD' => $price_mr_usd,
//                             'Розн BYN' => $price_mr_bel,
//                             'Наличие' => $count,
//                             'Дата поступления' => $item->count_text,
//                             'Сообщение i' => $item->more_about,
//                             'Сообщение %' => $item->mini_text . ' ' . $message_2,
//                             'Комплектация' => $equipment,
//                             'Характеристики' => $characteristics,
//                             'Преимущества' => $item->content,
//                             'Производитель' => $item->brand,
//                             'Назначение' => $item->apply,
//                             'Срок годности' => $item->shelf_life,
//                             'Страна изготовления' => $item->country,
//                             'Бренд' => $item->brand,
//                             'Импортер' => $item->importer,
//                             'Штрих-код' => $item->barcode,
//                             'Сертификат' => $item->certificate,
//                             'Габариты' => $dimensions,
//                             'Вес с упаковкой' => $weight,
//                             'Артикул' => $item->vendor_code,
//                             'Гарантийный срок (месяцев)' => $guarantee_period,
//                             'Новинка' => $is_new_item,
//                         ];
//                     }
//                 }
//             }

//         }
//         return $price;
//     }

//     public static function generatePriceVek()
//     {

//         $categories = Category::where('parent_1c_id', 0)->whereNotIn('1c_id', [193, 20070, 16305, 12710, 4774])->orderBy('default_sort')->with('subCategory')->get();

//         $price = [];
//         foreach ($categories as $mc) {

//             foreach ($mc->subCategory as $sc) {

//                 if ($sc->subCategory->count()) {
//                     foreach ($sc->items->where('in_archive', '!=', 1) as $item) {

//                          // ПРОПУСКАЕМ, ЕСЛИ ЕСТЬ ПОЗИЦИИ:
//                         // if($item->{'1c_id'} == 18061 ||
//                         //     $item->{'1c_id'} == 18062 ||
//                         //     $item->{'1c_id'} == 18133 ||
//                         //     $item->{'1c_id'} == 18132 ||
//                         //     $item->{'1c_id'} == 12052 ||
//                         //     $item->{'1c_id'} == 12053) continue;

//                         $count = $item->count;
//                         if ($item->count > 10) {
//                             $count = '> 10';
//                         }
//                         if ($item->count <= 2) {
//                             $count = 'Нет';
//                         }

//                         if ($item->more_about == 'Подробнее о товаре:') {
//                             $item->more_about = '';
//                         }

//                         $message_2 = $item->viewPriceList();

//                         $price_list_usd = $item->createListPricesUSD();
//                         $price_list_byn = $item->createListPricesBYN();

//                         $price_usd = str_replace('.', ',', $item->price_usd);
//                         $price_byn = str_replace('.', ',', $item->price_bel);

//                         if($price_list_usd && $price_list_usd[0]['count'] == 1){
//                             if($price_list_byn && $price_list_byn[0]['count'] == 1) {
//                                 $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
//                                 $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
//                             }
//                         }

//                         preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
//                         if(isset($matches[1]) && isset($matches[2])){
//                             if($matches[1] == 1)
//                             {
//                                 //$price_byn = $matches[2];
//                             }
//                         }


//                         // удаляем лишние пробелы в Комплектация
//                         $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

//                         // Формируем Характеристики
//                         $characteristics = "";
//                         foreach($item->charValues as $charValues) {
//                             if($charValues->characteristic) {
//                                 $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
//                             }
//                         }

//                         // Формируем Габариты
//                         if($item->depth && $item->width && $item->height) {
//                             $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                         } else {
//                             $dimensions ="";
//                         }

//                         // Формируем Вес с упаковкой
//                         if($item->weight != '0.00') {
//                             $weight = str_replace('.', ',', $item->weight).' кг';
//                         } else {
//                             $weight ="";
//                         }

//                         // Гарантийный срок меняем 0 на пусто
//                         if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
//                             else $guarantee_period = '';

//                         // Если новинка, пишем да
//                         if($item->is_new_item == 1) $is_new_item = 'Да';
//                             else $is_new_item = '';

//                         $price[] = [
//                             'Код' => $item->code,
//                             //'Категория' => $mc->name .' -  '.$sc->name,
//                             'Категория' => $sc->name,
//                             'Наименование товара' => $item->name,
//                             'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
//                             'Цена BYN' => $price_byn,//(string)$price_byn,
//                             'Розн USD' => str_replace('.', ',', $item->price_mr_usd),
//                             'Розн BYN' => str_replace('.', ',', $item->price_mr_bel),
//                             'Наличие' => $count,
//                             'Дата поступления' => $item->count_text,
//                             'Сообщение i' => $item->more_about,
//                             'Сообщение %' => $item->mini_text . ' ' . $message_2,
//                             'Комплектация' => $equipment,
//                             'Характеристики' => $characteristics,
//                             'Преимущества' => $item->content,
//                             'Производитель' => $item->brand,
//                             'Назначение' => $item->apply,
//                             'Срок годности' => $item->shelf_life,
//                             'Страна изготовления' => $item->country,
//                             'Бренд' => $item->brand,
//                             'Импортер' => $item->importer,
//                             'Штрих-код' => $item->barcode,
//                             'Сертификат' => $item->certificate,
//                             'Габариты' => $dimensions,
//                             'Вес с упаковкой' => $weight,
//                             'Артикул' => $item->vendor_code,
//                             'Гарантийный срок (месяцев)' => $guarantee_period,
//                             'Новинка' => $is_new_item,
//                         ];
//                     }

//                     foreach ($sc->subCategory as $scc) {

//                         foreach ($scc->items->where('in_archive', '!=', 1) as $item) {

//                              // ПРОПУСКАЕМ, ЕСЛИ ЕСТЬ ПОЗИЦИИ:
//                             // if($item->{'1c_id'} == 18061 ||
//                             //     $item->{'1c_id'} == 18062 ||
//                             //     $item->{'1c_id'} == 18133 ||
//                             //     $item->{'1c_id'} == 18132 ||
//                             //     $item->{'1c_id'} == 12052 ||
//                             //     $item->{'1c_id'} == 12053) continue;

//                             $count = $item->count;
//                             if ($item->count > 10) {
//                                 $count = '> 10';
//                             }
//                             if ($item->count <= 2) {
//                                 $count = 'Нет';
//                             }

//                             if ($item->more_about == 'Подробнее о товаре:') {
//                                 $item->more_about = '';
//                             }

//                             $categoryName = $sc->name;
//                             if ($scc->name) {
//                                 $categoryName = $scc->name;
//                             }

//                             $message_2 = $item->viewPriceList();

//                             $price_list_usd = $item->createListPricesUSD();
//                             $price_list_byn = $item->createListPricesBYN();

//                             $price_usd = str_replace('.', ',', $item->price_usd);
//                             $price_byn = str_replace('.', ',', $item->price_bel);

//                             if($price_list_usd && $price_list_usd[0]['count'] == 1){
//                                 if($price_list_byn && $price_list_byn[0]['count'] == 1){
//                                     $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
//                                     $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
//                                 }
//                             }

//                             preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
//                             if(isset($matches[1]) && isset($matches[2])){
//                                 if($matches[1] == 1)
//                                 {
//                                     //$price_byn = $matches[2];
//                                     //$price_usd = str_replace('.', ',', $item->price_usd);
//                                 }
//                             }


//                             // удаляем лишние пробелы в Комплектация
//                             $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

//                             // Формируем Характеристики
//                             $characteristics = "";
//                             foreach($item->charValues as $charValues) {
//                                 if($charValues->characteristic) {
//                                     $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
//                                 }
//                             }

//                             // Формируем Габариты
//                             if($item->depth && $item->width && $item->height) {
//                                 $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                             } else {
//                                 $dimensions ="";
//                             }

//                             // Формируем Вес с упаковкой
//                             if($item->weight != '0.00') {
//                                 $weight = str_replace('.', ',', $item->weight).' кг';
//                             } else {
//                                 $weight ="";
//                             }

//                             // Гарантийный срок меняем 0 на пусто
//                             if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
//                                 else $guarantee_period = '';

//                             // Если новинка, пишем да
//                             if($item->is_new_item == 1) $is_new_item = 'Да';
//                                 else $is_new_item = '';

//                             $price[] = [
//                                 'Код' => $item->code,
//                                 //'Категория' => $mc->name .' -  '.$sc->name,
//                                 'Категория' => $sc->name,
//                                 'Наименование товара' => $item->name,
//                                 'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
//                                 'Цена BYN' => $price_byn,//(string)$price_byn,
//                                 'Розн USD' => str_replace('.', ',', $item->price_mr_usd),
//                                 'Розн BYN' => str_replace('.', ',', $item->price_mr_bel),
//                                 'Наличие' => $count,
//                                 'Дата поступления' => $item->count_text,
//                                 'Сообщение i' => $item->more_about,
//                                 'Сообщение %' => $item->mini_text . ' ' . $message_2,
//                                 'Комплектация' => $equipment,
//                                 'Характеристики' => $characteristics,
//                                 'Преимущества' => $item->content,
//                                 'Производитель' => $item->brand,
//                                 'Назначение' => $item->apply,
//                                 'Срок годности' => $item->shelf_life,
//                                 'Страна изготовления' => $item->country,
//                                 'Бренд' => $item->brand,
//                                 'Импортер' => $item->importer,
//                                 'Штрих-код' => $item->barcode,
//                                 'Сертификат' => $item->certificate,
//                                 'Габариты' => $dimensions,
//                                 'Вес с упаковкой' => $weight,
//                                 'Артикул' => $item->vendor_code,
//                                 'Гарантийный срок (месяцев)' => $guarantee_period,
//                                 'Новинка' => $is_new_item,
//                             ];
//                         }
//                     }
//                 } else {


//                     foreach ($sc->items->where('in_archive', '!=', 1) as $item) {

//                          // ПРОПУСКАЕМ, ЕСЛИ ЕСТЬ ПОЗИЦИИ:
//                         // if($item->{'1c_id'} == 18061 ||
//                         //     $item->{'1c_id'} == 18062 ||
//                         //     $item->{'1c_id'} == 18133 ||
//                         //     $item->{'1c_id'} == 18132 ||
//                         //     $item->{'1c_id'} == 12052 ||
//                         //     $item->{'1c_id'} == 12053) continue;

//                        $count = $item->count;
//                         if ($item->count > 10) {
//                             $count = '> 10';
//                         }
//                         if ($item->count <= 2) {
//                             $count = 'Нет';
//                         }

//                         if ($item->more_about == 'Подробнее о товаре:') {
//                             $item->more_about = '';
//                         }

//                         $message_2 = $item->viewPriceList();

//                         $price_list_usd = $item->createListPricesUSD();
//                         $price_list_byn = $item->createListPricesBYN();

//                         $price_usd = str_replace('.', ',', $item->price_usd);
//                         $price_byn = str_replace('.', ',', $item->price_bel);

//                         if($price_list_usd && $price_list_usd[0]['count'] == 1){
//                             if($price_list_byn && $price_list_byn[0]['count'] == 1) {
//                                 $price_usd = str_replace('.', ',', $price_list_usd[0]['price']);
//                                 $price_byn = str_replace('.', ',', $price_list_byn[0]['price']);
//                             }
//                         }

//                         preg_match('/(\d{1,3})шт.*?([\d.]{1,})/', $message_2, $matches);
//                         if(isset($matches[1]) && isset($matches[2])){
//                             if($matches[1] == 1)
//                             {
//                                 //$price_byn = $matches[2];
//                             }
//                         }


//                         // удаляем лишние пробелы в Комплектация
//                         $equipment = trim(preg_replace('| +|', ' ', $item->equipment));

//                         // Формируем Характеристики
//                         $characteristics = "";
//                         foreach($item->charValues as $charValues) {
//                             if($charValues->characteristic) {
//                                 $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit." \n";
//                             }
//                         }

//                         // Формируем Габариты
//                         if($item->depth && $item->width && $item->height) {
//                             $dimensions = $item->depth."X".$item->width."X".$item->height."мм";
//                         } else {
//                             $dimensions ="";
//                         }

//                         // Формируем Вес с упаковкой
//                         if($item->weight != '0.00') {
//                             $weight = str_replace('.', ',', $item->weight).' кг';
//                         } else {
//                             $weight ="";
//                         }

//                         // Гарантийный срок меняем 0 на пусто
//                         if($item->guarantee_period) $guarantee_period = $item->guarantee_period;
//                             else $guarantee_period = '';

//                         // Если новинка, пишем да
//                         if($item->is_new_item == 1) $is_new_item = 'Да';
//                             else $is_new_item = '';

//                         $price[] = [
//                             'Код' => $item->code,
//                             //'Категория' => $mc->name .' -  '.$sc->name,
//                             'Категория' => $sc->name,
//                             'Наименование товара' => $item->name,
//                             'Цена USD' => $price_usd, //str_replace('.', ',', $item->usd_price),
//                             'Цена BYN' => $price_byn,//(string)$price_byn,
//                             'Розн USD' => str_replace('.', ',', $item->price_mr_usd),
//                             'Розн BYN' => str_replace('.', ',', $item->price_mr_bel),
//                             'Наличие' => $count,
//                             'Дата поступления' => $item->count_text,
//                             'Сообщение i' => $item->more_about,
//                             'Сообщение %' => $item->mini_text . ' ' . $message_2,
//                             'Комплектация' => $equipment,
//                             'Характеристики' => $characteristics,
//                             'Преимущества' => $item->content,
//                             'Производитель' => $item->brand,
//                             'Назначение' => $item->apply,
//                             'Срок годности' => $item->shelf_life,
//                             'Страна изготовления' => $item->country,
//                             'Бренд' => $item->brand,
//                             'Импортер' => $item->importer,
//                             'Штрих-код' => $item->barcode,
//                             'Сертификат' => $item->certificate,
//                             'Габариты' => $dimensions,
//                             'Вес с упаковкой' => $weight,
//                             'Артикул' => $item->vendor_code,
//                             'Гарантийный срок (месяцев)' => $guarantee_period,
//                             'Новинка' => $is_new_item,
//                         ];
//                     }
//                 }
//             }
//         }

//         return $price;
//     }

    public function testMailChimp()
    {
        $mailChimp = \App::make('\App\Helpers\MailChimpAlfa');

        dd($mailChimp->deleteSubscriber($list = '6f836bc284', $email = 'develop@zmitroc.by', $name = 'Тестовая компания'));
    }


    /**
     * @description SERVICE Прописывает всем пользователям новый токен для входа по ссылке.
     */
    public function generateLoginToken()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->login_token = str_slug(str_random(25));
            $user->save();
        }

    }


    /**
     * @description SERVICE Прописывает всем пользователям новый токен для входа по ссылке.
     */
    public function generateDirectToken()
    {
        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            $profile->direct_token = str_slug(str_random(25));
            $profile->save();
        }

    }


    public function enterFromUser()
    {
        \Auth::loginUsingId(22); //40
        return redirect('/');
    }

}
