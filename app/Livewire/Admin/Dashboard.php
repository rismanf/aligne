<?php

namespace App\Livewire\Admin;

use App\Models\Participant;
use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    public $participants, $total_sponsor, $total_participant, $total_partner;
    public $need_attention, $approved, $rejected;
    public $showModal = false;
    public $type;
    public $data_list = [];

    public function showDetailModal($type)
    {

        switch ($type) {
            case "All Participant":
                $this->data_list = Participant::all();
                break;
            case "Participant General Admission":
                $this->data_list = Participant::where('user_type', 'General Admission')->get();
                break;
            case "Participant Sponsor":
                $this->data_list = Participant::where('user_type', 'Sponsor')->get();
                break;
            case "Participant Partner":
                $this->data_list = Participant::where('user_type', 'Partner')->get();
                break;
            case "Participant Need Attention":
                $this->data_list = Participant::where('status', 'created')->get();
                break;
            case "Participant Approved":
                $this->data_list = Participant::where('status', 'approved')->get();
                break;
            case "Participant Rejected":
                $this->data_list = Participant::where('status', 'rejected')->get();
                break;
            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }


        $this->type = $type;
        $this->showModal = true;
    }

    public function mount()
    {
        $data = Participant::all();
        $this->participants = $data->count();
        $this->total_sponsor = $data->where('user_type', 'Sponsor')->count();
        $this->total_participant = $data->where('user_type', 'General Admission')->count();
        $this->total_partner = $data->where('user_type', 'Partner')->count();

        $this->need_attention = $data->where('status', 'created')->count();
        $this->approved = $data->where('status', 'approved')->count();
        $this->rejected = $data->where('status', 'rejected')->count();
    }
    public function render()
    {
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                // 'link' => route("home"), // route('home') = nama route yang ada di web.php
                'label' => 'Dashboard', // label yang ditampilkan di breadcrumb
                // 'icon' => 's-dashboard',
            ],
        ];

        return view('livewire.admin.dashboard')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Home',
        ]);
    }
}
