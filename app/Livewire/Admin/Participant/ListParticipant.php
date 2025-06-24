<?php

namespace App\Livewire\Admin\Participant;

use App\Models\Invoice;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ListParticipant extends Component
{
    use Toast;
    use WithPagination;

    public $selectedUserId,
        $total_price,
        $total_participant;
    public $detail_participants = [];
    public bool $confirmModal = false;
    public bool $showModal = false;
    public bool $deleteModal = false;

    public array $sortBy = ['column' => 'full_name', 'direction' => 'asc'];

    public function showDeleteModal($userId)
    {
        $this->detail_participants = Participant::find($userId);
        $this->selectedUserId = $userId;
        $this->deleteModal = true;
    }

    public function deleteParticipant()
    {
        $participant = Participant::find($this->selectedUserId);
        if ($participant) {
            $participant->delete();
            $this->success(
                'Success',
                'Participant deleted successfully!',
                redirectTo: route('admin.participant.index')
            );
        } else {
            $this->error('Error', 'Participant not found.');
        }
        $this->deleteModal = false;
    }

    public function showDetailModal($id_user)
    {
        $this->detail_participants = Participant::find($id_user);

        $this->selectedUserId = $id_user;
        $this->showModal = true;
    }
    public function showConfirmModal($id_user)
    {
        $this->selectedUserId = $id_user;
        $participants = Participant::where('created_by_id', $id_user)->get();
        $this->total_participant = $participants->count();
        $this->total_price = $participants->sum('price');
        $this->confirmModal = true;
    }

    public function confirm($id)
    {
        $participants = Participant::where('created_by_id', $id)->get();
        $inv_count = Invoice::count();
        $invoice_code = substr(date('Y'), -1) . str_pad(date('m'), 2, '0', STR_PAD_LEFT) . str_pad($inv_count + 1, '0', STR_PAD_LEFT);
        Invoice::create([
            'event_id' =>  $participants->first()->event_id,
            'invoice_code' => $invoice_code,
            'total_price' => $this->total_price,
            'total_participants' => $this->total_participant,
            'status' => 'unpaid',
            'created_by_id' => Auth::id(),
            'updated_by_id' => Auth::id(),
        ]);

        $participants->each(function ($participant) use ($invoice_code) {
            $participant->update([
                'invoice_code' => $invoice_code,
                'status' => 'unpaid',
            ]);
        });

        $this->success(
            'Success',
            'Invoice created successfully!',
            redirectTo: route('admin.invoice.index')
        );
    }

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
                // 'link' => route("admin.role.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Participant',
            ],
        ];
        $id_user = Auth::id();
        // if (Auth::user()->hasRole('User')) {
        //     $participants = Participant::where('created_by_id', $id_user)->paginate(10);
        //     $this->total_price = $participants->where('status', 'created')->sum('price');
        //     $this->total_participant = $participants->where('status', 'created')->count();
        // } else {
        //     $participants = Participant::paginate(10);
        //     $this->total_price = $participants->sum('price');
        //     $this->total_participant = $participants->count();
        // }

      

        $participants = Participant::orderBy(...array_values($this->sortBy))
            ->paginate(10);
        $participants->getCollection()->transform(function ($val, $index) use ($participants) {
            $val->row_number = ($participants->currentPage() - 1) * $participants->perPage() + $index + 1;
            return $val;
        });

        // Uncomment the line below to debug the total price

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'price', 'label' => 'Price'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.participant.list-participant', [
            't_headers' => $t_headers,
            'participants' => $participants,
            'id_user' => $id_user,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
