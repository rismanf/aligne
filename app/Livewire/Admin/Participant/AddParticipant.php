<?php

namespace App\Livewire\Admin\Participant;

use App\Models\Event;
use App\Models\Participant;
use App\Models\ParticipantAnswers;
use App\Models\Questions;
use Livewire\Component;
use Mary\Traits\Toast;

class AddParticipant extends Component
{
    use Toast;

    public $name,
        $first_name,
        $last_name,
        $phone,
        $nik,
        $job_title,
        $department,
        $email,
        $type_user,
        $country,
        $company;
    public $answers = [];

    public function save()
    {
        // Validasi inputan peserta
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'country' => 'nullable|string|max:100',
            'company' => 'required|string|max:255',
        ]);
        // Ambil semua question_id dari database
        $questions  = Questions::with('options')->get();

        // Cek apakah semua pertanyaan dijawab
        foreach ($questions as $question) {
            $qid = $question->id;

            if ($question->question_type === 'multiple') {
                // Validasi minimal 1 checkbox dicentang
                if (empty($this->answers[$qid]) || !collect($this->answers[$qid])->filter()->count()) {
                    $this->addError("answers.$qid", 'Pilih minimal satu jawaban.');
                }
            } else {
                // Validasi radio wajib diisi
                if (empty($this->answers[$qid])) {
                    $this->addError("answers.$qid", 'Pertanyaan ini wajib dijawab.');
                }
            }
        }

        // Jika ada error, jangan lanjut simpan
        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }
        $event_id = Event::where('is_active', true)->first()->id; // Ganti dengan event_id yang sesuai
        $id_user = auth()->id();
        $user = Participant::create([
            'event_id' => $event_id,
            'full_name' => $this->first_name . ' ' . $this->last_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'company' => $this->company,
            'country' => $this->country,
            'job_title' => $this->job_title,
            'created_by_id' => $id_user,
            'updated_by_id' => $id_user,
        ]);
        // Simpan jawaban peserta

        foreach ($questions as $question) {
            $qid = $question->id;

            if ($question->question_type === 'multiple') {
                foreach ($this->answers[$qid] ?? [] as $optionId => $isChecked) {
                    if ($isChecked) {
                        ParticipantAnswers::create([
                            'event_id' => $event_id,
                            'participant_id' => $user->id,
                            'question_id' => $qid,
                            'question' => $question->question,
                            'answer_id' => $optionId,
                            'answer' => $question->options->where('id', $optionId)->first()->option ?? null,
                            'created_by_id' => $id_user,
                            'updated_by_id' => $id_user,
                        ]);
                    }
                }
            } else {
                $optionId = $this->answers[$qid];
                ParticipantAnswers::create([
                    'event_id' => $event_id,
                    'participant_id' => $user->id,
                    'question_id' => $qid,
                    'question' => $question->question,
                    'answer_id' => $optionId,
                    'answer' => $question->options->where('id', $optionId)->first()->option ?? null,
                    'created_by_id' => $id_user,
                    'updated_by_id' => $id_user,
                ]);
            }
        }
        $this->success(
            'Participant created successfully!',
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
