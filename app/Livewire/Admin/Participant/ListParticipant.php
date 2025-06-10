<?php

namespace App\Livewire\Admin\Participant;

use App\Models\Participant;
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

        $participants = Participant::paginate(3);

        $participants->getCollection()->transform(function ($val, $index) use ($participants) {
            $val->row_number = ($participants->currentPage() - 1) * $participants->perPage() + $index + 1;
            return $val;
        });


        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'author', 'label' => 'Author'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.participant.list-participant', [
            't_headers' => $t_headers,
            'participants' => $participants,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
