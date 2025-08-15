<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="alert alert-success text-center mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger text-center mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($booking)
                <!-- Booking Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <h5 class="text-muted mb-2">Your class booking details</h5>
                        </div>

                        <!-- Class Type Badge -->
                        <div class="text-center mb-3">
                            <span
                                class="badge 
                                @if ($booking['group_class'] == 'REFORMER CLASS') bg-info
                                @elseif($booking['group_class'] == 'CHAIR CLASS') bg-success
                                @else bg-warning @endif 
                                px-3 py-2">
                                {{ $booking['group_class'] }}
                            </span>
                        </div>

                        <!-- Class Name -->
                        <h2 class="text-center mb-4 fw-bold">{{ $booking['class_name'] }}</h2>

                        <!-- Class Details Grid -->
                        <div class="row text-center mb-4">
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block">Date</small>
                                <strong>{{ \Carbon\Carbon::parse($booking['date'])->format('D, M j, Y') }}</strong>
                            </div>
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block">Time</small>
                                <strong>{{ $booking['start_time'] }} - {{ $booking['end_time'] }}</strong>
                            </div>
                        </div>

                        <div class="row text-center mb-4">
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block">Trainer</small>
                                <strong>{{ $booking['trainer_name'] }}</strong>
                            </div>
                            <div class="col-6 mb-3">
                                <small class="text-muted d-block">Membership</small>
                                <strong>{{ $booking['membership_name'] }}</strong>
                            </div>
                        </div>

                        @if (
                            ($booking['group_class'] == 'REFORMER CLASS' || $booking['group_class'] == 'CHAIR CLASS') &&
                                $booking['reformer_position']
                        )
                            <!-- Reformer Position -->
                            <div class="text-center mb-4">
                                <div class="bg-primary bg-opacity-10 rounded p-3">
                                    <small class="text-muted d-block mb-2">Your Position</small>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <strong class="text-primary fs-5">Position
                                            {{ $booking['reformer_position'] }}</strong>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Please find your assigned machine</small>
                                </div>
                            </div>
                        @endif

                        <!-- Booking Code -->
                        <div class="text-center mb-4">
                            <small class="text-muted d-block">Booking Code</small>
                            <div class="bg-light p-2 rounded mt-2">
                                <strong class="font-monospace">{{ $booking['booking_code'] }}</strong>
                            </div>
                        </div>

                        <!-- QR Code -->
                        <div class="text-center mb-4">
                            <small class="text-muted d-block mb-2">Show this QR code at the studio</small>
                            <div class="d-inline-block">
                                <img src="{{ $booking['qr_code'] }}" alt="QR Code" class="img-fluid"
                                    style="max-width: 200px; height: auto;">
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if ($booking['visit_status'] == 'visited')
                                <!-- Show feedback button for attended classes -->
                                <a href="{{ route('user.feedback', $booking['id']) }}" class="btn btn-warning">
                                    <i class="fas fa-star"></i> Share Your Experience
                                </a>
                            @elseif($booking['can_cancel'])
                                <button wire:click="cancelBooking"
                                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                                    class="btn btn-outline-danger">
                                    Cancel Booking
                                </button>
                            @else
                                <button class="btn btn-outline-secondary" disabled>
                                    Too close to class time
                                </button>
                            @endif

                            <div class="row g-2 mt-2">
                                <div class="col-6">
                                    <a href="{{ route('user.booking') }}" class="btn btn-primary w-100">
                                        View All Bookings
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('classes') }}" class="btn btn-outline-primary w-100">
                                        Book Another Class
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Information -->
                <div class="card mt-3 border-0 bg-light">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3">Important Information</h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-1">• Please arrive 10 minutes before class starts</li>
                            <li class="mb-1">• Bring your own water bottle and towel</li>
                            <li class="mb-1">• Cancellations must be made at least 12 hours before class</li>
                            <li class="mb-0">• Show your QR code to the instructor for attendance</li>
                        </ul>
                    </div>
                </div>
            @else
                <!-- No Booking Found -->
                <div class="text-center py-5">
                    <h3 class="mb-3">No Booking Found</h3>
                    <p class="text-muted mb-4">We couldn't find any recent booking for your account.</p>
                    <a href="{{ route('classes') }}" class="btn btn-primary">
                        Browse Available Classes
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
