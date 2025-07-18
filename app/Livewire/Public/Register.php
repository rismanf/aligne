<?php

namespace App\Livewire\Public;

use App\Events\NewUserRegistered;
use App\Events\PodcastProcessed;
use App\Mail\NewRegisterEmail;
use App\Mail\NewRegisterNotificationEmail;
use App\Mail\NewRegisterPICMail;
use App\Mail\RegisterSponsorPatnerEmail;
use App\Models\Event;
use App\Models\ManageMail;
use App\Models\master_data;
use App\Models\Participant;
use App\Models\ParticipantAnswers;
use App\Models\Questions;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public $name, $phone, $email,  $password, $password_confirmation;
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
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'title' => 'Member',
                'password' => bcrypt($this->password), // default password atau form password sendiri
            ]);

            $user->assignRole('User');
            try {
                Mail::to($this->email)->send(new NewRegisterEmail($this->name));

                Log::info('send mail ' . $this->email);
            } catch (\Exception $e) {
                Log::error('Gagal kirim email: ' . $e->getMessage());
            }

            try {

                $data_send_mail = [
                    'name' => $this->first_name . ' ' . $this->last_name,
                    'phone' => $this->phone,
                    'email' => $this->email
                ];

                $listMailPIC = ManageMail::where('type', 'New Member')->pluck('email')->toArray();
                Log::info("START  Sending email to PIC for type: New Member");
                Mail::to($listMailPIC)->send(new NewRegisterPICMail($data_send_mail));


                Log::info('send mail PIC' . json_encode($data_send_mail));
            } catch (\Exception $e) {
                Log::error('Gagal kirim email: ' . $e->getMessage());
            }

            DB::commit();
            Auth::login($user);
            session()->flash('success', 'Registration successful!');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create participant: ' . $e->getMessage());
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
