<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandCertificate;
use App\Models\BrandGuide;
use App\Models\Page;
use Illuminate\Http\Request;


class PagesController extends Controller
{

    public function show(Request $request, $alias){
        // $data = $this->getUserData($request);
        $page = Page::where('alias', $alias)->firstOrFail();

        if($page->is_active != 1){
            return redirect('/', 301);
        }

        // if($data['generalProfile']->is_service == 1) {
        //     $view = "service.order_answer"; // если сервис
        // } else {
            $view = "pages.show";
        // }

        return view($view, [
            'article' => $page,
            'pageName' => $page->title,
            'breadcrumbs' => [
                $page->title => '',
            ],

        ]);
    }

    public function guides(Request $request){
        $data = $this->getUserData($request);
        $page = Page::where('alias', 'guides')->firstOrFail();

        return view('guides', [
            'pageName' => $page->title,
            'breadcrumbs' => [
                $page->title => '',
            ],

            'brands' => Brand::orderBy('created_at', 'desc')->get(),
            'brandGuides' => BrandGuide::orderBy('name', 'asc')->get()
        ])->with($data);
    }


    public function certificates(Request $request){
        return redirect('/');

        // $data = $this->getUserData($request);
        // $page = Page::where('alias', 'certificates')->firstOrFail();

        // return view('guides', [
        //     'pageName' => $page->title,
        //     'breadcrumbs' => [
        //         $page->title => '',
        //     ],

        //     'brands' => Brand::orderBy('created_at', 'desc')->get(),
        //     'brandGuides' => BrandCertificate::orderBy('name', 'asc')->get()
        // ])->with($data);
    }


}
