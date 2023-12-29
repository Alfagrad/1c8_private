<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use PDF;


class PricetagController extends Controller
{
    public function pricetag(Request $request, $itemId)
    {
        $data = $this->getUserData($request);

        // берем данные товара
        $item = Item::where('1c_id', $itemId)->first();

        // формируем файл с характеристиками
        $characteristics = '';
        foreach($item->charValues as $charValues) {
            if($charValues->characteristic) {
                $characteristics .= $charValues->characteristic->name.": ".$charValues->value." ".$charValues->characteristic->unit."\n";
            }
        }

        return view('catalog.pricetag_form', compact(
            'item',
            'characteristics',
            'itemId'
        ))->with($data);
    }

    public function pdfDownload(Request $request, $itemId) {

        // определяем картинку логотипа брэнда
        if(!empty($request->brand_logo)) $brand_pic = $request->brand_logo.".jpg";
            else $brand_pic = "";

        // определяем путь к изображению
        $item = Item::where('1c_id', $itemId)->first();
        if(count($item->images)) $item_pic_path = $item->images[0]->path_image;
            else $item_pic_path = '';
        // получаем баркод
        $barcode = $item->barcode;
        // определяем тип ценника
        if (!empty($request->type_string)) $type = $request->type_string;
            else $type = '';

        // меняем перенос строк на <br>
        $advantages = str_replace("\r\n", "<br>", $request->advantages);
        $characteristics = mb_substr(str_replace("\r\n", "<br>", $request->characteristics), 0, 600);
        $complect = mb_substr(str_replace("\r\n", "<br>", $request->complect), 0, 600);

        $output = [
            'name' => $request->name,
            'brand_pic' => $brand_pic,
            'item_pic_path' => $item_pic_path,
            'type' => $type,
            'type_price' => $request->type_price,
            'price_old' => $request->price_old,
            'price_new' => $request->price_new,
            'advantages' => $advantages,
            'characteristics' => $characteristics,
            'complect' => $complect,
            'country' => $request->country,
            'line_checker' => $request->line_checker,
            'red_string' => $request->red_string,
            'barcode' => $barcode,
        ];
//dd($output);
        // определяем имя pdf странички
        $pdf_page = $request->type_list;

    	$pdf = PDF::loadView("pdf.".$pdf_page, compact('output'));

        if ($pdf_page == 'a5') {
            return $pdf->setPaper('a4', 'landscape')->download("table_".$pdf_page."_".$itemId.".pdf");
        } else {
            return $pdf->download("table_".$pdf_page."_".$itemId.".pdf");
        }

    }
}
