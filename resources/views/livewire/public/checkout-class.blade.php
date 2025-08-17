<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

        <div class="container">

            <div class="row gy-4 justify-content-center text-center ">
                @if (session()->has('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <script>
                    document.addEventListener('livewire:init', () => {
                        Livewire.on('redirectAfterSuccess', () => {
                            setTimeout(() => {
                                window.location.href = "{{ route('user.my-bookings') }}";
                            }, 2000);
                        });
                    });
                </script>
                <div class="col-lg-6">
                    <div class="pricing-item">
                        <div class="border p-3 mb-3">
                            {{-- <h5>Class summary</h5> --}}
                            <h5>{{ $schedule->classes->groupClass->name }}</h5>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Class Name</span>
                                <span>{{ $schedule->classes->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Level</span>
                                <span>{{ $schedule->classes->level_class }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Date</span>
                                <span>{{ $schedule->start_time->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Time</span>
                                <span>{{ $schedule->start_time->format('h:i A') }} -
                                    {{ $schedule->end_time->format('h:i A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Trainer</span>
                                <span>{{ $schedule->trainer->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Capacity</span>
                                <span>{{ $schedule->capacity_book }}/{{ $schedule->capacity }}</span>
                            </div>
                            <div class="text-muted small mt-2">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                            </div>
                        </div>
                        <div class="p-3">
                            @guest
                                <h3>Sign Up</h3>
                                <p>To book this class, please log in to your account or sign up.</p>
                                <a href="{{ route('login') }}" class="cta-btn">Log In</a>
                                <a href="{{ route('register') }}" class="cta-btn">Sign Up</a>
                            @else
                                @if ($existing_booking)
                                    <p>You have already booked this class</p>
                                    <p><strong>Booking Code:</strong> {{ $existing_booking->booking_code }}</p>
                                    <a href="{{ route('user.my-bookings') }}" class="cta-btn">View My Bookings</a>
                                    <a href="{{ route('detail-class', ['id' => $schedule->classes->group_class_id, 'date' => $dateClass]) }}"
                                        class="cta-btn">Back</a>
                                @else
                                    @if ($cek_quota == 0)
                                        <p>You do not have quota for this class type</p>
                                        <p>Please buy a membership package that includes this class type</p>
                                        <a href="{{ route('membership') }}" class="cta-btn">Buy Membership</a>
                                        <a href="{{ route('detail-class', ['id' => $schedule->classes->group_class_id, 'date' => $dateClass]) }}"
                                            class="cta-btn">Back to Classes</a>
                                    @else
                                        <div class="alert alert-info">
                                            You have <strong>{{ $cek_quota }}</strong> remaining credits for this class.
                                        </div>


                                        @if ($is_reformer_class)
                                            <!-- Reformer Position Selection -->
                                            <div class="reformer-selection mb-4">
                                                <!-- Reformer Layout Visual -->
                                                <div class="reformer-layout mb-3">
                                                    <style>
                                                        .reformer-layout {
                                                            background: linear-gradient(135deg, #F6F4EB 0%, #D4BDA5 100%);
                                                            border-radius: 10px;
                                                            padding: 15px;
                                                            position: relative;
                                                        }

                                                        .layout-title {
                                                            text-align: center;
                                                            color: #4B2E2E;
                                                            font-weight: bold;
                                                            margin-bottom: 15px;
                                                            font-size: 16px;
                                                        }

                                                        .reformer-image-container {
                                                            position: relative;
                                                            width: 100%;
                                                            max-width: 500px;
                                                            margin: 0 auto;
                                                        }

                                                        .reformer-image {
                                                            width: 100%;
                                                            height: auto;
                                                            border-radius: 8px;
                                                            box-shadow: 0 4px 12px rgba(75, 46, 46, 0.15);
                                                        }

                                                        .position-overlay {
                                                            position: absolute;
                                                            width: 40px;
                                                            height: 40px;
                                                            border-radius: 50%;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            font-weight: bold;
                                                            font-size: 14px;
                                                            cursor: pointer;
                                                            transition: all 0.3s ease;
                                                            border: 2px solid #CBB4A0;
                                                            background: rgba(255, 255, 255, 0.9);
                                                            color: #ffffff;
                                                        }

                                                        .position-overlay:hover {
                                                            transform: scale(1.1);
                                                            box-shadow: 0 4px 12px rgba(75, 46, 46, 0.3);
                                                        }

                                                        .position-overlay.available {
                                                            border-color: green;
                                                            background: rgba(0, 225, 45, 0.2);
                                                        }

                                                        .position-overlay.selected {
                                                            border-color: #4B2E2E;
                                                            background: #4B2E2E;
                                                            color: white;
                                                            transform: scale(1.2);
                                                        }

                                                        .position-overlay.occupied {
                                                            background: rgba(203, 180, 160, 0.8);
                                                            color: #666;
                                                            cursor: not-allowed;
                                                            opacity: 0.6;
                                                        }

                                                        /* Position coordinates based on image layout */
                                                        .pos-1 {
                                                            top: 25%;
                                                            left: 17%;
                                                        }

                                                        .pos-2 {
                                                            top: 47%;
                                                            left: 17%;
                                                        }

                                                        .pos-3 {
                                                            top: 65%;
                                                            left: 17%;
                                                        }

                                                        .pos-4 {
                                                            top: 84%;
                                                            left: 17%;
                                                        }

                                                        .pos-5 {
                                                            top: 18%;
                                                            right: 32%;
                                                        }

                                                        .pos-6 {
                                                            top: 37%;
                                                            right: 32%;
                                                        }

                                                        .pos-7 {
                                                            top: 56%;
                                                            right: 32%;
                                                        }

                                                        .pos-8 {
                                                            top: 75%;
                                                            right: 32%;
                                                        }

                                                        @media (max-width: 768px) {
                                                            .position-overlay {
                                                                width: 35px;
                                                                height: 35px;
                                                                font-size: 12px;
                                                            }

                                                            .layout-title {
                                                                font-size: 14px;
                                                            }
                                                        }
                                                    </style>

                                                    <div class="layout-title">Select Your Reformer Position</div>

                                                    <div class="reformer-image-container">
                                                        <img src="{{ asset('images/reformer_class.png') }}"
                                                            alt="Reformer Studio Layout" class="reformer-image">

                                                        <!-- Position overlays -->
                                                        @foreach (range(1, 8) as $position)
                                                            @php
                                                                $positionData = $available_positions[$position - 1];
                                                                $isAvailable = $positionData['is_available'];
                                                                $isSelected = $selected_position == $position;
                                                            @endphp
                                                            <div class="position-overlay pos-{{ $position }} {{ $isAvailable ? 'available' : 'occupied' }} {{ $isSelected ? 'selected' : '' }}"
                                                                wire:click="{{ $isAvailable ? 'selectPosition(' . $position . ')' : '' }}"
                                                                title="{{ $isAvailable ? 'Available' : 'Occupied' }} - Position {{ $position }}">
                                                                {{ $position }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                @if ($selected_position)
                                                    <div class="alert alert-success">
                                                        <strong>Selected Position:</strong> Reformer
                                                        {{ $selected_position }}
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        Please select a Reformer position to continue with your booking.
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($isChairClass)
                                            <!-- Reformer Position Selection -->
                                            <div class="reformer-selection mb-4">
                                                <!-- Reformer Layout Visual -->
                                                <div class="reformer-layout mb-3">
                                                    <style>
                                                        .reformer-layout {
                                                            background: linear-gradient(135deg, #F6F4EB 0%, #D4BDA5 100%);
                                                            border-radius: 10px;
                                                            padding: 15px;
                                                            position: relative;
                                                        }

                                                        .layout-title {
                                                            text-align: center;
                                                            color: #4B2E2E;
                                                            font-weight: bold;
                                                            margin-bottom: 15px;
                                                            font-size: 16px;
                                                        }

                                                        .reformer-image-container {
                                                            position: relative;
                                                            width: 100%;
                                                            max-width: 500px;
                                                            margin: 0 auto;
                                                        }

                                                        .reformer-image {
                                                            width: 100%;
                                                            height: auto;
                                                            border-radius: 8px;
                                                            box-shadow: 0 4px 12px rgba(75, 46, 46, 0.15);
                                                        }

                                                        .position-overlay {
                                                            position: absolute;
                                                            width: 40px;
                                                            height: 40px;
                                                            border-radius: 50%;
                                                            display: flex;
                                                            align-items: center;
                                                            justify-content: center;
                                                            font-weight: bold;
                                                            font-size: 14px;
                                                            cursor: pointer;
                                                            transition: all 0.3s ease;
                                                            border: 2px solid #CBB4A0;
                                                            background: rgba(255, 255, 255, 0.9);
                                                            color: #4B2E2E;
                                                        }

                                                        .position-overlay:hover {
                                                            transform: scale(1.1);
                                                            box-shadow: 0 4px 12px rgba(75, 46, 46, 0.3);
                                                        }

                                                        .position-overlay.available {
                                                            border-color: green;
                                                            background: rgba(98, 176, 114, 0.2);
                                                        }

                                                        .position-overlay.selected {
                                                            border-color: #4B2E2E;
                                                            background: #4B2E2E;
                                                            color: white;
                                                            transform: scale(1.2);
                                                        }

                                                        .position-overlay.occupied {
                                                            background: rgba(203, 180, 160, 0.8);
                                                            color: #666;
                                                            cursor: not-allowed;
                                                            opacity: 0.6;
                                                        }

                                                        /* Position coordinates based on image layout */
                                                        .pos-1 {
                                                            top: 15%;
                                                            left: 12%;
                                                        }

                                                        .pos-2 {
                                                            top: 15%;
                                                            left: 35%;
                                                        }

                                                        .pos-3 {
                                                            top: 15%;
                                                            left: 60%;
                                                        }

                                                        .pos-4 {
                                                            top: 15%;
                                                            left: 83%;
                                                        }


                                                        @media (max-width: 768px) {
                                                            .position-overlay {
                                                                width: 35px;
                                                                height: 35px;
                                                                font-size: 12px;
                                                            }

                                                            .layout-title {
                                                                font-size: 14px;
                                                            }
                                                        }
                                                    </style>

                                                    <div class="layout-title">Select Your Reformer Position</div>

                                                    <div class="reformer-image-container">
                                                        <img src="{{ asset('images/chair_class.png') }}"
                                                            alt="Reformer Studio Layout" class="reformer-image">

                                                        <!-- Position overlays -->
                                                        @foreach (range(1, 4) as $position)
                                                            @php
                                                                $positionData = $available_positions[$position - 1];
                                                                $isAvailable = $positionData['is_available'];
                                                                $isSelected = $selected_position == $position;
                                                            @endphp
                                                            <div class="position-overlay pos-{{ $position }} {{ $isAvailable ? 'available' : 'occupied' }} {{ $isSelected ? 'selected' : '' }}"
                                                                wire:click="{{ $isAvailable ? 'selectPosition(' . $position . ')' : '' }}"
                                                                title="{{ $isAvailable ? 'Available' : 'Occupied' }} - Position {{ $position }}">
                                                                {{ $position }}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                @if ($selected_position)
                                                    <div class="alert alert-success">
                                                        <strong>Selected Position:</strong> Chair
                                                        {{ $selected_position }}
                                                    </div>
                                                @else
                                                    <div class="alert alert-info">
                                                        Please select a Chair position to continue with your booking.
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <center>
                                            <form wire:submit="save">
                                                <button type="submit" class="cta-btn"
                                                    {{ ($is_reformer_class || $isChairClass) && !$selected_position ? 'disabled' : '' }}>
                                                    Confirm Booking
                                                </button>
                                            </form>
                                        </center>
                                        <a href="{{ route('detail-class', ['id' => $schedule->classes->group_class_id, 'date' => $dateClass]) }}"
                                            class="cta-btn btn-secondary">Back</a>
                                    @endif
                                @endif
                            @endguest
                        </div>
                    </div>
                </div><!-- End Pricing Item -->

            </div>

        </div>

    </section><!-- /Pricing Section -->
</div>
