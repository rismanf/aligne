<?php

namespace App\Livewire\Admin\Question;

use App\Models\Questions;
use App\Models\Questions_option;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class ListQuestion extends Component
{
    use Toast;
    use WithPagination;
    public $selectquestion_id;
    public $showModal = false;
    public $question;
    public $question_options = [];

    public function showDetailModal($id)
    {
        $data_question = Questions::find($id);
        $this->question = $data_question->question;
        $this->question_options = Questions_option::where('question_id', $id)->get();

        $this->selectquestion_id = $id;
        $this->showModal = true;
    }
    public function render()
    {
        $title = 'Question Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"), // route('home') = nama route yang ada di web.php
                'label' => 'Home', // label yang ditampilkan di breadcrumb
                'icon' => 's-home',
            ],
            [
                'link' => route("admin.question.index"), // route('home') = nama route yang ada di web.php
                'label' => 'Question',
            ],
        ];

        $quests = Questions::with('options')->paginate(10);

        $quests->getCollection()->transform(function ($quest, $index) use ($quests) {
            $quest->row_number = ($quests->currentPage() - 1) * $quests->perPage() + $index + 1;
            return $quest;
        });

        $t_headers = [
            ['key' => 'row_number', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'question', 'label' => 'Question'],
            ['key' => 'question_type', 'label' => 'Question Type'],
            ['key' => 'updated_at', 'label' => 'Updated At'],
            ['key' => 'action', 'label' => 'Action', 'class' => 'justify-center w-1'],
        ];

        return view('livewire.admin.question.list-question', [
            't_headers' => $t_headers,
            'quests' => $quests,
        ])
            ->layout('components.layouts.app', [
                'breadcrumbs' => $breadcrumbs,
                'title' => $title,
            ]);
    }
}
