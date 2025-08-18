<?php

namespace App\Livewire\User;

use App\Models\ClassBooking;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserKuota;
use App\Models\UserMembership;
use App\Models\GroupClass;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class Profile extends Component
{
    use WithFileUploads;

    public $user;
    public $activeMemberships;
    public $membershipDetails;
    public $completedClasses;

    // Edit mode toggles
    public $editName = false;
    public $editPassword = false;
    public $editAvatar = false;

    // Form fields
    public $name;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $avatar;

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
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

    // Toggle edit modes
    public function toggleEditName()
    {
        $this->editName = !$this->editName;
        if (!$this->editName) {
            $this->name = $this->user->name; // Reset if cancelled
        }
    }

    public function toggleEditPassword()
    {
        $this->editPassword = !$this->editPassword;
        if (!$this->editPassword) {
            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        }
    }

    public function toggleEditAvatar()
    {
        $this->editAvatar = !$this->editAvatar;
        if (!$this->editAvatar) {
            $this->avatar = null;
        }
    }

    // Update name
    public function updateName()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $this->user->update([
                'name' => $this->name,
            ]);

            $this->user->refresh();
            $this->editName = false;
            
            session()->flash('success', 'Name updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update name: ' . $e->getMessage());
        }
    }

    // Update password
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Verify current password
        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        try {
            $this->user->update([
                'password' => Hash::make($this->new_password),
            ]);

            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
            $this->editPassword = false;
            
            session()->flash('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    // Update avatar
    public function updateAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:2048', // 2MB max
        ]);

        try {
            // Delete old avatar if exists
            if ($this->user->avatar && Storage::disk('public')->exists($this->user->avatar)) {
                Storage::disk('public')->delete($this->user->avatar);
            }

            // Store new avatar
            $avatarPath = $this->avatar->store('avatars', 'public');

            $this->user->update([
                'avatar' => $avatarPath,
            ]);

            $this->user->refresh();
            $this->avatar = null;
            $this->editAvatar = false;
            
            session()->flash('success', 'Profile photo updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile photo: ' . $e->getMessage());
        }
    }

    // Remove avatar
    public function removeAvatar()
    {
        try {
            // Delete avatar file if exists
            if ($this->user->avatar && Storage::disk('public')->exists($this->user->avatar)) {
                Storage::disk('public')->delete($this->user->avatar);
            }

            $this->user->update([
                'avatar' => null,
            ]);

            $this->user->refresh();
            $this->editAvatar = false;
            
            session()->flash('success', 'Profile photo removed successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove profile photo: ' . $e->getMessage());
        }
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
