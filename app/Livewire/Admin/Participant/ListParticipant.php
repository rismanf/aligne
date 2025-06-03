<?php

namespace App\Livewire\Admin\Participant;

use Livewire\Component;

class ListParticipant extends Component
{

    public function render()
    {
        $title = 'Participant Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("user.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Admin',
            ],
            [
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Participant',
            ],
        ];
        // return view('livewire.admin.participant.list-participant');
        return view('livewire.maintenance')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
