<?php

namespace App\Livewire\Auth;

use App\Models\Country;
use App\Models\Event;
use App\Models\master_data;
use App\Models\Participant;
use App\Models\ParticipantAnswers;
use App\Models\Questions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public $first_name, $last_name, $company, $job, $country, $phone, $email, $industry, $user_type;
    public $answers = [];
    public $country_list, $job_list, $industry_list;
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


            $user = Participant::create([
                'event_id' => $event_id,
                'full_name' => $this->first_name . ' ' . $this->last_name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'company' => $this->company,
                'country' => $this->country,
                'job_title' => $this->job,
                'industry' => $this->industry,
                'user_type' => $this->user_type,
                'price' => $price,
                'status' => 'created',
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
                redirectTo: route('public.register')
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

        $this->industry_list = master_data::where('type', 'industry_sector')->get();
    }
    public function render()
    {
        $questions = Questions::with('options')->get();

        return view('livewire.auth.register', [
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
