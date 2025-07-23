<?php

namespace App\Livewire\Admin;

use App\Models\Participant;
use App\Models\Questions_option;
use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    public $participants, $total_sponsor, $total_participant, $total_partner;
    public $need_attention, $approved, $rejected;
    public $showModal = false;
    public $type;
    public $t_headers = [];
    public $data_list = [];
    public array $sortBy = ['column' => 'full_name', 'direction' => 'asc'];

    public $currentPage = 1;
    public $perPage = 5;
    public $myChart;
    public $myChart2;
    public function showDetailModal($type)
    {

        switch ($type) {
            case "All Participant":
                $data_list = Participant::query();
                break;
            case "Participant General Admission":
                $data_list = Participant::where('user_type', 'General Admission');
                break;
            case "Participant Sponsor":
                $data_list = Participant::where('user_type', 'Sponsor');
                break;
            case "Participant Partner":
                $data_list = Participant::where('user_type', 'Partner');
                break;
            case "Participant Need Attention":
                $data_list = Participant::where('status', 'Waiting');
                break;
            case "Participant Approved":
                $data_list = Participant::where('status', 'Approved');
                break;
            case "Participant Rejected":
                $data_list = Participant::where('status', 'Rejected');
                break;
            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }
        $this->t_headers = [
            ['key' => 'user_type', 'label' => 'Type'],
            ['key' => 'full_name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'company', 'label' => 'Company'],
            ['key' => 'job_title', 'label' => 'Job Title'],
            ['key' => 'status', 'label' => 'Status'],
        ];

        $this->data_list = $data_list->get();

        $this->type = $type;
        $this->showModal = true;
        $this->currentPage = 1;
    }

    public function getPaginatedDataListProperty()
    {
        $start = ($this->currentPage - 1) * $this->perPage;

        return collect($this->data_list)
            ->sortBy($this->sortBy['column'])
            ->slice($start, $this->perPage)
            ->values(); // <- ini penting
    }

    public function getTotalPagesProperty()
    {
        return ceil(collect($this->data_list)->count() / $this->perPage);
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->totalPages) {
            $this->currentPage++;
        }
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
        }
    }

    public function mount()
    {
        $data = Participant::all();
        $this->participants = $data->count();
        $this->total_sponsor = $data->where('user_type', 'Sponsor')->count();
        $this->total_participant = $data->where('user_type', 'General Admission')->count();
        $this->total_partner = $data->where('user_type', 'Partner')->count();

        $this->need_attention = $data->where('status', 'Waiting')->count();
        $this->approved = $data->where('status', 'Approved')->count();
        $this->rejected = $data->where('status', 'Rejected')->count();

        $data_chart = Participant::with(['topicAnswer', 'golfAnswer'])
            ->where('user_type', 'General Admission')
            ->where('status', 'Approved')
            ->get();

        // Ambil jawaban dari relasi topicAnswer
        $grouped = $data_chart->groupBy(function ($item) {
            return $item->topicAnswer->answer ?? 'No Answer';
        });

        // Hitung jumlah per jawaban
        $chart = $grouped->map(fn($items) => $items->count())->toArray();

        // Ambil semua opsi dari question_id = 5 (topik)
        $options_topic = Questions_option::where('question_id', 5)->pluck('option')->toArray();

        // Susun data sesuai urutan options_topic
        $data_counts = [];
        foreach ($options_topic as $option) {
            $data_counts[] = $chart[$option] ?? 0; // pakai 0 kalau tidak ada data
        }

        if ($data_chart->count() > 0) {
            $this->myChart = [
                'type' => 'pie',
                'data' => [
                    'labels' => $options_topic,     // label sesuai dari database
                    'datasets' => [
                        [
                            'label' => '# of Votes',
                            'data' => $data_counts, // jumlah per label
                        ]
                    ]
                ]
            ];
        } else {
            $this->myChart = [
                'type' => 'pie',
                'data' => [
                    'labels' => $options_topic,
                    'datasets' => [
                        [
                            'label' => '# of Votes',
                            'data' => [0, 0, 0, 0, 0, 0, 0],
                        ]
                    ]
                ]
            ];
        }


        // Ambil jawaban dari relasi topicAnswer
        $grouped_golf = $data_chart->groupBy(function ($item) {
            return $item->golfAnswer->answer ?? 'No Answer';
        });

        // Hitung jumlah per jawaban
        $chart_golf = $grouped_golf->map(fn($items) => $items->count())->toArray();

        // Ambil semua opsi dari question_id = 5 (topik)
        $options_golf = Questions_option::where('question_id', 6)->pluck('option')->toArray();

        // Susun data sesuai urutan options_topic
        $data_counts_golf = [];
        foreach ($options_golf as $option) {
            $data_counts_golf[] = $chart_golf[$option] ?? 0; // pakai 0 kalau tidak ada data
        }

        if ($data_chart->count() > 0) {
            $this->myChart2 = [
                'type' => 'bar',
                'data' => [
                    'labels' => $options_golf,     // label sesuai dari database
                    'datasets' => [
                        [
                            'label' => '# of Votes',
                            'data' => $data_counts_golf, // jumlah per label
                        ]
                    ]
                ]
            ];
        } else {
            $this->myChart2 = [
                'type' => 'bar',
                'data' => [
                    'labels' => $options_golf,
                    'datasets' => [
                        [
                            'label' => '# of Votes',
                            'data' => [0, 0],
                        ]
                    ]
                ]
            ];
        }
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
