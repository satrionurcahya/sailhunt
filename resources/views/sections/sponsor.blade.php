    {{-- ======================================================
                        SPONSOR
    ======================================================= --}}
    <section class="sponsor">
        <div class="container">
            <div class="section-header">
                <span>SUPPORTED BY</span>
                <h2>Sponsor & Partner</h2>
                <p>Terima kasih kepada seluruh pihak yang mendukung terselenggaranya Sail & Hunt Chapter I. <br class="d-none d-md-block">Ingin menjadi bagian? <a href="#contact" class="text-link">Hubungi kami</a>.</p>
            </div>

            {{-- Grid Sponsor --}}
            <div class="sponsor-grid">
                @php
                    $sponsors = [
                        ['img' => 'sponsor1.png', 'alt' => 'Sponsor 1'],
                        ['img' => 'sponsor2.png', 'alt' => 'Sponsor 2'],
                        ['img' => 'sponsor3.png', 'alt' => 'Sponsor 3'],
                        ['img' => 'sponsor4.png', 'alt' => 'Sponsor 4'],
                        ['img' => 'sponsor5.png', 'alt' => 'Sponsor 5'],
                        ['img' => 'sponsor6.png', 'alt' => 'Sponsor 6'],
                    ];
                @endphp

                @foreach ($sponsors as $sponsor)
                    <div class="sponsor-item">
                        <img src="{{ asset('assets/logo/sponsor/'.$sponsor['img']) }}" alt="{{ $sponsor['alt'] }}">
                    </div>
                @endforeach
            </div>
        </div>
    </section>