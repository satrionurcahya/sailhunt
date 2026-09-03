@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-header">
            <h1>Reset Password</h1>
            <p>Buat password baru untuk akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" style="padding: 45px 60px 55px;">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            @if(session('success'))
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif

            <div class="form-group">
                <label class="form-label" for="email">Email <span class="required">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email) }}" readonly style="background: #f1f5f9;">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password Baru <span class="required">*</span></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Min. 8 karakter, huruf besar, angka, simbol">
                <div class="form-help">Password minimal 8 karakter, harus memiliki huruf besar, angka, dan simbol.</div>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru <span class="required">*</span></label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">
                <i class="fas fa-key"></i> Reset Password
            </button>

            <p style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login') }}" style="color: var(--az); font-weight: 700;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>
            </p>
        </form>
    </div>
</div>

@endsection