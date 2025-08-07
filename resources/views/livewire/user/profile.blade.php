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
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="d-flex flex-column">
                                {{-- <img src="{{ asset('assets/img/testimonials/testimonials-2.jpg') }}"
                                    class="profile-image" alt=""> --}}
                                <span class="fw-bold" style="color: #4B2E2E;">{{ $user->name }}</span>
                                <span class="fw-bold" style="color: #4B2E2E;">{{ $user->email }}</span>
                                {{-- <div>
                                    <a href="{{ route('classes') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <small class="fw-medium" style="color: #111111;">Member
                                    Since</small>
                                <span class="fw-bold"
                                    style="color: #4B2E2E;">{{ $user->created_at->format('M j, Y') }}</span>
                                <small style="color: #4B2E2E;">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <small class="fw-medium" style="color: #111111;">Completed
                                    Classes</small>
                                <h2 class="fw-bold" style="color: #4B2E2E;">
                                    <i class="bi bi-trophy me-1"></i>
                                    {{ $completedClasses }} Classes
                                </h2>
                            </div>
                        </div>

                    </div>
                    <hr>
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
            border-radius: 50px;
            float: left;
            margin: 0 10px 0 0;
        }

        .footer-contact{
            color: #ffffff;
        }
    </style>
</div>
