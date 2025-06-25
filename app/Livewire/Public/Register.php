<?php

namespace App\Livewire\Public;

use App\Events\NewUserRegistered;
use App\Events\PodcastProcessed;
use App\Mail\NewRegisterEmail;
use App\Mail\NewRegisterPICMail;
use App\Models\Event;
use App\Models\ManageMail;
use App\Models\master_data;
use App\Models\Participant;
use App\Models\ParticipantAnswers;
use App\Models\Questions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public $first_name, $last_name, $company,  $phone, $email,  $user_type;
    public $country = '';
    public $job = '';
    public $industry = '';
    public $answers = [];
    public $country_list, $job_list, $industry_list;
    public $questionForm = false;
    public function showQuestions()
    {
        $this->questionForm = true;
    }


    public function hideQuestions()
    {
        $this->questionForm = false;
    }
    public function save()
    {
        $this->validate([
            'user_type' => 'required',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'job' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($this->user_type == 1) {

            $questions  = Questions::with('options')->get();
            // Cek apakah semua pertanyaan dijawab
            foreach ($questions as $question) {
                $qid = $question->id;

                if ($question->question_type === 'multiple') {
                    // Validasi minimal 1 checkbox dicentang
                    if (empty($this->answers[$qid]) || !collect($this->answers[$qid])->filter()->count()) {
                        $this->addError("answers.$qid", 'Select at least one answer.');
                    }
                } else {
                    // Validasi radio wajib diisi
                    if (empty($this->answers[$qid])) {
                        $this->addError("answers.$qid", 'This question is required.');
                    }
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
            $id_user = 1;
            $price =  0;
            $user_type_name = master_data::where('type', 'user_type')->where('code', $this->user_type)->first()->name;
            // $user = Participant::create([
            //     'event_id' => $event_id,
            //     'full_name' => $this->first_name . ' ' . $this->last_name,
            //     'first_name' => $this->first_name,
            //     'last_name' => $this->last_name,
            //     'phone' => $this->phone,
            //     'email' => $this->email,
            //     'company' => $this->company,
            //     'country' => $this->country,
            //     'job_title' => $this->job,
            //     'industry' => $this->industry,
            //     'user_type_id' => $this->user_type,
            //     'user_type' => $user_type_name,
            //     'price' => $price,
            //     'status' => 'created',
            //     'created_by_id' => $id_user,
            //     'updated_by_id' => $id_user,
            // ]);

            // Simpan jawaban peserta
            // if ($this->user_type == 1) {
            //     foreach ($questions as $question) {
            //         $qid = $question->id;

            //         if ($question->question_type === 'multiple') {
            //             foreach ($this->answers[$qid] ?? [] as $optionId => $isChecked) {
            //                 if ($isChecked) {
            //                     ParticipantAnswers::create([
            //                         'event_id' => $event_id,
            //                         'participant_id' => $user->id,
            //                         'question_id' => $qid,
            //                         'question' => $question->question,
            //                         'answer_id' => $optionId,
            //                         'answer' => $question->options->where('id', $optionId)->first()->option ?? null,
            //                         'created_by_id' => $id_user,
            //                         'updated_by_id' => $id_user,
            //                     ]);
            //                 }
            //             }
            //         } else {
            //             $optionId = $this->answers[$qid];
            //             ParticipantAnswers::create([
            //                 'event_id' => $event_id,
            //                 'participant_id' => $user->id,
            //                 'question_id' => $qid,
            //                 'question' => $question->question,
            //                 'answer_id' => $optionId,
            //                 'answer' => $question->options->where('id', $optionId)->first()->option ?? null,
            //                 'created_by_id' => $id_user,
            //                 'updated_by_id' => $id_user,
            //             ]);
            //         }
            //     }
            // }
            //send mail User
            Mail::to('risman.firmansyah@neutradc.com')->send(new NewRegisterEmail($this->first_name));

            //send mail PIC
            if ($this->user_type == 1) {
                $type_mail = 'Register General';
            } elseif ($this->user_type == 2) {
                $type_mail = 'Register Sponsor';
            } elseif ($this->user_type == 3) {
                $type_mail = 'Register Partner';
            }

            $data_send_mail = [
                'full_name' => $this->first_name . ' ' . $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'company' => $this->company,
                'country' => $this->country,
                'job_title' => $this->job,
                'industry' => $this->industry,
                'user_type' => $user_type_name,
            ];

            // Mail::to('risman.firmansyah@neutradc.com')->send(new NewRegisterPICMail($data_send_mail));
            // Kirim event
            event(new \App\Events\NewUserRegistered($data_send_mail, $type_mail));

            Log::info("2222");
            $this->success(
                'Participant created successfully!',
                redirectTo: route('register')
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


    public function mount()
    {

        $data_country =  master_data::where('type', 'country')->orderby('parent_code')->orderby('name')->get();
        $this->country_list = $data_country->groupBy('parent_code')->map(function ($items) {
            return $items->values()->toArray();
        })->toArray();

        $data_job =  master_data::where('type', 'job')->orderby('code')->get();
        $this->job_list = $data_job->groupBy('parent_code')->map(function ($items) {
            return $items->values()->toArray();
        })->toArray();

        $this->industry_list = master_data::where('type', 'industries')->get();
    }
    public function render()
    {
        $questions = Questions::with('options')->get();

        return view('livewire.public.register', [
            'questions' => $questions,
        ])->layout('components.layouts.website', [
            'title' => 'Register | NeutraDC',
            'description' => 'NeutraDC is a leading data center provider offering colocation, cloud computing, and managed services in Indonesia and Southeast Asia.',
            'keywords' => 'register, neutradc,neutradc summit, data center, colocation, cloud computing, managed services',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
