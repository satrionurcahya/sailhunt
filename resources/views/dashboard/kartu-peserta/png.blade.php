<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Download {{ $registration->registration_code }}
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
            min-height: 100%;

            background: #f1f5f9;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }


        /* =========================================================
           PAGE
           ========================================================= */

        .download-page {

            min-height: 100vh;

            padding: 30px;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;
        }


        /* =========================================================
           KARTU
           ========================================================= */

        .participant-card {

            position: relative;

            /*
             * Rasio asli desain:
             *
             * 1024 × 1536
             *
             * = 2 : 3
             */
            width: min(100%, 1024px);

            aspect-ratio: 1024 / 1536;

            overflow: hidden;

            background: #ffffff;
        }


        /* =========================================================
           BACKGROUND KARTU
           ========================================================= */

        .participant-card-background {

            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            display: block;

            object-fit: cover;

            z-index: 1;
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

            padding: 15px;

            font-size: 52px;

            font-weight: 900;

            letter-spacing: 2px;

            text-align: center;

            color: #173c72;

            line-height: 1;

            z-index: 2;
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

            padding: 20px;

            font-size: 38px;

            font-weight: 900;

            text-align: center;

            text-transform: uppercase;

            color: #173c72;

            line-height: 1.15;

            overflow-wrap: anywhere;

            word-break: normal;

            z-index: 2;
        }


        /* =========================================================
           ACTION BUTTON
           ========================================================= */

        .download-actions {

            margin-top: 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 10px;
        }


        .download-actions button,
        .download-actions a {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 10px 18px;

            border: none;

            border-radius: 8px;

            font-size: 14px;

            font-weight: 700;

            text-decoration: none;

            cursor: pointer;
        }


        .download-button {

            background: #2563eb;

            color: #ffffff;
        }


        .back-button {

            background: #e5e7eb;

            color: #111827;
        }


        /* =========================================================
           MOBILE
           ========================================================= */

        @media (max-width: 768px) {

            .download-page {

                padding: 15px;
            }

            .participant-card-code {

                font-size: 36px;

                letter-spacing: 1px;
            }

            .participant-card-school {

                font-size: 26px;

                padding: 12px;
            }

        }

    </style>

</head>


<body>


<div class="download-page">


    {{-- =========================================================
         KARTU PESERTA
         ========================================================= --}}

    <div class="participant-card"
         id="participantCard">


        {{-- BACKGROUND --}}

        <img
            src="{{ asset('assets/images/kartu-peserta.png') }}"
            alt="Kartu Peserta Sail & Hunt"
            class="participant-card-background"
            id="cardBackground"
        >


        {{-- KODE LOMBA --}}

        <div class="participant-card-code">

            {{ $registration->registration_code }}

        </div>


        {{-- NAMA SEKOLAH --}}

        <div class="participant-card-school">

            {{ $registration->unit->school_name }}

        </div>


    </div>



    {{-- =========================================================
         ACTION
         ========================================================= --}}

    <div class="download-actions">

        <button
            type="button"
            class="download-button"
            onclick="downloadCard()"
        >

            Download PNG

        </button>


        <a
            href="{{ route(
                'participant-cards.show',
                $registration->registration_code
            ) }}"
            class="back-button"
        >

            Kembali

        </a>

    </div>


</div>



{{-- =========================================================
     HTML2CANVAS
     ========================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js">
</script>


<script>

async function downloadCard() {

    const card = document.getElementById('participantCard');

    const background =
        document.getElementById('cardBackground');


    /*
     * Pastikan gambar background
     * sudah selesai dimuat sebelum
     * canvas dibuat.
     */
    if (!background.complete) {

        await new Promise((resolve, reject) => {

            background.onload = resolve;

            background.onerror = reject;

        });

    }


    try {

        const canvas = await html2canvas(card, {

            /*
             * Ukuran output yang diinginkan.
             */
            width: 1024,

            height: 1536,

            /*
             * Jangan memperbesar lagi.
             *
             * 1024 × 1536
             * akan menjadi ukuran PNG final.
             */
            scale: 1,

            /*
             * Posisi viewport tidak memengaruhi hasil.
             */
            scrollX: 0,

            scrollY: 0,

            /*
             * Background transparan.
             */
            backgroundColor: null,

            /*
             * Izinkan gambar eksternal.
             */
            useCORS: true,

            /*
             * Abaikan elemen action.
             */
            ignoreElements: function(element) {

                return element.classList.contains(
                    'download-actions'
                );

            }

        });


        /*
         * Buat link download.
         */
        const link = document.createElement('a');


        link.download =
            '{{ $registration->registration_code }}.png';


        link.href =
            canvas.toDataURL(
                'image/png',
                1.0
            );


        /*
         * Trigger download.
         */
        document.body.appendChild(link);

        link.click();

        link.remove();


    } catch (error) {

        console.error(
            'Gagal membuat PNG:',
            error
        );

        alert(
            'Kartu gagal dibuat menjadi PNG. ' +
            'Silakan coba lagi.'
        );

    }

}

</script>


</body>

</html>