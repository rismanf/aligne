<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

        <div class="container">

            <div class="row gy-4 justify-content-center text-center align-items-center">

                <div class="col-lg-4">
                    <div class="pricing-item">
                        <h3>Login</h3>


                        <form wire:submit="login" role="form" class="php-email-form">

                            <div class="form-group mt-3">

                                <input type="email" wire:model.defer="email" name="email" class="form-control"
                                    placeholder="Email" required>
                            </div>
                            <div class="form-group mt-3">

                                <input type="password" wire:model.defer="password" name="password" class="form-control"
                                    placeholder="Password" required>
                            </div>
                            @error('email')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                            <div class="row">
                                <button class="cta-btn">Login</button>
                                <div class="col">
                                    <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                                    <p><a href="{{ route('forgot-password') }}" class="text-decoration-none">Forgot Password?</a></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- End Pricing Item -->

            </div>

        </div>

    </section><!-- /Pricing Section -->

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
</div>
