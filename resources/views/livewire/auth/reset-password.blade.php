<div>
    <section id="hero" class="hero section">
        <div class="container">
            <div class="row gy-4">
                {{-- <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
                    <h1>Reset Password</h1>
                    <p>Masukkan password baru untuk akun Anda</p>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="{{ asset('image/Aligne_Black.webp') }}" class="img-fluid animated" alt="Aligné Logo">
                </div> --}}
            </div>
        </div>
    </section>

    <section id="contact" class="contact section">
        <div class="container section-title">
            <h2>Reset Password</h2>
            <p>Masukkan password baru untuk akun {{ $email }}</p>
        </div>

        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-lg-6">
                    @if(!$tokenValid)
                        <div class="alert alert-danger text-center" role="alert">
                            <h4 class="alert-heading">Link Tidak Valid!</h4>
                            <p>Link reset password sudah kadaluarsa atau tidak valid.</p>
                            <hr>
                            <p class="mb-0">
                                <small>
                                    • Link reset password hanya berlaku selama 30 menit<br>
                                    • Silakan minta link reset password yang baru
                                </small>
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('forgot-password') }}" class="btn btn-primary">
                                    Minta Link Baru
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary ms-2">
                                    Kembali ke Login
                                </a>
                            </div>
                        </div>
                    @elseif($passwordReset)
                        <div class="alert alert-success text-center" role="alert">
                            <h4 class="alert-heading">Password Berhasil Direset!</h4>
                            <p>Password Anda telah berhasil diubah.</p>
                            <hr>
                            <p class="mb-0">
                                <small>
                                    Sekarang Anda dapat login dengan password baru Anda.
                                </small>
                            </p>
                            <div class="mt-3">
                                <button wire:click="goToLogin" class="btn btn-primary">
                                    Login Sekarang
                                </button>
                            </div>
                        </div>
                    @else
                        <form wire:submit="resetPassword" role="form" class="php-email-form">
                            <div class="row gy-4">
                                <div class="col-md-12">
                                    <label for="password" class="form-label">Password Baru</label>
                                    <input type="password" wire:model.defer="password" name="password" class="form-control" 
                                           placeholder="Masukkan password baru (minimal 8 karakter)" required>
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <input type="password" wire:model.defer="password_confirmation" name="password_confirmation" class="form-control" 
                                           placeholder="Ulangi password baru" required>
                                    @error('password_confirmation')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="alert alert-info" role="alert">
                                        <small>
                                            <strong>Persyaratan Password:</strong><br>
                                            • Minimal 8 karakter<br>
                                            • Kombinasi huruf dan angka direkomendasikan<br>
                                            • Hindari menggunakan informasi pribadi
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="loading" wire:loading wire:target="resetPassword">Loading...</div>
                                    <div class="error-message" style="display: none;"></div>
                                    <div class="sent-message" style="display: none;">Password berhasil direset!</div>

                                    <button type="submit" wire:loading.attr="disabled" wire:target="resetPassword">
                                        <span wire:loading.remove wire:target="resetPassword">Reset Password</span>
                                        <span wire:loading wire:target="resetPassword">Memproses...</span>
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
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
