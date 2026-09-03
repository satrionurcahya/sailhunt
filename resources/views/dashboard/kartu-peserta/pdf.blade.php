<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Kartu Peserta -
        {{ $registrations->first()->unit->school_name ?? 'Sail & Hunt' }}
    </title>


    <style>

        /* =========================================================
           RESET
           ========================================================= */

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }


        /* =========================================================
           ACTION BUTTON
           ========================================================= */

        .pdf-actions {
            position: fixed;

            top: 20px;
            right: 20px;

            z-index: 9999;

            display: flex;
            gap: 10px;
        }

        .pdf-actions a,
        .pdf-actions button {
            border: none;

            padding: 10px 16px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 14px;
            font-weight: 700;

            cursor: pointer;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-print {
            background: #2563eb;
            color: #ffffff;
        }


        /* =========================================================
           A4 PAGE
           ========================================================= */

        .pdf-page {
            width: 210mm;
            height: 297mm;

            padding: 10mm;

            display: flex;

            align-items: center;
            justify-content: center;

            page-break-after: always;
            break-after: page;
        }

        .pdf-page:last-child {
            page-break-after: auto;
            break-after: auto;
        }


        /* =========================================================
           GRID KARTU
           ========================================================= */

        .cards-grid {
            width: 100%;
            height: 100%;

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            column-gap: 6mm;

            align-items: center;
            justify-items: center;
        }


        /* =========================================================
           WRAPPER KARTU
           ========================================================= */

        .participant-card {
            position: relative;

            width: 91mm;
            height: 136.5mm;

            overflow: hidden;

            background: #ffffff;

            flex-shrink: 0;

            page-break-inside: avoid;
            break-inside: avoid;
        }


        /* =========================================================
           BACKGROUND KARTU
           ========================================================= */

        .participant-card-background {
            position: absolute;

            top: 0;
            left: 0;

            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            z-index: 1;

            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }


        /* =========================================================
           KODE LOMBA
           ========================================================= */

        .participant-card-code {
            position: absolute;

            left: 13%;
            top: 16%;

            width: 74%;
            height: 15.5%;

            display: flex;

            align-items: center;
            justify-content: center;

            padding: 5px;

            font-size: 26px;

            font-weight: 900;

            letter-spacing: 1.5px;

            text-align: center;

            color: #173c72;

            line-height: 1;

            z-index: 2;

            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }


        /* =========================================================
           NAMA SEKOLAH
           ========================================================= */

        .participant-card-school {
            position: absolute;

            left: 13%;
            top: 34.5%;

            width: 74%;
            height: 18%;

            display: flex;

            align-items: center;
            justify-content: center;

            padding: 8px;

            font-size: 18px;

            font-weight: 900;

            text-align: center;

            text-transform: uppercase;

            color: #173c72;

            line-height: 1.15;

            overflow-wrap: anywhere;

            z-index: 2;

            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }


        /* =========================================================
           SCREEN PREVIEW
           ========================================================= */

        @media screen {

            body {
                background: #f1f5f9;
            }

            .pdf-page {
                margin: 30px auto;

                background: #ffffff;

                box-shadow:
                    0 10px 35px rgba(0, 0, 0, 0.12);
            }

        }


        /* =========================================================
           PRINT
           ========================================================= */

        @media print {

            html,
            body {
                width: 210mm;
                min-height: 297mm;

                margin: 0 !important;
                padding: 0 !important;

                background: #ffffff !important;
            }


            .no-print {
                display: none !important;
            }


            .pdf-page {
                width: 210mm;
                height: 297mm;

                margin: 0 !important;
                padding: 10mm !important;

                display: flex !important;

                align-items: center !important;
                justify-content: center !important;

                background: #ffffff !important;

                page-break-after: always;
                break-after: page;
            }


            .pdf-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }


            .cards-grid {
                width: 100%;
                height: 100%;

                display: grid !important;

                grid-template-columns: repeat(2, 91mm) !important;

                column-gap: 6mm !important;

                align-items: center !important;
                justify-content: center !important;

                justify-items: center !important;
            }


            .participant-card {
                width: 91mm !important;
                height: 136.5mm !important;

                max-width: 91mm !important;
                max-height: 136.5mm !important;

                min-width: 91mm !important;
                min-height: 136.5mm !important;

                margin: 0 !important;
                padding: 0 !important;

                overflow: hidden !important;

                page-break-inside: avoid !important;
                break-inside: avoid !important;

                background: #ffffff !important;
            }


            .participant-card-background {
                position: absolute !important;

                top: 0 !important;
                left: 0 !important;

                width: 91mm !important;
                height: 136.5mm !important;

                display: block !important;

                object-fit: cover !important;

                visibility: visible !important;
                opacity: 1 !important;

                z-index: 1 !important;

                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }


            .participant-card-code {
                position: absolute !important;

                left: 13% !important;
                top: 16% !important;

                width: 74% !important;
                height: 15.5% !important;

                display: flex !important;

                align-items: center !important;
                justify-content: center !important;

                padding: 5px !important;

                font-size: 26px !important;

                font-weight: 900 !important;

                letter-spacing: 1.5px !important;

                text-align: center !important;

                color: #173c72 !important;

                line-height: 1 !important;

                z-index: 2 !important;
            }


            .participant-card-school {
                position: absolute !important;

                left: 13% !important;
                top: 34.5% !important;

                width: 74% !important;
                height: 18% !important;

                display: flex !important;

                align-items: center !important;
                justify-content: center !important;

                padding: 8px !important;

                font-size: 18px !important;

                font-weight: 900 !important;

                text-align: center !important;

                text-transform: uppercase !important;

                color: #173c72 !important;

                line-height: 1.15 !important;

                overflow-wrap: anywhere !important;

                z-index: 2 !important;
            }


            @page {
                size: A4 portrait;
                margin: 0;
            }

        }

    </style>

</head>


<body>


    {{-- =========================================================
         ACTION
         ========================================================= --}}

    <div class="pdf-actions no-print">

        <a href="{{ route('participant-cards.index') }}"
           class="btn-back">

            ← Kembali

        </a>


        <button type="button"
                class="btn-print"
                onclick="printPdf()">

            🖨 Simpan sebagai PDF

        </button>

    </div>



    {{-- =========================================================
         BAGI DATA MENJADI 2 KARTU PER HALAMAN
         ========================================================= --}}

    @foreach($registrations->chunk(2) as $pageRegistrations)

        <section class="pdf-page">


            <div class="cards-grid">


                @foreach($pageRegistrations as $registration)

                    <div class="participant-card">


                        {{-- =================================================
                             BACKGROUND
                             ================================================= --}}

                        <img src="{{ asset('assets/images/kartu-peserta.png') }}"
                             alt="Kartu Peserta"
                             class="participant-card-background">


                        {{-- =================================================
                             KODE
                             ================================================= --}}

                        <div class="participant-card-code">

                            {{ $registration->registration_code }}

                        </div>


                        {{-- =================================================
                             SEKOLAH
                             ================================================= --}}

                        <div class="participant-card-school">

                            {{ $registration->unit->school_name }}

                        </div>


                    </div>

                @endforeach


            </div>


        </section>

    @endforeach



    <script>

        function printPdf() {

            window.print();

        }

    </script>


</body>

</html>