<?php

namespace App\Livewire\Admin;

use App\Models\UserSchedule;
use Livewire\Component;

class Visit extends Component
{
    public $search_code;
    public $user;

    public function search()
    {
        $this->user = UserSchedule::where('code', $this->search_code)->first();
    }
    public function render()
    {
        return view('livewire.admin.visit')->layout('components.layouts.app', [
            'breadcrumbs' => [
                ['link' => route("admin.home"), 'label' => 'Home', 'icon' => 's-home'],
                ['label' => 'Visit'],
            ],
            'title' => 'Visit Management',
        ]);
    }
}
