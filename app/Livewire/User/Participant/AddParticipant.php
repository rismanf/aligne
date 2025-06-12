<?php

namespace App\Livewire\User\Participant;

use Livewire\Component;

class AddParticipant extends Component
{
    public function render()
    {
        return view('livewire.user.participant.add-participant')->layout('components.layouts.user', [
                'breadcrumbs' => '',
                'title' => '',
            ]);
    }
}
