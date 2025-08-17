<?php

namespace App\Livewire\User;

use App\Models\ClassBooking;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserKuota;
use App\Models\UserMembership;
use App\Models\GroupClass;
use Livewire\Component;

class Profile extends Component
{
    public $user;
    public $activeMemberships;
    public $membershipDetails;
    public $completedClasses;

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadActiveMemberships();
        
    }

    private function loadActiveMemberships()
    {
        // Get active memberships with detailed information
        $this->activeMemberships = UserMembership::with(['membership.groupClasses'])
            ->where('user_id', $this->user->id)
            ->active()
            ->get();

        $this->completedClasses = ClassBooking::where('user_id', $this->user->id)
            ->where('visit_status', 'visited')
            ->count();

        // Prepare detailed membership information with quotas
        $this->membershipDetails = $this->activeMemberships->map(function ($membership) {
            $details = [
                'membership' => $membership,
                'package_name' => $membership->membership->name,
                'category' => $membership->membership->category ?? 'general',
                'expires_at' => $membership->expires_at,
                'remaining_days' => $membership->getRemainingDays(),
                'is_flexible' => $membership->isFlexibleQuota(),
                'quota_strategy' => $membership->membership->quota_strategy,               
                'quotas' => []
            ];

            if ($membership->isFlexibleQuota()) {
                // For flexible quota packages
                $remainingQuota = $membership->getRemainingFlexibleQuota();
                $classTypes = $membership->membership->groupClasses->pluck('name')->toArray();
                $details['quotas'][] = [
                    'class_name' => implode(' / ', $classTypes) . ' Classes',
                    'remaining_quota' => $remainingQuota,
                    'class_types' => $classTypes,
                    'quota_type' => 'flexible'
                ];
            } else {
                // For fixed quota packages
                foreach ($membership->membership->groupClasses as $groupClass) {
                    $remainingQuota = $membership->getRemainingQuotaForClass($groupClass->id);
                    if ($remainingQuota > 0) {
                        $details['quotas'][] = [
                            'class_name' => $groupClass->name,
                            'class_category' => ucfirst($groupClass->category),
                            'remaining_quota' => $remainingQuota,
                            'quota_type' => 'fixed'
                        ];
                    }
                }
            }
            return $details;
        });
    }

    public function render()
    {
        $menu = Menu::where('name', 'About Us')->first();

        return view('livewire.user.profile')->layout('components.layouts.website', [
            'title' => $menu->title ?? 'My Profile',
            'description' => $menu->description ?? 'User Profile',
            'keywords' => $menu->keywords ?? 'profile, membership',
            'image' => asset('images/logo.png'),
            'url' => url()->current(),
        ]);
    }
}
