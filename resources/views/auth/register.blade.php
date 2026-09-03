@extends('layouts.auth')

@section('title', 'Daftar Unit')

@section('content')

<div class="register-wrapper">

    <div class="register-card">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="register-header">

            <h1>Pendaftaran Unit PMR</h1>

            <p>
                Sail & Hunt Chapter I – Kompetisi PMR Tingkat Jawa Barat
            </p>

        </div>


        {{-- =========================================================
             FORM
        ========================================================== --}}
        <form
            id="registerForm"
            method="POST"
            action="{{ route('register') }}"
            autocomplete="off"
        >

            @csrf


            {{-- =====================================================
                 STEP 1: TINGKAT
            ====================================================== --}}
            <div
                class="form-step active"
                data-step="1"
            >

                <h2>Pilih Tingkat PMR</h2>

                <p>
                    Tentukan tingkat unit yang akan didaftarkan.
                </p>


                <div class="grid grid-2">

                    {{-- PMR MADYA --}}
                    <label
                        class="radio-card @if(old('level') == 'Madya') active @endif"
                    >

                        <input
                            type="radio"
                            name="level"
                            value="Madya"
                            @if(old('level') == 'Madya')
                                checked
                            @endif
                        >

                        <h3>
                            PMR Madya
                        </h3>

                        <p>
                            SMP / Sederajat
                        </p>

                    </label>


                    {{-- PMR WIRA --}}
                    <label
                        class="radio-card @if(old('level') == 'Wira') active @endif"
                    >

                        <input
                            type="radio"
                            name="level"
                            value="Wira"
                            @if(old('level') == 'Wira')
                                checked
                            @endif
                        >

                        <h3>
                            PMR Wira
                        </h3>

                        <p>
                            SMA / Sederajat
                        </p>

                    </label>

                </div>


                @error('level')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- =====================================================
                 STEP 2: DATA SEKOLAH
            ====================================================== --}}
            <div
                class="form-step"
                data-step="2"
            >

                <h2>
                    Data Sekolah
                </h2>

                <p>
                    Lengkapi informasi sekolah asal unit PMR.
                </p>


                {{-- =================================================
                     NAMA SEKOLAH
                ================================================== --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="school_name"
                    >
                        Nama Sekolah
                        <span class="required">*</span>
                    </label>


                    <input
                        type="text"
                        class="form-control @error('school_name') is-invalid @enderror"
                        id="school_name"
                        name="school_name"
                        value="{{ old('school_name') }}"
                        placeholder="Contoh: SMA NEGERI 27 BANDUNG"
                        autocomplete="off"
                        maxlength="255"
                        oninput="this.value = this.value.toUpperCase()"
                        required
                    >


                    {{-- CONTOH --}}
                    <div class="form-help">

                        💡 Contoh:
                        <strong>
                            SMA NEGERI 27 BANDUNG
                        </strong>

                    </div>


                    {{-- NOTE SATU AKUN --}}
                    <div class="form-help">

                        ⚠️
                        <strong>
                            Satu sekolah hanya diperbolehkan memiliki satu akun/unit.
                        </strong>

                        Pastikan nama sekolah yang dimasukkan sudah benar.

                    </div>


                    @error('school_name')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     ALAMAT
                ================================================== --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="address"
                    >
                        Alamat
                        <span class="required">*</span>
                    </label>


                    <textarea
                        class="form-control @error('address') is-invalid @enderror"
                        id="address"
                        name="address"
                        rows="2"
                        placeholder="Alamat lengkap sekolah"
                        autocomplete="off"
                    >{{ old('address') }}</textarea>


                    @error('address')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     KABUPATEN / KOTA
                ================================================== --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="city"
                    >
                        Kabupaten / Kota
                        <span class="required">*</span>
                    </label>


                    <select
                        class="form-control @error('city') is-invalid @enderror"
                        id="city"
                        name="city"
                        autocomplete="off"
                    >

                        <option value="">
                            Cari Kabupaten / Kota...
                        </option>


                        @php

                            $cities = [

                                'Kab. Bandung',

                                'Kab. Bandung Barat',

                                'Kab. Bekasi',

                                'Kab. Bogor',

                                'Kab. Ciamis',

                                'Kab. Cianjur',

                                'Kab. Cirebon',

                                'Kab. Garut',

                                'Kab. Indramayu',

                                'Kab. Karawang',

                                'Kab. Kuningan',

                                'Kab. Majalengka',

                                'Kab. Pangandaran',

                                'Kab. Purwakarta',

                                'Kab. Subang',

                                'Kab. Sukabumi',

                                'Kab. Sumedang',

                                'Kab. Tasikmalaya',

                                'Kota Bandung',

                                'Kota Banjar',

                                'Kota Bekasi',

                                'Kota Bogor',

                                'Kota Cimahi',

                                'Kota Cirebon',

                                'Kota Depok',

                                'Kota Sukabumi',

                                'Kota Tasikmalaya'

                            ];

                        @endphp


                        @foreach($cities as $cityOption)

                            <option
                                value="{{ $cityOption }}"
                                @if(old('city') == $cityOption)
                                    selected
                                @endif
                            >
                                {{ $cityOption }}
                            </option>

                        @endforeach

                    </select>


                    @error('city')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =================================================
                     KODE POS
                ================================================== --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="postal_code"
                    >
                        Kode Pos
                        <span class="required">*</span>
                    </label>


                    <input
                        type="text"
                        class="form-control @error('postal_code') is-invalid @enderror"
                        id="postal_code"
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                        placeholder="5 digit kode pos"
                        maxlength="5"
                        autocomplete="off"
                    >


                    @error('postal_code')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- =====================================================
                 STEP 3: DATA UNIT & PENDAMPING
            ====================================================== --}}
            <div
                class="form-step"
                data-step="3"
            >

                <h2>
                    Data Unit PMR
                </h2>

                <p>
                    Informasi pendamping dan komandan unit.
                </p>


                {{-- PEMBINA --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="coach_name"
                    >
                        Nama Pembina
                        <span class="required">*</span>
                    </label>


                    <input
                        type="text"
                        class="form-control @error('coach_name') is-invalid @enderror"
                        id="coach_name"
                        name="coach_name"
                        value="{{ old('coach_name') }}"
                        placeholder="Nama lengkap pembina"
                        autocomplete="off"
                    >


                    @error('coach_name')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PELATIH --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="trainer_name"
                    >
                        Nama Pelatih / Fasilitator
                        <span class="required">*</span>
                    </label>


                    <input
                        type="text"
                        class="form-control @error('trainer_name') is-invalid @enderror"
                        id="trainer_name"
                        name="trainer_name"
                        value="{{ old('trainer_name') }}"
                        placeholder="Nama lengkap pelatih"
                        autocomplete="off"
                    >


                    @error('trainer_name')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- KOMANDAN --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="commander_name"
                    >
                        Nama Komandan
                        <span class="required">*</span>
                    </label>


                    <input
                        type="text"
                        class="form-control @error('commander_name') is-invalid @enderror"
                        id="commander_name"
                        name="commander_name"
                        value="{{ old('commander_name') }}"
                        placeholder="Nama komandan unit"
                        autocomplete="off"
                    >


                    @error('commander_name')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- =====================================================
                 STEP 4: BUAT AKUN
            ====================================================== --}}
            <div
                class="form-step"
                data-step="4"
            >

                <h2>
                    Buat Akun
                </h2>

                <p>
                    Data login untuk mengakses dashboard peserta.
                </p>


                {{-- EMAIL --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="email"
                    >
                        Email
                        <span class="required">*</span>
                    </label>


                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Gunakan email resmi PMR sekolah"
                        autocomplete="off"
                    >


                    <div class="form-help">

                        💡 Disarankan menggunakan email resmi PMR sekolah
                        (contoh: pmr.sman1bdg@gmail.com)

                    </div>


                    @error('email')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- USERNAME --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="username"
                    >
                        Username
                        <span class="required">*</span>
                    </label>


                    <input
                        type="text"
                        class="form-control @error('username') is-invalid @enderror"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Minimal 4 karakter"
                        autocomplete="off"
                    >


                    @error('username')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PASSWORD --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="password"
                    >
                        Password
                        <span class="required">*</span>
                    </label>


                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Min. 8 karakter, huruf besar, angka, simbol"
                        autocomplete="new-password"
                    >


                    @error('password')

                        <div class="form-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- KONFIRMASI PASSWORD --}}
                <div class="form-group">

                    <label
                        class="form-label"
                        for="password_confirmation"
                    >
                        Konfirmasi Password
                        <span class="required">*</span>
                    </label>


                    <input
                        type="password"
                        class="form-control"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                    >

                </div>


                {{-- AGREEMENT --}}
                <div class="checkbox">

                    <input
                        type="checkbox"
                        id="agreement"
                        name="agreement"
                        @if(old('agreement'))
                            checked
                        @endif
                    >


                    <label for="agreement">

                        Saya menyetujui

                        <a
                            href="#"
                            target="_blank"
                        >
                            syarat dan ketentuan
                        </a>

                        yang berlaku

                    </label>

                </div>


                @error('agreement')

                    <div class="form-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            {{-- =====================================================
                 STEP 5: REVIEW
            ====================================================== --}}
            <div
                class="form-step"
                data-step="5"
            >

                <h2>
                    Review Pendaftaran
                </h2>

                <p>
                    Periksa kembali data sebelum dikirim.
                </p>


                <div class="review-card">


                    {{-- TINGKAT --}}
                    <div class="review-section">

                        <div class="review-title">

                            <i class="fas fa-layer-group"></i>

                            Tingkat

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Tingkat PMR
                            </span>


                            <span
                                class="review-value"
                                id="reviewLevel"
                            >
                                {{ old('level', '-') }}
                            </span>

                        </div>

                    </div>


                    {{-- DATA SEKOLAH --}}
                    <div class="review-section">

                        <div class="review-title">

                            <i class="fas fa-school"></i>

                            Data Sekolah

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Nama Sekolah
                            </span>


                            <span
                                class="review-value"
                                id="reviewSchool"
                            >
                                {{ old('school_name', '-') }}
                            </span>

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Alamat
                            </span>


                            <span
                                class="review-value"
                                id="reviewAddress"
                            >
                                {{ old('address', '-') }}
                            </span>

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Kabupaten/Kota
                            </span>


                            <span
                                class="review-value"
                                id="reviewCity"
                            >
                                {{ old('city', '-') }}
                            </span>

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Kode Pos
                            </span>


                            <span
                                class="review-value"
                                id="reviewPostal"
                            >
                                {{ old('postal_code', '-') }}
                            </span>

                        </div>

                    </div>


                    {{-- UNIT & PENDAMPING --}}
                    <div class="review-section">

                        <div class="review-title">

                            <i class="fas fa-users"></i>

                            Unit & Pendamping

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Pembina
                            </span>


                            <span
                                class="review-value"
                                id="reviewCoach"
                            >
                                {{ old('coach_name', '-') }}
                            </span>

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Pelatih
                            </span>


                            <span
                                class="review-value"
                                id="reviewTrainer"
                            >
                                {{ old('trainer_name', '-') }}
                            </span>

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Komandan
                            </span>


                            <span
                                class="review-value"
                                id="reviewCommander"
                            >
                                {{ old('commander_name', '-') }}
                            </span>

                        </div>

                    </div>


                    {{-- AKUN --}}
                    <div class="review-section">

                        <div class="review-title">

                            <i class="fas fa-key"></i>

                            Akun

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Email
                            </span>


                            <span
                                class="review-value"
                                id="reviewEmail"
                            >
                                {{ old('email', '-') }}
                            </span>

                        </div>


                        <div class="review-item">

                            <span class="review-label">
                                Username
                            </span>


                            <span
                                class="review-value"
                                id="reviewUsername"
                            >
                                {{ old('username', '-') }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 NAVIGASI
            ====================================================== --}}
            <div class="form-navigation">


                {{-- SEBELUMNYA --}}
                <button
                    type="button"
                    class="btn btn-secondary prev-step"
                    id="prevStepBtn"
                >

                    <i class="fas fa-arrow-left"></i>

                    Sebelumnya

                </button>


                {{-- KEMBALI KE BERANDA --}}
                <a
                    href="{{ route('home') }}"
                    class="btn btn-outline"
                    id="backHomeBtn"
                    style="display:none;"
                >

                    <i class="fas fa-home"></i>

                    Kembali ke Beranda

                </a>


                {{-- SELANJUTNYA --}}
                <button
                    type="button"
                    class="btn btn-primary next-step"
                    id="nextStepBtn"
                >

                    Selanjutnya

                    <i class="fas fa-arrow-right"></i>

                </button>


                {{-- DAFTARKAN --}}
                <button
                    type="submit"
                    class="btn btn-success"
                    id="submitRegister"
                    style="display:none;"
                >

                    <i class="fas fa-check"></i>

                    Daftarkan

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================== --}}
@push('scripts')

    <script src="{{ asset('assets/js/register.js') }}"></script>

@endpush

@endsection