<?php

namespace App\Actions;

class IsServiceCanBuy
{

    public function __invoke(): bool
    {
        return session()->get('service_components_or_repair', false);
    }

    public function toggle(): void
    {
        session()->put('service_components_or_repair', !$this->__invoke());
    }

}
