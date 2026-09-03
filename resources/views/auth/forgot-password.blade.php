@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')

<div class="register-wrapper">
    <div class="register-card">
        <div class="register-header">
            <h1>Lupa Password</h1>
            <p>Masukkan email yang terdaftar untuk menerima link reset password.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" style="padding: 45px 60px 55px;">
            @csrf

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
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email terdaftar">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">
                <i class="fas fa-paper-plane"></i> Kirim Link Reset
            </button>

            <p style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login') }}" style="color: var(--az); font-weight: 700;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>
            </p>

            <p style="text-align: center; margin-top: 10px; font-size: 14px; color: #94a3b8;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color: var(--az); font-weight: 700;">Daftar sekarang</a>
            </p>
        </form>
    </div>
</div>

@endsection