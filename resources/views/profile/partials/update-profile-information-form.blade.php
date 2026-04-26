<section>
    <header>
        <h2 class="h5 fw-bold text-dark">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="text-muted small">
            {{ __("Perbarui informasi profil, alamat email, nomor HP, dan alamat pengiriman Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 needs-validation" novalidate>
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-bold small text-uppercase text-muted">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold small text-uppercase text-muted">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-muted">
                        {{ __('Email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline text-decoration-none">
                            {{ __('Klik di sini untuk kirim ulang verifikasi.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success small fw-bold">
                            {{ __('Link verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="nomor_hp" class="form-label fw-bold small text-uppercase text-muted">Nomor WhatsApp / HP</label>
            <div class="input-group">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-telephone"></i></span>
                <input type="text" name="nomor_hp" id="nomor_hp" class="form-control border-start-0 ps-0" 
                       value="{{ old('nomor_hp', $user->nomor_hp) }}" 
                       placeholder="Contoh: 08123456789">
            </div>
            @error('nomor_hp')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label fw-bold small text-uppercase text-muted">Alamat Pengiriman</label>
            <textarea name="alamat" id="alamat" rows="3" class="form-control" 
                      placeholder="Masukkan alamat lengkap (Jalan, No. Rumah, Kecamatan, Kota)">{{ old('alamat', $user->alamat) }}</textarea>
            @error('alamat')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success fw-bold mb-0">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ __('Berhasil Disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>