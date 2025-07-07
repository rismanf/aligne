<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

        <div class="container">

            <div class="row gy-4 justify-content-center text-center align-items-center">
                @if (session()->has('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                <div class="col-lg-5">
                    <div class="pricing-item">
                        <h3>Register</h3>
                        <form wire:submit="save" class="row g-4">
                            <div class="form-group mt-3">
                                <input type="text" name="name" wire:model="name" class="form-control"
                                    placeholder="Your Name" required>
                            </div>
                            <div class="form-group mt-3">
                                <input type="email" name="email" wire:model="email" class="form-control"
                                    placeholder="Your Email" required>
                            </div>
                            <div class="form-group mt-3">
                                <input id="registerPhone" name="phone" wire:model="phone" type="text"
                                    inputmode="numeric" pattern="[0-9]+" class="form-control" placeholder="Phone Number"
                                    aria-label="Phone Number" required />
                            </div>
                            <div class="form-group mt-3">
                                <input type="password" name="password" wire:model="password" class="form-control"
                                    placeholder="Password" required>
                            </div>
                            <div class="form-group mt-3">
                                <input type="password" name="password_confirmation" wire:model="password_confirmation"
                                    class="form-control" placeholder="Confirm Password" required>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops! Ada beberapa masalah:</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <button type="submit" class="cta-btn" wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading.remove wire:target="save">Register</span>
                                <span wire:loading wire:target="save">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"
                                        aria-hidden="true"></span>
                                    Processing...
                                </span>
                            </button>
                        </form>
                    </div>
                </div><!-- End Pricing Item -->

            </div>

        </div>

    </section><!-- /Pricing Section -->

    <script>
        function handleCheckout(event) {
            event.preventDefault();

            const btn = document.getElementById('checkout-btn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            // Simulasi delay (ganti ini dengan submit asli atau AJAX)
            setTimeout(() => {
                document.getElementById('checkout-form').submit();
            }, 1000);
        }
    </script>

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
