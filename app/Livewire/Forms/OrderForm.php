<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class OrderForm extends Form
{

    #[Validate('nullable|string')]
    public string $comment = '';

}
