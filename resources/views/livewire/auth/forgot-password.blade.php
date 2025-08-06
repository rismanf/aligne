<div>
    <section id="hero" class="hero section">
        <div class="container">
            <div class="row gy-4 mt-2">
                {{-- <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                    <h1>Lupa Password</h1>
                    <p>Masukkan email Anda untuk menerima link reset password</p>
                </div> --}}
                {{-- <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="{{ asset('image/Aligne_Black.webp') }}" class="img-fluid animated" alt="Aligné Logo">
                </div> --}}
            </div>
        </div>
    </section>

    <section id="contact" class="contact section">
        <div class="container section-title" >
            <h2>Reset Password</h2>
            <p>Masukkan alamat email yang terdaftar untuk menerima link reset password</p>
        </div>

        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-6">
                    @if(!$emailSent)
                        <form wire:submit="sendResetLink" role="form" class="php-email-form">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" wire:model.defer="email" name="email" class="form-control" 
                                           placeholder="Masukkan email Anda" required>
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading" wire:loading wire:target="sendResetLink">Loading...</div>
                                  
                                    <button type="submit" class="cta-btn" wire:loading.attr="disabled" wire:target="sendResetLink">
                                        <span wire:loading.remove wire:target="sendResetLink">Kirim Link Reset</span>
                                        <span wire:loading wire:target="sendResetLink">Mengirim...</span>
                                    </button>
                                </div>

                                <div class="col-md-12 text-center">
                                    <p class="mt-3">
                                        <a href="{{ route('login') }}" class="text-decoration-none" style="color: #4b2e2e;">
                                            ← Kembali ke Login
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    @else
                        <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px;" >
                            <h4 class="alert-heading">Email Terkirim!</h4>
                            <p>Link reset password telah dikirim ke <strong>{{ $email }}</strong></p>
                            <hr>
                            <p class="mb-0">
                                <small>
                                    • Periksa inbox atau folder spam Anda<br>
                                    • Link berlaku selama 30 menit<br>
                                    • Jika tidak menerima email, silakan coba lagi
                                </small>
                            </p>
                            <div class="mt-3">
                                <button wire:click="backToLogin" class="btn btn-primary">
                                    Kembali ke Login
                                </button>
                                <button wire:click="$set('emailSent', false)" class="btn btn-outline-secondary ms-2">
                                    Kirim Ulang
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
