<?php

namespace App\Livewire\Admin\Participant;

use App\Models\CodeCoupon;
use App\Models\Event;
use App\Models\Participant;
use App\Models\ParticipantAnswers;
use App\Models\Questions;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Mary\Traits\Toast;

class EditParticipant extends Component
{
    use Toast;
    public $participantId;

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
        $coupon_code,
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
            'coupon_code' => 'nullable|string|max:100',
            'company' => 'required|string|max:255',
        ]);
        // Ambil semua question_id dari database
        $questions  = Questions::with('options')->get();
        $coupon_code = $this->coupon_code ?? null;
        if (!empty($coupon_code)) {
            $event = CodeCoupon::where('code', $coupon_code)->first();
            // Cek apakah kode kupon valid
            if (!$event) {
                $this->addError('coupon_code', 'Kode kupon tidak valid.');
            } else {
                if ($event->is_active === false) {
                    $this->addError('coupon_code', 'Kode kupon sudah digunakan.');
                }
            }
        }
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
            $this->toast(
                type: 'error',
                title: 'Validation Error',
                description: 'Please fix the errors before submitting.',
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-warning',
                timeout: 3000,
                redirectTo: null
            );
            return;
        }


        DB::beginTransaction();
        try {
            $event_id = Event::where('is_active', true)->first()->id; // Ganti dengan event_id yang sesuai
            $id_user = auth()->id();
            $price =  5000000;
            if (!empty($coupon_code)) {
                // Update status kupon jika digunakan
                $event->update([
                    'is_active' => false,
                    'updated_by_id' => $id_user,
                ]);
                $price =  0;
            }

            $user = Participant::where('id', $this->participantId)->first();
            if (!$user) {
                $this->toast(
                    type: 'error',
                    title: 'Participant Not Found',
                    description: 'The participant you are trying to edit does not exist.',
                    position: 'toast-top toast-end',
                    icon: 'o-information-circle',
                    css: 'alert-danger',
                    timeout: 3000,
                    redirectTo: null
                );
                return;
            }
            // Update data peserta
            $user->update([
                'event_id' => $event_id,
                'full_name' => $this->first_name . ' ' . $this->last_name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'company' => $this->company,
                'country' => $this->country,
                'job_title' => $this->job_title,
                'price' => $price,
                'updated_by_id' => $id_user,
            ]);
            // Simpan jawaban peserta

            foreach ($questions as $question) {
                $qid = $question->id;
                ParticipantAnswers::where('participant_id', $user->id)->delete();
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
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->toast(
                type: 'error',
                title: 'Error',
                description: 'Failed to create participant: ' . $e->getMessage(),
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-danger',
                timeout: 3000,
                redirectTo: null
            );
        }
    }
    public function mount($id)
    {
        $this->participantId = $id;
        // Load participant data based on ID
        $participant = Participant::with('answers')->findOrFail($this->participantId);
        $this->name = $participant->name;
        $this->first_name = $participant->first_name;
        $this->last_name = $participant->last_name;
        $this->phone = $participant->phone;
        $this->nik = $participant->nik;
        $this->job_title = $participant->job_title;
        $this->department = $participant->department;
        $this->email = $participant->email;
        $this->type_user = $participant->type_user;
        $this->country = $participant->country;
        $this->coupon_code = $participant->coupon_code;
        $this->company = $participant->company;

        // Load answers if available

        foreach ($participant->answers as $answer) {

            $qid = $answer->question_id;
            $aid = $answer->answer_id;

            if ($answer->questions->question_type === 'multiple') {
                $answers[$qid][$aid] = true; // simpan sebagai array untuk checkbox
            } else {
                $answers[$qid] = $aid; // simpan satu jawaban
            }
        }
        $this->answers = $answers ?? [];
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
                'label' => 'Edit Participant',
            ],
        ];
        $questions = Questions::with('options')->get();

        return view('livewire.admin.participant.edit-participant', [
            'questions' => $questions,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
