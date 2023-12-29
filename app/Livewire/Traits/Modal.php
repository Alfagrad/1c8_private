<?php

namespace App\Livewire\Traits;

use Livewire\Attributes\On;

trait Modal
{

    public bool $modalShow = false;

    public function modalClose(): void
    {
        $this->modalShow = false;
    }

    #[On('modal-open')]
    public function modelOpen(): void
    {
        $this->modalShow = true;
    }

}
