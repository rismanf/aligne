<div>
    <!-- Class Booking Section -->
    <section id="class-booking" class="pricing section">
        <div class="container">
            <div class="row gy-4 justify-content-center text-center">
                @if(session()->has('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                @if(session()->has('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <div class="col-lg-8">
                    <div class="class-booking-card">
                        <!-- Class Header -->
                        <div class="class-header">
                            <div class="class-category-badge badge-{{ $class->category ?? 'default' }}">
                                {{ ucfirst($class->category ?? 'Class') }}
                            </div>
                            <h2 class="class-title">{{ $schedule->name }}</h2>
                            <h3 class="class-name">{{ $class->name }}</h3>
                            @if($class->level)
                                <span class="level-badge level-{{ $class->level }}">
                                    {{ ucfirst($class->level) }} Level
                                </span>
                            @endif
                        </div>

                        <!-- Class Details -->
                        <div class="class-details">
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">Date & Time</span>
                                        <span class="detail-value">{{ $schedule->start_time->format('M d, Y - H:i') }}</span>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">Duration</span>
                                        <span class="detail-value">{{ $schedule->duration }} minutes</span>
                                    </div>
                                </div>

                                @if($schedule->trainer)
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Trainer</span>
                                            <span class="detail-value">{{ $schedule->trainer->name }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">Capacity</span>
                                        <span class="detail-value">{{ $schedule->capacity_book }}/{{ $schedule->capacity }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($class->description)
                                <div class="class-description">
                                    <h4>About This Class</h4>
                                    <p>{{ $class->description }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Booking Status -->
                        <div class="booking-status">
                            @guest
                                <div class="status-message status-info">
                                    <h4>Login Required</h4>
                                    <p>Please log in to book this class</p>
                                </div>
                                <div class="booking-actions">
                                    <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
                                    <a href="{{ route('register') }}" class="btn btn-outline">Sign Up</a>
                                </div>
                            @else
                                @if($existingBooking)
                                    <div class="status-message status-success">
                                        <h4>Already Booked</h4>
                                        <p>You have already booked this class</p>
                                    </div>
                                    <div class="booking-actions">
                                        <a href="{{ route('user.booking') }}" class="btn btn-primary">View My Bookings</a>
                                        <a href="{{ route('classes') }}" class="btn btn-outline">Browse Other Classes</a>
                                    </div>
                                @elseif(!$hasActiveMembership)
                                    <div class="status-message status-warning">
                                        <h4>Membership Required</h4>
                                        <p>You need an active membership to book this class</p>
                                    </div>
                                    <div class="booking-actions">
                                        <a href="{{ route('membership') }}" class="btn btn-primary">Buy Membership</a>
                                        <a href="{{ route('classes') }}" class="btn btn-outline">Back to Classes</a>
                                    </div>
                                @elseif($userQuota <= 0)
                                    <div class="status-message status-warning">
                                        <h4>No Quota Available</h4>
                                        <p>You don't have enough quota for this class type</p>
                                    </div>
                                    <div class="booking-actions">
                                        <a href="{{ route('membership') }}" class="btn btn-primary">Buy More Classes</a>
                                        <a href="{{ route('classes') }}" class="btn btn-outline">Browse Other Classes</a>
                                    </div>
                                @elseif($schedule->capacity_book >= $schedule->capacity)
                                    <div class="status-message status-error">
                                        <h4>Class Full</h4>
                                        <p>This class has reached maximum capacity</p>
                                    </div>
                                    <div class="booking-actions">
                                        <a href="{{ route('classes') }}" class="btn btn-primary">Browse Other Classes</a>
                                    </div>
                                @elseif($schedule->start_time <= now())
                                    <div class="status-message status-error">
                                        <h4>Class Started</h4>
                                        <p>This class has already started or ended</p>
                                    </div>
                                    <div class="booking-actions">
                                        <a href="{{ route('classes') }}" class="btn btn-primary">Browse Other Classes</a>
                                    </div>
                                @elseif(!$schedule->is_active)
                                    <div class="status-message status-error">
                                        <h4>Class Unavailable</h4>
                                        <p>This class is currently not available for booking</p>
                                    </div>
                                    <div class="booking-actions">
                                        <a href="{{ route('classes') }}" class="btn btn-primary">Browse Other Classes</a>
                                    </div>
                                @else
                                    <div class="status-message status-success">
                                        <h4>Ready to Book</h4>
                                        <p>You have {{ $userQuota }} quota left for this class type</p>
                                    </div>
                                    <div class="booking-actions">
                                        <button wire:click="bookClass" class="btn btn-primary btn-large">
                                            Confirm Booking
                                        </button>
                                        <a href="{{ route('classes') }}" class="btn btn-outline">Back to Classes</a>
                                    </div>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Styles -->
    <style>
        .class-booking-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: left;
        }

        .class-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .class-category-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .badge-reformer { background-color: #28a745; color: white; }
        .badge-chair { background-color: #ffc107; color: #000; }
        .badge-functional { background-color: #dc3545; color: white; }
        .badge-default { background-color: #6c757d; color: white; }

        .class-title {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }

        .class-name {
            font-size: 1.5rem;
            color: #666;
            margin: 5px 0;
        }

        .level-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .level-beginner { background-color: #28a745; color: white; }
        .level-intermediate { background-color: #ffc107; color: #000; }
        .level-advanced { background-color: #dc3545; color: white; }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .detail-icon {
            margin-right: 15px;
            color: #007bff;
        }

        .detail-content {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }

        .detail-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .class-description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .class-description h4 {
            color: #333;
            margin-bottom: 10px;
        }

        .booking-status {
            text-align: center;
            padding: 20px 0;
        }

        .status-message {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .status-message h4 {
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .status-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .status-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .status-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .status-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .booking-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            text-decoration: none;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline:hover {
            background: #007bff;
            color: white;
        }

        .btn-large {
            padding: 15px 40px;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .class-booking-card {
                padding: 20px;
            }
            
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .booking-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</div>
