<?php

namespace App\Livewire\Admin\Participant;

use App\Models\Questions;
use Livewire\Component;

class AddParticipant extends Component
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
                'link' => route('admin.participant.index'), // route('home') = nama route yang ada di web.php
                'label' => 'Participant',
            ],
            [
                'link' => '', // route('home') = nama route yang ada di web.php
                'label' => 'Create Participant',
            ],
        ];

        $questions = Questions::with('options')->get();

        return view('livewire.admin.participant.add-participant', [
            'questions' => $questions,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
