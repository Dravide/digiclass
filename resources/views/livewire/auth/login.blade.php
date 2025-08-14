<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <div>
                    <div class="text-center mt-1">
                        <h4 class="font-size-18">Selamat Datang Kembali!</h4>
                        <p class="text-muted">Masuk untuk melanjutkan ke DigiClass.</p>
                    </div>

                    <form wire:submit="login" class="auth-input">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   wire:model="email"
                                   placeholder="Masukkan email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password"
                                   wire:model="password"
                                   placeholder="Masukkan password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   wire:model="remember"
                                   id="auth-remember-check">
                            <label class="form-check-label" for="auth-remember-check">Ingat saya</label>
                        </div>
                        
                        <div class="mt-3">
                            <button class="btn btn-primary w-100" type="submit" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="ri-login-circle-line me-1"></i> Masuk
                                </span>
                                <span wire:loading>
                                    <i class="ri-loader-2-line me-1 spinner-border spinner-border-sm"></i> Memproses...
                                </span>
                            </button>
                        </div>
                        
                        <div class="mt-4 pt-2 text-center">
                            <div class="signin-other-title">
                                <h5 class="font-size-14 mb-4 title">Atau masuk dengan</h5>
                            </div>
                            <div class="pt-2 hstack gap-2 justify-content-center">
                                <button type="button" class="btn btn-primary btn-sm" disabled>
                                    <i class="ri-facebook-fill font-size-16"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" disabled>
                                    <i class="ri-google-fill font-size-16"></i>
                                </button>
                                <button type="button" class="btn btn-dark btn-sm" disabled>
                                    <i class="ri-github-fill font-size-16"></i>
                                </button>
                                <button type="button" class="btn btn-info btn-sm" disabled>
                                    <i class="ri-twitter-fill font-size-16"></i>
                                </button>
                            </div>
                            <small class="text-muted">Fitur login sosial akan segera tersedia</small>
                        </div>
                    </form>
                </div>

                <div class="mt-4 text-center">
                    <p class="mb-0 text-muted">Hubungi administrator untuk mendapatkan akses</p>
                </div>
            </div>
        </div>
    </div>
</div>