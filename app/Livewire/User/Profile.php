<?php

namespace App\Livewire\User;

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

        // Prepare detailed membership information with quotas
        $this->membershipDetails = $this->activeMemberships->map(function ($membership) {
            $details = [
                'membership' => $membership,
                'package_name' => $membership->membership->name,
                'category' => $membership->membership->category ?? 'general',
                'expires_at' => $membership->expires_at,
                'remaining_days' => $membership->getRemainingDays(),
                'is_combination' => $membership->isCombinationPackage(),
                'quotas' => []
            ];

            if ($membership->isCombinationPackage()) {
                // For combination packages (like Elevate Pack)
                $remainingQuota = $membership->getRemainingCombinationQuota();
                $details['quotas'][] = [
                    'class_name' => 'Reformer / Chair Classes',
                    'remaining_quota' => $remainingQuota,
                    'class_types' => $membership->membership->groupClasses->pluck('name')->toArray()
                ];
            } else {
                // For specific class packages
                foreach ($membership->membership->groupClasses as $groupClass) {
                    $remainingQuota = $membership->getRemainingQuotaForClass($groupClass->id);
                    if ($remainingQuota > 0) {
                        $details['quotas'][] = [
                            'class_name' => $groupClass->name,
                            'class_category' => ucfirst($groupClass->category),
                            'remaining_quota' => $remainingQuota
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
