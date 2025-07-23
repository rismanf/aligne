<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

        <div class="container">

            <div class="row gy-4 justify-content-center text-center ">
                @if (session()->has('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>

                    <script>
                        window.addEventListener('redirect-after-success', function() {
                            setTimeout(function() {
                                window.location.href = "{{ route('user.order') }}"; // ganti dengan route tujuan Anda
                            }, 3000); // 3 detik delay
                        });
                    </script>
                @endif
                <div class="col-lg-6">
                    <div class="pricing-item">
                        <div class="border p-3 mb-3">
                            {{-- <h5>Class summary</h5> --}}
                            <h5>{{ $schedule->classes->group_class }}</h5>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Class Name</span>
                                <span>{{ $schedule->classes->name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Level</span>
                                <span>{{ $schedule->classes->level_class }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Time</span>
                                <span>{{ \Carbon\Carbon::parse($schedule->classes->time)->format('h:i A') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <span>Trainer</span>
                                <span>{{ $schedule->trainer->name }}</span>
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
                                <p>To purchase this plan and use its benefits in the future, log in to your account or sign
                                    up.
                                </p>
                                <a href="{{ route('login') }}" class="cta-btn">Log In</a>
                                <a href="{{ route('register') }}" class="cta-btn">Sign Up</a>
                            @else
                                @if ($data_schedule == 0)
                                
                                    <form wire:submit="save">
                                        <button type="submit" class="cta-btn">Confrim</button>
                                    </form>
                                @else
                                    <p>You have already signed up for this class</p>
                                     <a href="{{ url()->previous() }}" type="submit" class="cta-btn">Back</a >
                                @endif
                            @endguest
                        </div>
                    </div>
                </div><!-- End Pricing Item -->

            </div>

        </div>

    </section><!-- /Pricing Section -->
</div>
