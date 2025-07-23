<?php

namespace App\Livewire\Admin\Participant;

use App\Mail\ApproveRegisterMail;
use App\Mail\RejectedRegisterMail;
use App\Models\Invoice;
use App\Models\Participant;
use App\Models\Questions_option;
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

    public $select_status = '';
    public $select_type_participant = '';
    public $select_golf = '';
    public $select_topic = '';
    public $status = [
        ['id' => 'Waiting', 'name' => 'Waiting'],
        ['id' => 'Approved', 'name' => 'Approved'],
        ['id' => 'Rejected', 'name' => 'Rejected']
    ];
    public $type_participant = [
        ['id' => 'General Admission', 'name' => 'General Admission'],
        ['id' => 'Sponsor', 'name' => 'Sponsor'],
        ['id' => 'Partner', 'name' => 'Partner']
    ];
    public $options_topic = [];
    public $options_golf = [];


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

        if ($status == 'Approved') {


            $qr = new QrCode($participants->code);
            $writer = new PngWriter();
            $result = $writer->write($qr);

            $filename = "qrcodes/{$participants->code}.png";

            // Upload ke MinIO (pastikan visibility = public)
            Storage::disk('s3')->put($filename, $result->getString(), 'public');

            // Dapatkan URL publik
            $qr_url = Storage::disk('s3')->url($filename);

            Mail::to($participants->email)->send(new ApproveRegisterMail($participants->full_name, $participants->code, $qr_url));
        } else {
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
    public function mount()
    {
        // $this->status = [
        //     'created' => 'created',
        //     'approved' => 'approved',
        //     'rejected' => 'rejected'
        // ];

        $options = Questions_option::wherein('question_id', [5, 6])->get();
        $this->options_topic = $options->where('question_id', 5)->map(fn($o) => ['id' => $o->option, 'name' => $o->option])->values()->toArray();
        $this->options_golf = $options->where('question_id', 6)->map(fn($o) => ['id' => $o->option, 'name' => $o->option])->values()->toArray();
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

        $query = Participant::with('answers', 'topicAnswer', 'golfAnswer');

        if ($this->select_status) {
            $query->where('status', $this->select_status);
        }

        if ($this->select_type_participant) {
            $query->where('user_type', $this->select_type_participant);
        }
        if ($this->select_topic) {
            $query->whereHas('topicAnswer', function ($q) {
                $q->where('answer', $this->select_topic);
            });
        }

        if ($this->select_golf) {
            $query->whereHas('golfAnswer', function ($q) {
                $q->where('answer', $this->select_golf);
            });
        }

        $sortableColumns = ['full_name', 'email', 'company', 'job_title', 'status', 'user_type'];

        if (in_array($this->sortBy['column'], $sortableColumns)) {
            $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
        }

        $participants = $query->paginate(10);

        $participants->getCollection()->transform(function ($val, $index) use ($participants) {
            $val->row_number = ($participants->currentPage() - 1) * $participants->perPage() + $index + 1;
            return $val;
        });

        if ($this->sortBy['column'] === 'topic_answers') {
            $sorted = $participants->getCollection()->sortBy(function ($participant) {
                return $participant->topicAnswer?->answer ?? '';
            }, SORT_REGULAR, $this->sortBy['direction'] === 'desc');

            $participants->setCollection($sorted->values());
        } else if ($this->sortBy['column'] === 'golf_answers') {
            $sorted = $participants->getCollection()->sortBy(function ($participant) {
                return $participant->golfAnswer?->answer ?? '';
            }, SORT_REGULAR, $this->sortBy['direction'] === 'desc');

            $participants->setCollection($sorted->values());
        }

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'user_type', 'label' => 'Type'],
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'job_title', 'label' => 'Job Title'],
            ['key' => 'topic_answers', 'label' => 'Topic'],
            ['key' => 'golf_answers', 'label' => 'Golf'],
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
