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
                            <div class="row">
                                <button class="cta-btn">Create</button>

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
