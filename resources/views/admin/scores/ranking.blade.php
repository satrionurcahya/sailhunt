@extends('layouts.admin')

@section('title', 'Rekap Juara')

@section('content')

<div class="container-fluid">

    {{-- ============================================================
         HEADER
    ============================================================= --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">

        <div>

            <h1 class="h3 mb-1">
                Rekap Juara
            </h1>

            <p class="text-muted mb-0">
                Rekap prestasi seluruh unit berdasarkan hasil perlombaan.
            </p>

        </div>

        <div>

            <a
                href="{{ route('admin.scores.select') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Kembali ke Nilai
            </a>

        </div>

    </div>


    {{-- ============================================================
         INFORMASI DASAR JUARA
    ============================================================= --}}
    <div class="alert alert-info">

        <div class="fw-bold mb-1">

            <i class="bi bi-info-circle"></i>

            Dasar Penentuan Juara Umum

        </div>

        <div>

            Juara Umum ditentukan berdasarkan
            <strong>jumlah Juara 1 terbanyak</strong>
            yang diperoleh setiap unit.

        </div>

        <div class="mt-1">

            Kategori <strong>Wira dan Madya berada dalam
            satu klasemen</strong>.

        </div>

        <div class="mt-1">

            Nilai, ranking, dan points digunakan sebagai
            <strong>data evaluasi hasil lomba</strong>,
            bukan sebagai dasar Juara Umum.

        </div>

    </div>


    {{-- ============================================================
         JUARA UMUM
    ============================================================= --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-trophy-fill text-warning"></i>

                Juara Umum

            </h5>

        </div>

        <div class="card-body p-0">

            @if($juaraUmum->isEmpty())

                <div class="p-4 text-center text-muted">

                    Belum terdapat unit yang memperoleh
                    Juara 1.

                </div>

            @else

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th
                                    width="70"
                                    class="text-center"
                                >
                                    Posisi
                                </th>

                                <th>
                                    Sekolah
                                </th>

                                <th
                                    width="120"
                                    class="text-center"
                                >
                                    Level
                                </th>

                                <th
                                    width="170"
                                    class="text-center"
                                >
                                    Juara 1
                                </th>

                                <th
                                    width="150"
                                    class="text-center"
                                >
                                    Lomba Dinilai
                                </th>

                                <th
                                    width="180"
                                    class="text-center"
                                >
                                    Poin Evaluasi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach(
                                $juaraUmum
                                as $index => $item
                            )

                                <tr>

                                    <td class="text-center">

                                        <strong>
                                            {{ $index + 1 }}
                                        </strong>

                                    </td>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->unit->school_name }}

                                        </div>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-secondary">

                                            {{ $item->unit->level }}

                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <span
                                            class="badge bg-success fs-6"
                                        >

                                            {{ $item->champion_count }}

                                            piala

                                        </span>

                                    </td>

                                    <td class="text-center">

                                        {{ $item->competitions_count }}

                                    </td>

                                    <td class="text-center text-muted">

                                        {{ $item->total_points }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
         JUARA FAVORIT
    ============================================================= --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-star-fill text-warning"></i>

                Juara Favorit

            </h5>

        </div>

        <div class="card-body p-0">

            @if($juaraFavorit->isEmpty())

                <div class="p-4 text-center text-muted">

                    Belum terdapat unit yang memenuhi
                    kriteria Juara Favorit.

                </div>

            @else

                <div class="table-responsive">

                    <table
                        class="table table-hover align-middle mb-0"
                    >

                        <thead class="table-light">

                            <tr>

                                <th
                                    width="70"
                                    class="text-center"
                                >
                                    No.
                                </th>

                                <th>
                                    Sekolah
                                </th>

                                <th
                                    width="120"
                                    class="text-center"
                                >
                                    Level
                                </th>

                                <th
                                    width="150"
                                    class="text-center"
                                >
                                    Juara 1
                                </th>

                                <th
                                    width="150"
                                    class="text-center"
                                >
                                    Lomba Dinilai
                                </th>

                                <th
                                    width="170"
                                    class="text-center"
                                >
                                    Poin Evaluasi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach(
                                $juaraFavorit
                                as $index => $item
                            )

                                <tr>

                                    <td class="text-center">

                                        {{ $index + 1 }}

                                    </td>

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $item->unit->school_name }}

                                        </div>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-secondary">

                                            {{ $item->unit->level }}

                                        </span>

                                    </td>

                                    <td class="text-center">

                                        {{ $item->champion_count }}

                                    </td>

                                    <td class="text-center">

                                        {{ $item->competitions_count }}

                                    </td>

                                    <td class="text-center text-muted">

                                        {{ $item->total_points }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>


    {{-- ============================================================
         KETERANGAN EVALUASI
    ============================================================= --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="bi bi-clipboard-data"></i>

                Evaluasi Nilai

            </h5>

        </div>

        <div class="card-body">

            <p class="mb-3">

                Data nilai digunakan untuk mengevaluasi
                hasil peserta pada masing-masing mata lomba.

            </p>

            <p class="mb-3">

                Ranking masing-masing lomba dihitung dari
                nilai tertinggi.

            </p>

            <div class="table-responsive">

                <table
                    class="table table-sm table-bordered mb-3"
                >

                    <thead class="table-light">

                        <tr>

                            <th>
                                Peringkat
                            </th>

                            <th>
                                Poin Evaluasi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td>Juara 1</td>
                            <td>10</td>
                        </tr>

                        <tr>
                            <td>Juara 2</td>
                            <td>7</td>
                        </tr>

                        <tr>
                            <td>Juara 3</td>
                            <td>5</td>
                        </tr>

                        <tr>
                            <td>Juara 4</td>
                            <td>3</td>
                        </tr>

                        <tr>
                            <td>Juara 5</td>
                            <td>1</td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="alert alert-secondary mb-0">

                <strong>Catatan:</strong>

                Poin evaluasi tidak digunakan untuk
                menentukan Juara Umum.

                Juara Umum ditentukan berdasarkan
                <strong>jumlah piala Juara 1</strong>.

            </div>

        </div>

    </div>


    {{-- ============================================================
         ACTION
    ============================================================= --}}
    <div class="d-flex flex-wrap gap-2 mb-4">

        <a
            href="{{ route('admin.scores.select') }}"
            class="btn btn-primary"
        >

            <i class="bi bi-clipboard-data"></i>

            Kelola Nilai

        </a>

        <a
            href="{{ route('admin.dashboard') }}"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-speedometer2"></i>

            Dashboard

        </a>

    </div>

</div>

@endsection