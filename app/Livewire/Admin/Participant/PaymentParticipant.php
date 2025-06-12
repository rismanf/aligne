<?php

namespace App\Livewire\Admin\Participant;

use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PaymentParticipant extends Component
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

        $participants = Participant::where('created_by_id', Auth::id())
            ->where('invoice_code', null)
            ->paginate(5);
        $participants->getCollection()->transform(function ($val, $index) use ($participants) {
            $val->row_number = ($participants->currentPage() - 1) * $participants->perPage() + $index + 1;
            return $val;
        });




        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.participant.payment-participant', [
            't_headers' => $t_headers,
            'participants' => $participants,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
