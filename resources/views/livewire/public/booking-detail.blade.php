<div>
    <!-- Hero Section -->
    <section class="hero section" style="background-color: #F6F1EB;">
        <div class="container">
            {{-- <div class="row gy-4">
                <div class="col-lg-12 text-center">
                    <h1 class="mb-2" style="color: #4B2E2E;">Booking Details</h1>
                </div>
            </div> --}}
        </div class="mb-2">
    </section>

    <!-- Booking Detail Section -->
    <section style="background-color: #F6F4EB;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 mb-4 mt-4">
                    <!-- Back Button -->
                    {{-- <div class="mb-4 mt-4">
                        <button wire:click="backToScanner" class="btn"
                            style="background-color: transparent; border: 2px solid #4B2E2E; color: #4B2E2E;">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Scanner
                        </button>
                    </div> --}}

                    <!-- Messages -->
                    {{-- @if ($message)
                        <div class="alert 
                            @if ($messageType === 'success') alert-success
                            @elseif($messageType === 'warning') alert-warning
                            @else alert-danger @endif 
                            alert-dismissible fade show"
                            role="alert">
                            <div class="d-flex align-items-center">
                                <i
                                    class="bi 
                                    @if ($messageType === 'success') bi-check-circle-fill
                                    @elseif($messageType === 'warning') bi-exclamation-triangle-fill
                                    @else bi-x-circle-fill @endif me-2"></i>
                                {{ $message }}
                            </div>
                        </div>
                    @endif --}}

                    <!-- Booking Details Card -->
                    @if ($scanResult)
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-body pt-4 pl-5 pr-5 pb-4">
                                <div class="text-center mb-4">
                                    {{-- <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                        style="width: 80px; height: 80px; background-color: #A6A58A; background-opacity: 0.2;">
                                        <i class="bi bi-person-check" style="font-size: 2rem; color: #4B2E2E;"></i>
                                    </div> --}}
                                    <h4 class="card-title" style="color: #4B2E2E;">Booking Information</h4>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-md-10">
                                        <!-- Member Information Section -->
                                        <div class="rounded p-4 mb-4"
                                            style="background-color: #D4BDA5; background-opacity: 0.3;">
                                            <h6 class="mb-3" style="color: #4B2E2E;">
                                                <i class="bi bi-person-circle me-2"></i>
                                                Member Information
                                            </h6>
                                            
                                            <!-- Member Avatar and Basic Info -->
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="me-3">
                                                    @if($scanResult['user_avatar'])
                                                        <img src="{{ asset('storage/' . $scanResult['user_avatar']) }}" 
                                                             alt="Member Avatar" 
                                                             class="rounded-circle"
                                                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #4B2E2E;">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                                                             style="width: 80px; height: 80px; background-color: #4B2E2E; color: white;">
                                                            <i class="bi bi-person-fill" style="font-size: 2rem;"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-1 fw-bold" style="color: #4B2E2E;">{{ $scanResult['user_name'] }}</h5>
                                                    <p class="mb-0" style="color: #111111;">
                                                        <i class="bi bi-envelope me-1"></i>
                                                        {{ $scanResult['user_email'] }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Member
                                                            Since</small>
                                                        <span class="fw-bold"
                                                            style="color: #4B2E2E;">{{ $scanResult['member_since'] }}</span>
                                                        <small
                                                            style="color: #4B2E2E;">{{ $scanResult['member_duration'] }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Completed
                                                            Classes</small>
                                                        <h2 class="fw-bold" style="color: #4B2E2E;">
                                                            <i class="bi bi-trophy me-1"></i>
                                                            {{ $scanResult['completed_classes'] }} Classes
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Class Information Section -->
                                        <div class="rounded p-4"
                                            style="background-color: #CBB4A0; background-opacity: 0.3;">
                                            <h6 class="mb-3" style="color: #4B2E2E;">
                                                <i class="bi bi-calendar-event me-2"></i>
                                                Class Information @if ($scanResult['qr_verified'])
                                                    <small style="color: #111111;">
                                                        <i class="bi bi-shield-check me-1"></i>QR Verified
                                                    </small>
                                                @endif
                                            </h6>
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Booking
                                                            Code</small>
                                                        <span class="font-monospace"
                                                            style="color: #4B2E2E;">{{ $scanResult['booking_code'] }}</span>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Class</small>
                                                        <span class="fw-bold"
                                                            style="color: #4B2E2E;">{{ $scanResult['class_name'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Schedule Time</small>
                                                        <span class="fw-bold"
                                                            style="color: #4B2E2E;">{{ $scanResult['class_date'] }} - {{ $scanResult['class_time'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Type</small>
                                                        <span class="fw-bold"
                                                            style="color: #4B2E2E;">{{ $scanResult['group_class'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex flex-column">
                                                        <small class="fw-medium" style="color: #111111;">Trainer</small>
                                                        <span class="fw-bold"
                                                            style="color: #4B2E2E;">{{ $scanResult['trainer_name'] }}</span>
                                                    </div>
                                                </div>

                                                @if ($scanResult['is_reformer_class'] && $scanResult['reformer_position'])
                                                    <div class="col-12">
                                                        <div class="text-center mt-3 pt-3"
                                                            style="border-top: 1px solid #A6A58A;">
                                                            <div class="rounded p-3 d-inline-block"
                                                                style="background-color: #4B2E2E; background-opacity: 0.1;">
                                                                <i class="bi bi-geo-alt me-2"
                                                                    style="color: #4B2E2E;"></i>
                                                                <span class="fw-bold" style="color: #4B2E2E;">
                                                                    Reformer Position:
                                                                    {{ $scanResult['reformer_position'] }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="d-flex gap-3 mt-4">
                                            @if ($booking && $booking->canCheckIn())
                                                <button wire:click="confirmCheckIn" class="btn btn-lg flex-fill fw-bold"
                                                    style="background-color: #A6A58A; border-color: #A6A58A; color: white;">
                                                    <i class="bi bi-check-circle me-2"></i>
                                                    Confirm Check-In
                                                </button>
                                            @endif
                                            <button wire:click="backToScanner" class="btn btn-lg px-4"
                                                style="background-color: transparent; border: 2px solid #A6A58A; color: #A6A58A;">
                                                <i class="bi bi-arrow-left me-2"></i>
                                                Back to Scanner
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No booking found -->
                        <div class="card shadow-lg border-0 mb-4">
                            <div class="card-body p-5 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                    style="width: 80px; height: 80px; background-color: #dc3545; background-opacity: 0.1;">
                                    <i class="bi bi-exclamation-triangle"
                                        style="font-size: 2rem; color: #ffffff;"></i>
                                </div>
                                <h4 class="card-title mb-3" style="color: #4B2E2E;">Booking Not Found</h4>
                                <p class="mb-4" style="color: #A6A58A;">The booking you're looking for could not be
                                    found or may have been cancelled.</p>
                                <button wire:click="backToScanner" class="btn"
                                    style="background-color: #4B2E2E; border-color: #4B2E2E; color: white;">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Back to Scanner
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listen for check-in success
            Livewire.on('checkin-success', () => {
                setTimeout(() => {
                    window.location.href = '{{ route('member-checkin') }}';
                }, 3000);
            });
        });
    </script>
@endpush
