<div>
    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-3">
                    <div class="services-list">
                        <a href="{{ route('user.profile') }}" class="active">Profile</a>
                        <a href="{{ route('user.booking') }}">My Booking</a>
                        <a href="{{ route('user.order') }}">My Order</a>
                        <a href="{{ route('logout') }}">Logout</a>
                    </div>
                </div>

                <div class="col-lg-9">
                    <h3>My Profile</h3>
                    
                    <!-- Success/Error Messages -->
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row g-3">
                        <!-- Profile Photo Section -->
                        <div class="col-md-12">
                            <div class="profile-photo-section">
                                <div class="d-flex align-items-center">
                                    <div class="profile-photo-container">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" class="profile-image" alt="Profile Photo">
                                        @else
                                            <div class="profile-placeholder">
                                                <i class="bi bi-person-circle"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="profile-info ms-3">
                                        <!-- Name Section -->
                                        <div class="name-section mb-2">
                                            @if(!$editName)
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold profile-name">{{ $user->name }}</span>
                                                    <button wire:click="toggleEditName" class="btn btn-link btn-sm ms-2 p-0">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="edit-name-form">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name">
                                                        <button wire:click="updateName" class="btn btn-success btn-sm">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                        <button wire:click="toggleEditName" class="btn btn-secondary btn-sm">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </div>
                                                    @error('name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Email (non-editable) -->
                                        <div class="email-section mb-2">
                                            <span class="text-muted">{{ $user->email }}</span>
                                        </div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="action-buttons">
                                            <button wire:click="toggleEditAvatar" class="btn btn-outline-primary btn-sm me-2">
                                                <i class="bi bi-camera"></i> Change Photo
                                            </button>
                                            <button wire:click="toggleEditPassword" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-key"></i> Change Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar Upload Form -->
                        @if($editAvatar)
                            <div class="col-md-12">
                                <div class="avatar-upload-form">
                                    <h5>Update Profile Photo</h5>
                                    <div class="mb-3">
                                        <input type="file" wire:model="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                        @error('avatar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    @if($avatar)
                                        <div class="mb-3">
                                            <img src="{{ $avatar->temporaryUrl() }}" class="preview-image" alt="Preview">
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex gap-2">
                                        <button wire:click="updateAvatar" class="btn btn-primary btn-sm" @if(!$avatar) disabled @endif>
                                            <i class="bi bi-upload"></i> Upload Photo
                                        </button>
                                        @if($user->avatar)
                                            <button wire:click="removeAvatar" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Remove Current Photo
                                            </button>
                                        @endif
                                        <button wire:click="toggleEditAvatar" class="btn btn-secondary btn-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Password Change Form -->
                        @if($editPassword)
                            <div class="col-md-12">
                                <div class="password-change-form">
                                    <h5>Change Password</h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" wire:model="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" wire:model="new_password" class="form-control @error('new_password') is-invalid @enderror">
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" wire:model="new_password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button wire:click="updatePassword" class="btn btn-primary btn-sm">
                                            <i class="bi bi-key"></i> Update Password
                                        </button>
                                        <button wire:click="toggleEditPassword" class="btn btn-secondary btn-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Member Statistics Section -->
                        <div class="col-md-12">
                            <div class="member-stats-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="stat-card">
                                            <div class="stat-icon">
                                                <i class="bi bi-calendar-check"></i>
                                            </div>
                                            <div class="stat-content">
                                                <small class="stat-label">Member Since</small>
                                                <h4 class="stat-value">{{ $user->created_at->format('M j, Y') }}</h4>
                                                <small class="stat-subtitle">{{ $user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="stat-card">
                                            <div class="stat-icon">
                                                <i class="bi bi-trophy"></i>
                                            </div>
                                            <div class="stat-content">
                                                <small class="stat-label">Completed Classes</small>
                                                <h4 class="stat-value">{{ $completedClasses }} Classes</h4>
                                                <small class="stat-subtitle">Total achievements</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <h3>My Membership</h3>

                    @if ($membershipDetails && $membershipDetails->count() > 0)
                        <div class="row">
                            @foreach ($membershipDetails as $detail)
                                <div class="col-md-6 mb-4">
                                    <div class="membership-card">
                                        <div class="membership-header">
                                            <h5 class="membership-title">{{ $detail['package_name'] }}</h5>
                                            <span class="membership-category badge badge-{{ $detail['category'] }}">
                                                {{ ucfirst($detail['category']) }}
                                            </span>
                                        </div>

                                        <div class="membership-body">
                                            <div class="expiry-info mb-3">
                                                @if ($detail['expires_at'])
                                                    <div class="d-flex justify-content-between">
                                                        <span>Expires:</span>
                                                        <span
                                                            class="fw-bold">{{ $detail['expires_at']->format('d M Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span>Days remaining:</span>
                                                        <span
                                                            class="fw-bold text-{{ $detail['remaining_days'] > 7 ? 'success' : ($detail['remaining_days'] > 3 ? 'warning' : 'danger') }}">
                                                            {{ round($detail['remaining_days']) }} days
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="text-success">
                                                        <i class="bi bi-infinity"></i> No expiration
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="quota-info">
                                                <h6 class="mb-2">Remaining Classes:</h6>
                                                @if (count($detail['quotas']) > 0)
                                                    @foreach ($detail['quotas'] as $quota)
                                                        <div class="quota-item">
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span
                                                                        class="class-name">{{ $quota['class_name'] }}</span>
                                                                    @if (isset($quota['class_category']))
                                                                        <small
                                                                            class="class-category">({{ $quota['class_category'] }})</small>
                                                                    @endif
                                                                </div>
                                                                <span class="quota-badge">
                                                                    {{ $quota['remaining_quota'] }}x
                                                                </span>
                                                            </div>
                                                            @if (isset($quota['class_types']) && count($quota['class_types']) > 0)
                                                                <small class="text-muted">
                                                                    Available for:
                                                                    {{ implode(', ', $quota['class_types']) }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-muted">No remaining classes</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="membership-footer">
                                            <a href="{{ route('classes') }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-calendar-plus"></i> Book Classes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-membership-card">
                            <div class="text-center py-4">
                                <i class="bi bi-card-list display-4 text-muted mb-3"></i>
                                <h5>No Active Memberships</h5>
                                <p class="text-muted mb-3">You don't have any active membership packages at the moment.
                                </p>
                                <a href="{{ route('membership') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Browse Membership Packages
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    </section><!-- /Service Details Section -->

    <!-- Additional Styles -->
    <style>
        .services-list a {
            display: block;
            padding: 12px 18px;
            margin-bottom: 8px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 1px solid #e9ecef;
        }

        .services-list a:hover,
        .services-list a.active {
            background: #4b2e2e !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(75, 46, 46, 0.3);
            border-color: #4b2e2e;
        }

        .services-list {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        h3 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
        }

        ul li {
            margin-bottom: 10px;
            font-size: 16px;
        }

        ul li i {
            color: #007bff;
            margin-right: 8px;
        }

        hr {
            border-color: #e9ecef;
            margin: 30px 0;
        }

        p {
            line-height: 1.6;
            /* color: #666; */
        }

        /* Membership Cards Styling */
        .membership-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .membership-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .membership-header {
            background: linear-gradient(135deg, #4b2e2e 0%, #6d4545 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .membership-title {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .membership-category {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
        }

        .membership-body {
            padding: 20px;
        }

        .expiry-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #4b2e2e;
        }

        .quota-info h6 {
            color: #4b2e2e;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .quota-item {
            background: #f8f9fa;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
            border-left: 3px solid #4b2e2e;
        }

        .quota-item:last-child {
            margin-bottom: 0;
        }

        .class-name {
            font-weight: 500;
            color: #333;
        }

        .class-category {
            color: #666;
            font-style: italic;
        }

        .quota-badge {
            background: #4b2e2e;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .membership-footer {
            padding: 15px 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .no-membership-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            margin-top: 20px;
        }

        .badge-signature {
            background-color: #4b2e2e;
        }

        .badge-functional {
            background-color: #28a745;
        }

        .badge-vip {
            background-color: #ffc107;
            color: #000;
        }

        .badge-special {
            background-color: #17a2b8;
        }

        .badge-general {
            background-color: #6c757d;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .fw-bold {
            font-weight: bold !important;
        }

        .d-flex {
            display: flex !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .py-4 {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .btn-primary {
            background-color: #4b2e2e;
            border-color: #4b2e2e;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3a2323;
            border-color: #3a2323;
            color: white;
            text-decoration: none;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .display-4 {
            font-size: 2.5rem;
        }

        .profile-image {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4b2e2e;
        }

        .profile-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #e9ecef;
        }

        .profile-placeholder i {
            font-size: 40px;
            color: #6c757d;
        }

        .profile-photo-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .member-stats-section {
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            height: 100%;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4b2e2e 0%, #6d4545 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .stat-icon i {
            font-size: 24px;
            color: white;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            color: #666;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            display: block;
        }

        .stat-value {
            color: #4B2E2E;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .stat-subtitle {
            color: #4B2E2E;
            font-size: 11px;
            opacity: 0.8;
            margin-top: 2px;
            display: block;
        }

        .profile-name {
            font-size: 1.5rem;
            color: #4B2E2E;
        }

        .edit-name-form {
            max-width: 300px;
        }

        .avatar-upload-form,
        .password-change-form {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .avatar-upload-form h5,
        .password-change-form h5 {
            color: #4B2E2E;
            margin-bottom: 15px;
        }

        .preview-image {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }

        .btn-link {
            color: #4B2E2E;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #3a2323;
        }

        .btn-outline-primary {
            border-color: #4B2E2E;
            color: #4B2E2E;
        }

        .btn-outline-primary:hover {
            background-color: #4B2E2E;
            border-color: #4B2E2E;
            color: white;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #4B2E2E;
            box-shadow: 0 0 0 0.2rem rgba(75, 46, 46, 0.25);
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .footer-contact{
            color: #ffffff;
        }
    </style>
</div>
