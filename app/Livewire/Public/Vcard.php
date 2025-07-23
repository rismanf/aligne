<?php

namespace App\Livewire\Public;

use App\Models\Vard;
use Livewire\Component;

class Vcard extends Component
{
    public $data;
        public function render()
    {
        $this->data = Vard::first();

        return view('livewire.public.vcard')->layout('components.layouts.vcard',[
            'title' => 'Vcard',
        ]);
    }
}
