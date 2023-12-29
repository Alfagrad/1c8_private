<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class CatalogComposer
{
    public function compose(View $view) {

        if (isset($_COOKIE['opt_state'])) {
            if ($_COOKIE['opt_state']) {
                $opt_style = 'block';
            } else {
                $opt_style = 'none';
            }
        } else {
            $opt_style = 'block';
        }

        if (isset($_COOKIE['purcent_state'])) {
            if ($_COOKIE['purcent_state']) {
                $purcent_style = 'block';
            } else {
                $purcent_style = 'none';
            }
        } else {
            $purcent_style = 'block';
        }

        if (isset($_COOKIE['mr_state'])) {
            if ($_COOKIE['mr_state']) {
                $mr_style = 'block';
            } else {
                $mr_style = 'none';
            }
        } else {
            $mr_style = 'none';
        }

        return $view->with(compact('opt_style', 'purcent_style', 'mr_style'));
    }
}
