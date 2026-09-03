{{-- ======================================================
                        COMPETITION (5 Cabang Resmi)
    ======================================================= --}}
<section id="competition" class="competition">
    <div class="container">
        <div class="section-header">
            <span>COMPETITION</span>
            <h2>5 Cabang – 19 Mata Lomba</h2>
            <p>Seluruh mata lomba terbuka untuk <strong>PMR Madya</strong> dan <strong>PMR Wira</strong> (kecuali ada catatan khusus di Juknis).</p>
        </div>

        {{-- Grid 5 cabang --}}
        <div class="comp-grid comp-grid-5">
            @php
            $cabangs = [
                ['img' => 'pp.png',   'title' => 'Pertolongan Pertama',       'list' => ['Pertolongan Pertama Umum','Pengetahuan Pertolongan Pertama','Tandu Darurat Ganda Putra','Tandu Darurat Ganda Putri','Tandu Darurat Mono']],
                ['img' => 'rsps.png', 'title' => 'Remaja Sehat Peduli Sesama', 'list' => ['Perawatan Keluarga','Remaja Tanggap Sehat']],
                ['img' => 'asb.png',  'title' => 'Ayo Siaga Bencana',          'list' => ['BKRK','Tas Siaga Bencana','Halang Rintang']],
                ['img' => 'kr.png',   'title' => 'Kesehatan Remaja',           'list' => ['Misi Remaja Sehat Mandiri','Kampanye Kreatif Remaja Sehat']],
                ['img' => 'kk.png',   'title' => 'Kepalangmerahan & Kreativitas','list' => ['Donor Darah Sukarela','Sang Jawara','Kepemimpinan','Cerdas Cermat','Paduan Suara','Video Kreatif','Gerakan Pungut Sampah (GPS)']],
            ];
            @endphp

            @foreach($cabangs as $cabang)
            <div class="comp-card">
                <div class="comp-icon">
                    <img src="{{ asset('assets/images/'.$cabang['img']) }}" alt="{{ $cabang['title'] }}">
                </div>
                <h3>{{ $cabang['title'] }}</h3>
                <ul class="comp-list">
                    @foreach($cabang['list'] as $item)
                    <li>{{ $item }}</li>
                    @endforeach
                </ul>
                <div class="comp-meta"><span>Madya & Wira</span></div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center; margin-top:2.5rem;">
            <a href="{{ route('download.show', 'juklak-juknis') }}" class="btn btn-primary btn-lg" target="_blank">
                <i class="fas fa-book-open"></i> Download Juklak & Juknis Lengkap
            </a>
        </div>
    </div>
</section>