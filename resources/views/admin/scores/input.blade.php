@extends('layouts.admin')

@section('title', 'Input Nilai - ' . $competition->name)

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

        <div>
            <h1 class="h3 mb-1">
                Input Nilai
            </h1>

            <p class="text-muted mb-0">
                {{ $competition->name }}
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route('admin.scores.select') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Pilih Lomba
            </a>

            <a
                href="{{ route('admin.scores.ranking') }}"
                class="btn btn-outline-primary"
            >
                <i class="bi bi-bar-chart-fill"></i>
                Ranking
            </a>

        </div>

    </div>


    {{-- =========================================================
        FLASH SUCCESS
    ========================================================== --}}
    @if (session('success'))
        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >
            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- =========================================================
        FLASH ERROR
    ========================================================== --}}
    @if (session('error'))
        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >
            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    {{-- =========================================================
        ERROR VALIDASI LARAVEL
    ========================================================== --}}
    @if (isset($errors) && $errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                Terdapat kesalahan:
            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- =========================================================
        ERROR IMPORT EXCEL
    ========================================================== --}}
    @if (session('import_errors'))

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                <i class="bi bi-file-earmark-excel-fill me-2"></i>
                Import Excel dibatalkan.
            </div>

            <p class="mb-2">
                Tidak ada data yang disimpan karena terdapat kesalahan
                pada file Excel.
            </p>

            <ul class="mb-0">

                @foreach (session('import_errors') as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        INFORMASI KOMPETISI
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="text-muted small">
                        Mata Lomba
                    </div>

                    <div class="fw-bold">
                        {{ $competition->name }}
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-muted small">
                        Jumlah Peserta
                    </div>

                    <div class="fw-bold">
                        {{ $registrations->count() }}
                        tim
                    </div>

                </div>

                <div class="col-md-2">

                    <div class="text-muted small">
                        Ukuran Tim
                    </div>

                    <div class="fw-bold">
                        {{ $competition->team_size ?? '-' }}
                        orang
                    </div>

                </div>

                <div class="col-md-4">

                    <div class="text-muted small">
                        Sistem Penilaian
                    </div>

                    <div class="fw-bold">
                        Nilai → Ranking → Poin
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        IMPORT EXCEL
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-success text-white">

            <div class="d-flex align-items-center">

                <i class="bi bi-file-earmark-excel-fill fs-5 me-2"></i>

                <span class="fw-bold">
                    Import Nilai dari Excel
                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="alert alert-info">

                <div class="fw-bold mb-2">
                    Petunjuk Import
                </div>

                <ol class="mb-0 ps-3">

                    <li>
                        Download template Excel.
                    </li>

                    <li>
                        Isi kolom
                        <code>score</code>
                        dan
                        <code>notes</code>
                        sesuai hasil penilaian.
                    </li>

                    <li>
                        Jangan mengubah
                        <code>registration_code</code>.
                    </li>

                    <li>
                        Upload kembali file Excel.
                    </li>

                    <li>
                        Sistem akan memvalidasi kode peserta,
                        sekolah, level, dan mata lomba sebelum menyimpan nilai.
                    </li>

                </ol>

            </div>


            {{-- DOWNLOAD TEMPLATE --}}

            <div class="d-flex flex-wrap gap-2 mb-4">

                <a
                    href="{{ route('admin.scores.template', $competition) }}"
                    class="btn btn-success"
                >
                    <i class="bi bi-download me-1"></i>
                    Download Template Excel
                </a>

            </div>


            {{-- FORM IMPORT --}}

            <form
                action="{{ route('admin.scores.import', $competition) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="row align-items-end g-3">

                    <div class="col-md-8">

                        <label
                            for="score_excel"
                            class="form-label fw-semibold"
                        >
                            File Excel Nilai
                        </label>

                        <input
                            type="file"
                            name="file"
                            id="score_excel"
                            class="form-control"
                            accept=".xlsx,.xls"
                            required
                        >

                        <div class="form-text">
                            Format:
                            <strong>.xlsx</strong>
                            atau
                            <strong>.xls</strong>.
                            Maksimal 10 MB.
                        </div>

                    </div>

                    <div class="col-md-4">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="bi bi-upload me-1"></i>
                            Import Nilai
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        INPUT NILAI MANUAL
    ========================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <h5 class="mb-1">
                        Input Nilai Manual
                    </h5>

                    <div class="text-muted small">
                        Nilai dapat dimasukkan langsung melalui tabel di bawah.
                    </div>

                </div>

                <div class="text-muted small">
                    {{ $registrations->count() }} registration
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if ($registrations->isEmpty())

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                    </div>

                    <h5>
                        Belum ada peserta
                    </h5>

                    <p class="text-muted mb-0">
                        Belum terdapat registration untuk mata lomba ini.
                    </p>

                </div>

            @else

                <form
                    action="{{ route('admin.scores.store', $competition) }}"
                    method="POST"
                >

                    @csrf

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th
                                        class="text-center"
                                        style="width: 60px;"
                                    >
                                        #
                                    </th>

                                    <th
                                        style="min-width: 150px;"
                                    >
                                        Kode Peserta
                                    </th>

                                    <th
                                        style="min-width: 220px;"
                                    >
                                        Sekolah
                                    </th>

                                    <th
                                        class="text-center"
                                        style="min-width: 100px;"
                                    >
                                        Level
                                    </th>

                                    <th
                                        style="min-width: 280px;"
                                    >
                                        Peserta
                                    </th>

                                    <th
                                        class="text-center"
                                        style="min-width: 130px;"
                                    >
                                        Nilai
                                    </th>

                                    <th
                                        class="text-center"
                                        style="min-width: 100px;"
                                    >
                                        Rank
                                    </th>

                                    <th
                                        class="text-center"
                                        style="min-width: 100px;"
                                    >
                                        Poin
                                    </th>

                                    <th
                                        style="min-width: 250px;"
                                    >
                                        Catatan
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach ($registrations as $index => $registration)

                                    <tr>

                                        {{-- NOMOR --}}

                                        <td class="text-center">
                                            {{ $index + 1 }}
                                        </td>


                                        {{-- REGISTRATION CODE --}}

                                        <td>

                                            <span class="badge bg-dark">
                                                {{ $registration->registration_code ?? '-' }}
                                            </span>

                                            <input
                                                type="hidden"
                                                name="scores[{{ $index }}][registration_id]"
                                                value="{{ $registration->id }}"
                                            >

                                        </td>


                                        {{-- SEKOLAH --}}

                                        <td>

                                            <div class="fw-semibold">
                                                {{ $registration->unit->school_name ?? '-' }}
                                            </div>

                                            @if (!empty($registration->unit?->city))

                                                <div class="text-muted small">
                                                    {{ $registration->unit->city }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- LEVEL --}}

                                        <td class="text-center">

                                            @php
                                                $level = $registration->unit->level ?? '-';
                                            @endphp

                                            @if (strtolower($level) === 'wira')

                                                <span class="badge bg-primary">
                                                    Wira
                                                </span>

                                            @elseif (strtolower($level) === 'madya')

                                                <span class="badge bg-success">
                                                    Madya
                                                </span>

                                            @else

                                                <span class="badge bg-secondary">
                                                    {{ $level }}
                                                </span>

                                            @endif

                                        </td>


                                        {{-- PESERTA --}}

                                        <td>

                                            @if ($registration->participants->isNotEmpty())

                                                <ol class="mb-0 ps-3">

                                                    @foreach ($registration->participants as $participant)

                                                        <li>
                                                            {{ $participant->name }}
                                                        </li>

                                                    @endforeach

                                                </ol>

                                            @else

                                                <span class="text-muted">
                                                    Belum ada peserta
                                                </span>

                                            @endif

                                        </td>


                                        {{-- NILAI --}}

                                        <td>

                                            <input
                                                type="number"
                                                name="scores[{{ $index }}][score]"
                                                class="form-control text-center score-input"
                                                value="{{ old(
                                                    'scores.' . $index . '.score',
                                                    $registration->score?->score
                                                ) }}"
                                                step="0.01"
                                                min="0"
                                                placeholder="0.00"
                                            >

                                        </td>


                                        {{-- RANK --}}

                                        <td class="text-center">

                                            @if ($registration->score?->rank)

                                                <span class="badge bg-warning text-dark">
                                                    {{ $registration->score->rank }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- POINTS --}}

                                        <td class="text-center">

                                            @if ($registration->score)

                                                <span class="fw-bold">
                                                    {{ $registration->score->points ?? 0 }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    0
                                                </span>

                                            @endif

                                        </td>


                                        {{-- CATATAN --}}

                                        <td>

                                            <textarea
                                                name="scores[{{ $index }}][notes]"
                                                class="form-control"
                                                rows="2"
                                                placeholder="Catatan penilaian..."
                                            >{{ old(
                                                'scores.' . $index . '.notes',
                                                $registration->score?->notes
                                            ) }}</textarea>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- FOOTER FORM --}}

                    <div class="card-footer bg-white">

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                            <div class="text-muted small">

                                <i class="bi bi-info-circle me-1"></i>

                                Setelah nilai disimpan, sistem akan menghitung
                                ranking dan poin secara otomatis.

                            </div>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-save me-1"></i>

                                Simpan Nilai & Hitung Ranking

                            </button>

                        </div>

                    </div>

                </form>

            @endif

        </div>

    </div>


    {{-- =========================================================
        KETERANGAN SISTEM POIN
    ========================================================== --}}
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header">

            <h5 class="mb-0">
                <i class="bi bi-award-fill me-2"></i>
                Ketentuan Poin
            </h5>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-2 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Juara I
                        </div>

                        <div class="fs-4 fw-bold">
                            10
                        </div>

                        <div class="small text-muted">
                            poin
                        </div>

                    </div>

                </div>


                <div class="col-md-2 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Juara II
                        </div>

                        <div class="fs-4 fw-bold">
                            7
                        </div>

                        <div class="small text-muted">
                            poin
                        </div>

                    </div>

                </div>


                <div class="col-md-2 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Juara III
                        </div>

                        <div class="fs-4 fw-bold">
                            5
                        </div>

                        <div class="small text-muted">
                            poin
                        </div>

                    </div>

                </div>


                <div class="col-md-2 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Harapan I
                        </div>

                        <div class="fs-4 fw-bold">
                            3
                        </div>

                        <div class="small text-muted">
                            poin
                        </div>

                    </div>

                </div>


                <div class="col-md-2 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Harapan II
                        </div>

                        <div class="fs-4 fw-bold">
                            1
                        </div>

                        <div class="small text-muted">
                            poin
                        </div>

                    </div>

                </div>


                <div class="col-md-2 col-6">

                    <div class="border rounded p-3 text-center">

                        <div class="text-muted small">
                            Lainnya
                        </div>

                        <div class="fs-4 fw-bold">
                            0
                        </div>

                        <div class="small text-muted">
                            poin
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================== --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | File Excel validation
    |--------------------------------------------------------------------------
    */

    const fileInput = document.getElementById('score_excel');

    if (fileInput) {

        fileInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            const allowedExtensions = [
                'xlsx',
                'xls'
            ];

            const extension = file.name
                .split('.')
                .pop()
                .toLowerCase();

            if (!allowedExtensions.includes(extension)) {

                alert(
                    'File harus berformat .xlsx atau .xls.'
                );

                this.value = '';

                return;
            }

            const maxSize = 10 * 1024 * 1024;

            if (file.size > maxSize) {

                alert(
                    'Ukuran file maksimal 10 MB.'
                );

                this.value = '';

                return;
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi sebelum import Excel
    |--------------------------------------------------------------------------
    */

    const importForms =
        document.querySelectorAll(
            'form[action*="/import"]'
        );

    importForms.forEach(function (form) {

        form.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Import nilai dari Excel sekarang?\n\n' +
                'Sistem akan memvalidasi seluruh data terlebih dahulu. ' +
                'Jika ada data yang salah, tidak ada nilai yang akan disimpan.'
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Konfirmasi sebelum simpan manual
    |--------------------------------------------------------------------------
    */

    const manualForm =
        document.querySelector(
            'form[action*="/scores/"]'
        );

    /*
    |--------------------------------------------------------------------------
    | Hanya pasang handler jika form memiliki
    | input registration_id.
    |--------------------------------------------------------------------------
    */

    if (
        manualForm &&
        manualForm.querySelector(
            'input[name*="[registration_id]"]'
        )
    ) {

        manualForm.addEventListener(
            'submit',
            function (event) {

                const confirmed = confirm(
                    'Simpan seluruh perubahan nilai dan hitung ulang ranking?'
                );

                if (!confirmed) {
                    event.preventDefault();
                }

            }
        );

    }

});
</script>

@endpush

@endsection