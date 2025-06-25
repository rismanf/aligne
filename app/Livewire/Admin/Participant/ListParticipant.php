<?php

namespace App\Livewire\Admin\Participant;

use App\Mail\ApproveRegisterMail;
use App\Mail\RejectedRegisterMail;
use App\Models\Invoice;
use App\Models\Participant;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ListParticipant extends Component
{
    use Toast;
    use WithPagination;

    public $selectedUserId,
        $total_price,
        $total_participant,
        $status_participant;
    public $detail_participants = [];
    public bool $confirmModal = false;
    public bool $showModal = false;
    public bool $deleteModal = false;

    public array $sortBy = ['column' => 'full_name', 'direction' => 'asc'];

    public function showDeleteModal($userId)
    {
        $this->detail_participants = Participant::with('answers')->find($userId);
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
    public function showConfirmModal($id_user, $status)
    {
        $this->selectedUserId = $id_user;
        $this->detail_participants = Participant::find($id_user);
        $this->status_participant = $status;
        $this->confirmModal = true;
    }

    public function confirm($id, $status)
    {
        // dd($id, $status);
        $participants = Participant::find($id);

        if ($status == 'approved') {


            $qr = new QrCode($participants->code);
            $writer = new PngWriter();
            $result = $writer->write($qr);

            $filename = "qrcodes/{$participants->code}.png";

            // Upload ke MinIO (pastikan visibility = public)
            Storage::disk('s3')->put($filename, $result->getString(), 'public');

            // Dapatkan URL publik
            $qr_url = Storage::disk('s3')->url($filename);

            Mail::to($participants->email)->send(new ApproveRegisterMail($participants->full_name, $participants->code, $qr_url));
        }else{
            Mail::to($participants->email)->send(new RejectedRegisterMail($participants->full_name));
        }

        $participants->update([
            'status' => $status,
        ]);


        $this->success(
            'Success',
            'Participant updated to ' . $status . ' successfully!',
            redirectTo: route('admin.participant.index')
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
            ['key' => 'user_type', 'label' => 'Type'],
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'job_title', 'label' => 'Job Title'],
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
