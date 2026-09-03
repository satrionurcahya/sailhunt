@extends('layouts.auth')

@section('title', 'Login Unit PMR')

@section('content')
<div class="register-wrapper">
    <div class="register-card">
        <div class="register-header">
            <h1>Login Unit PMR</h1>
            <p>Masuk ke dashboard peserta Sail & Hunt Chapter I</p>
        </div>

        <form method="POST" action="{{ route('login') }}" style="padding: 45px 60px 55px;">
            @csrf

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label class="form-label" for="login">
                    Username atau Email <span class="required">*</span>
                </label>
                <input type="text" class="form-control @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login') }}" placeholder="Masukkan username atau email" autofocus>
                @error('login') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    Password <span class="required">*</span>
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>

            <p style="text-align: center; margin-top: 10px;">
                <a href="{{ route('password.request') }}" style="color: var(--az); font-weight: 600; font-size: 0.9rem;">
                    <i class="fas fa-key"></i> Lupa Password?
                </a>
            </p>

            <p style="text-align: center; margin-top: 20px;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color: var(--az); font-weight: 700;">Daftar sekarang</a>
            </p>
        </form>
    </div>
</div>
@endsection