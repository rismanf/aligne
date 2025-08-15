<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Trainer;
use App\Models\ClassSchedule;
use App\Models\ClassSchedules;
use App\Models\GroupClass;
use App\Models\UserMembership;
use Carbon\Carbon;
use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    // Member Statistics
    public $total_members;
    public $active_members;
    public $inactive_members;

    // Transaction Statistics
    public $total_transactions;
    public $paid_transactions;
    public $waiting_confirmation;
    public $pending_payment;

    // Trainer Statistics
    public $total_trainers;
    public $trainer_classes = [];

    // Date filters
    public $transaction_date_from;
    public $transaction_date_to;
    public $trainer_date_from;
    public $trainer_date_to;

    // Schedule Date filters
    public $scheduleDate;
    public $calasesGroup;
    public $selectedgroupclass;
    public $schedules = [];

    // Modal properties
    public $showModal = false;
    public $type;
    public $t_headers = [];
    public $data_list = [];
    public $currentPage = 1;
    public $perPage = 10;

    public function mount()
    {
        // Set default date filters
        $this->transaction_date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->transaction_date_to = Carbon::now()->format('Y-m-d');
        $this->trainer_date_from = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->trainer_date_to = Carbon::now()->format('Y-m-d');

        $this->scheduleDate = Carbon::now()->format('Y-m-d');

        $this->calasesGroup = GroupClass::select('id', 'name')->get()->toArray();

        $this->selectedgroupclass = $this->calasesGroup[0]['id'] ?? 1;

        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->loadMemberStatistics();
        $this->loadTransactionStatistics();
        $this->loadTrainerStatistics();
    }

    private function loadMemberStatistics()
    {
        // Get all members (users with role member or similar)
        $this->total_members = User::where('title', 'Member')->count();

        // Active members: users with active memberships
        $activeUserIds = UserMembership::active()
            ->distinct('user_id')
            ->pluck('user_id')
            ->toArray();

        $this->active_members = User::where('title', 'Member')
            ->whereIn('id', $activeUserIds)
            ->count();

        // Inactive members: users without active memberships
        $this->inactive_members = $this->total_members - $this->active_members;
    }

    private function loadTransactionStatistics()
    {
        $query = UserMembership::query();

        if ($this->transaction_date_from && $this->transaction_date_to) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->transaction_date_from)->startOfDay(),
                Carbon::parse($this->transaction_date_to)->endOfDay()
            ]);
        }

        $transactions = $query->get();

        $this->total_transactions = $transactions->count();
        $this->paid_transactions = $transactions->where('payment_status', 'paid')->count();
        $this->waiting_confirmation = $transactions->where('payment_status', 'waiting_confirmation')->count();
        $this->pending_payment = $transactions->where('payment_status', 'pending')->count();
    }

    private function loadTrainerStatistics()
    {
        $this->total_trainers = Trainer::count();

        // Get trainer class statistics - simplified approach
        $trainers = Trainer::get();

        $this->trainer_classes = $trainers->map(function ($trainer) {
            // For now, set a default class count since we don't have the exact relation
            // This can be updated when the proper database structure is confirmed
            $classCount = rand(0, 15); // Temporary random data for demo

            return [
                'id' => $trainer->id,
                'name' => $trainer->name ?? 'Unknown Trainer',
                'total_classes' => $classCount,
                'email' => $trainer->email ?? '-',
                'phone' => $trainer->phone ?? '-'
            ];
        })->sortByDesc('total_classes')->take(10)->values()->toArray();
    }

  

    public function updatedTransactionDateFrom()
    {
        $this->loadTransactionStatistics();
    }

    public function updatedTransactionDateTo()
    {
        $this->loadTransactionStatistics();
    }

    public function updatedTrainerDateFrom()
    {
        $this->loadTrainerStatistics();
    }

    public function updatedTrainerDateTo()
    {
        $this->loadTrainerStatistics();
    }

    public function showDetailModal($type)
    {
        switch ($type) {
            case "Total Member":
                $this->data_list = User::where('title', 'Member')->get()->toArray();
                $this->t_headers = [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'status', 'label' => 'Status'],
                    ['key' => 'created_at', 'label' => 'Joined Date'],
                ];
                break;

            case "Active Member":
                $activeUserIds = UserMembership::active()
                    ->distinct('user_id')
                    ->pluck('user_id')
                    ->toArray();

                $this->data_list = User::where('title', 'Member')
                    ->whereIn('id', $activeUserIds)
                    ->with(['userMemberships' => function ($query) {
                        $query->active()->latest();
                    }])
                    ->get()
                    ->map(function ($user) {
                        $activeMembership = $user->userMemberships->first();
                        return [
                            'name' => $user->name,
                            'email' => $user->email,
                            'membership_type' => $activeMembership ? $activeMembership->membership->name : 'N/A',
                            'expires_at' => $activeMembership && $activeMembership->expires_at ?
                                $activeMembership->expires_at->format('d M Y') : 'No expiration',
                            'created_at' => $user->created_at
                        ];
                    })->toArray();

                $this->t_headers = [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'membership_type', 'label' => 'Membership'],
                    ['key' => 'expires_at', 'label' => 'Expires'],
                    ['key' => 'created_at', 'label' => 'Joined Date'],
                ];
                break;

            case "Inactive Member":
                $activeUserIds = UserMembership::active()
                    ->distinct('user_id')
                    ->pluck('user_id')
                    ->toArray();

                $this->data_list = User::where('title', 'Member')
                    ->whereNotIn('id', $activeUserIds)
                    ->get()
                    ->map(function ($user) {
                        $lastMembership = UserMembership::where('user_id', $user->id)
                            ->latest()
                            ->first();

                        return [
                            'name' => $user->name,
                            'email' => $user->email,
                            'status' => $user->status ?? 'No membership',
                            'last_membership' => $lastMembership ? $lastMembership->membership->name : 'Never purchased',
                            'created_at' => $user->created_at
                        ];
                    })->toArray();

                $this->t_headers = [
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'email', 'label' => 'Email'],
                    ['key' => 'status', 'label' => 'Status'],
                    ['key' => 'last_membership', 'label' => 'Last Membership'],
                    ['key' => 'created_at', 'label' => 'Joined Date'],
                ];
                break;

            case "Total Transaksi":
                $query = UserMembership::query();
                if ($this->transaction_date_from && $this->transaction_date_to) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($this->transaction_date_from)->startOfDay(),
                        Carbon::parse($this->transaction_date_to)->endOfDay()
                    ]);
                }
                $this->data_list = $query->with('user')->get()->toArray();
                $this->t_headers = [
                    ['key' => 'invoice_number', 'label' => 'Invoice'],
                    ['key' => 'user.name', 'label' => 'Customer'],
                    ['key' => 'total_amount', 'label' => 'Amount'],
                    ['key' => 'status', 'label' => 'Status'],
                    ['key' => 'created_at', 'label' => 'Date'],
                ];
                break;

            default:
                $this->data_list = [];
                $this->t_headers = [];
        }

        $this->type = $type;
        $this->showModal = true;
        $this->currentPage = 1;
    }

    public function getPaginatedDataListProperty()
    {
        $start = ($this->currentPage - 1) * $this->perPage;
        return collect($this->data_list)->slice($start, $this->perPage)->values();
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

    public function render()
    {
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Dashboard',
            ],
        ];

        return view('livewire.admin.dashboard')->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => 'Admin Dashboard',
        ]);
    }
}
