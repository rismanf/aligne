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
                                <span>{{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }}</span>
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
                                    <a href="{{ url()->previous() }}" class="cta-btn">Back</a>
                                @else
                                    @if ($cek_quota == 0)
                                        <p>You do not have quota for this class type</p>
                                        <p>Please buy a membership package that includes this class type</p>
                                        <a href="{{ route('membership') }}" class="cta-btn">Buy Membership</a>                                    
                                        <a href="{{ url()->previous() }}" class="cta-btn">Back to Classes</a>
                                    @else
                                        <p>You have {{ $cek_quota }} quota left for this class type</p>
                                        <form wire:submit="save">
                                            <button type="submit" class="cta-btn">Confirm Booking</button>
                                        </form>
                                        <a href="{{ url()->previous() }}" class="cta-btn btn-secondary">Back</a>
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
