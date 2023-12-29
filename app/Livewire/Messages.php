<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Messages extends Component
{

    public array $errors = [];
    public array $messages = [
        'error' => [],
        'success' => []
    ];
    public array $success = [];

    #[On('error')]
    public function error(array|string $message = ''): void
    {
        $this->reset('messages');
        $session = session()->get('error', '');
        $this->messages['error'] = is_array($session) ? $session : [$session];
        $this->messages['error'] = array_merge($this->messages['error'], is_array($message) ? $message : [$message]);
        $this->messages['error'] = array_filter($this->messages['error']);
    }

    #[On('success')]
    public function success(array|string $message = ''): void
    {
        $this->reset('messages');
        $session = session()->get('success', '');
        $this->messages['success'] = is_array($session) ? $session : [$session];
        $this->messages['success'] = array_merge($this->messages['success'], is_array($message) ? $message : [$message]);
        $this->messages['success'] = array_filter($this->messages['success']);

    }

    public function render(): View
    {
        return view('livewire.messages');
    }

}
