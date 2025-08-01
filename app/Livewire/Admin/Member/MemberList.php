<?php

namespace App\Livewire\Admin\Member;

use App\Models\User;
use App\Models\UserMembership;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class MemberList extends Component
{
    use Toast, WithPagination;

    public $search = '';
    public $status_filter = 'all'; // all, active, inactive
    public $membership_filter = 'all'; // all, paid, unpaid, expired
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Modal properties
    public $showDetailModal = false;
    public $selectedMember = null;
    public $memberMemberships = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'status_filter' => ['except' => 'all'],
        'membership_filter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedMembershipFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function showMemberDetail($memberId)
    {
        $this->selectedMember = User::with(['userMemberships.membership'])
            ->find($memberId);

        if ($this->selectedMember) {
            $this->memberMemberships = $this->selectedMember->userMemberships()
                ->with('membership')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($membership) {
                    return [
                        'id' => $membership->id,
                        'membership_name' => $membership->membership->name ?? 'Unknown',
                        'price' => $membership->total_price ?? $membership->price,
                        'payment_status' => $membership->payment_status,
                        'status' => $membership->status,
                        'starts_at' => $membership->starts_at,
                        'expires_at' => $membership->expires_at,
                        'is_active' => $membership->isActive(),
                        'remaining_days' => $membership->getRemainingDays(),
                        'created_at' => $membership->created_at,
                    ];
                })->toArray();

            $this->showDetailModal = true;
        }
    }

    public function render()
    {
        $query = User::where('title', 'Member');

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter (active/inactive based on membership)
        if ($this->status_filter === 'active') {
            $activeUserIds = UserMembership::active()
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('id', $activeUserIds);
        } elseif ($this->status_filter === 'inactive') {
            $activeUserIds = UserMembership::active()
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
            $query->whereNotIn('id', $activeUserIds);
        }

        // Membership filter
        if ($this->membership_filter === 'paid') {
            $paidUserIds = UserMembership::where('payment_status', 'paid')
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('id', $paidUserIds);
        } elseif ($this->membership_filter === 'unpaid') {
            $unpaidUserIds = UserMembership::where('payment_status', 'unpaid')
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('id', $unpaidUserIds);
        } elseif ($this->membership_filter === 'expired') {
            $expiredUserIds = UserMembership::where('expires_at', '<', now())
                ->where('expires_at', '!=', null)
                ->distinct('user_id')
                ->pluck('user_id')
                ->toArray();
            $query->whereIn('id', $expiredUserIds);
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $members = $query->paginate(15);

        // Add membership status to each member
        $members->getCollection()->transform(function ($member) {
            // Get active membership
            $activeMembership = UserMembership::where('user_id', $member->id)
                ->active()
                ->with('membership')
                ->first();

            // Get latest membership if no active one
            $latestMembership = UserMembership::where('user_id', $member->id)
                ->with('membership')
                ->latest()
                ->first();

            $member->active_membership = $activeMembership;
            $member->latest_membership = $latestMembership;
            $member->is_active = $activeMembership ? true : false;
            $member->membership_count = UserMembership::where('user_id', $member->id)->count();

            return $member;
        });

        $title = 'Member Management';
        $breadcrumbs = [
            [
                'link' => route("admin.home"),
                'label' => 'Home',
                'icon' => 's-home',
            ],
            [
                'label' => 'Member Management',
            ],
        ];

        return view('livewire.admin.member.member-list', [
            'members' => $members,
        ])->layout('components.layouts.app', [
            'breadcrumbs' => $breadcrumbs,
            'title' => $title,
        ]);
    }
}
