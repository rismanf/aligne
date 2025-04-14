<?php

namespace App\Livewire;

use Livewire\Component;
use Mary\Traits\Toast;

class Home extends Component
{
    use Toast;

    public function render()
    {
        return view("livewire.home");
    }
}
