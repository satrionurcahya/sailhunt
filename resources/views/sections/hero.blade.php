    {{-- ======================================================
                        HERO (Epik & Interaktif)
    ======================================================= --}}
    <section class="hero">
        {{-- Partikel dekoratif --}}
        <div class="hero-particles" id="heroParticles"></div>

        {{-- Ikon mengambang --}}
        <div class="hero-floating">
            <i class="fas fa-anchor float-icon"></i>
            <i class="fas fa-compass float-icon"></i>
            <i class="fas fa-ship float-icon"></i>
            <i class="fas fa-skull float-icon"></i>
        </div>

        <div class="hero-container">
            <div class="hero-left">
                <div class="hero-badge">
                    <i class="fas fa-flag"></i> Pertama Kali Diadakan – Pendaftaran Dibuka
                </div>

                <img src="{{ asset('assets/logo/logo-sailandhunt.png') }}" alt="Sail & Hunt" class="hero-logo">

                <p class="hero-subtitle">Sailing for Glory,<br>Hunting the Treasure!</p>
                <p class="hero-tagline">Adventure is the Realm of Pirates</p>

                <p class="hero-description">
                    Sail & Hunt Chapter I adalah kompetisi <strong>Palang Merah Remaja</strong> tingkat Jawa Barat pertama dengan tema petualangan bajak laut. <strong>19 mata lomba</strong> dalam <strong>5 cabang</strong> siap menjadi panggung prestasi dan solidaritas PMR Madya & Wira.
                </p>

                <div class="hero-info">
                    <div><i class="fas fa-calendar-days"></i> 26 September 2026</div>
                    <div><i class="fas fa-location-dot"></i> SMAN 27 Bandung</div>
                    <div><i class="fas fa-clock"></i> 06.00 – 21.00 WIB</div>
                </div>

                <div class="hero-button">
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus"></i> Daftarkan Unit
                    </a>
                    <a href="#competition" class="btn btn-secondary btn-lg">
                        <i class="fas fa-trophy"></i> Lihat Perlombaan
                    </a>
                </div>
            </div>

            <div class="hero-right">
                <div class="event-card">
                    <h3>Chapter I</h3>
                    <div class="event-row"><span>Kategori</span><strong>Madya & Wira</strong></div>
                    <div class="event-row"><span>Cabang</span><strong>5 Cabang</strong></div>
                    <div class="event-row"><span>Mata Lomba</span><strong>19 Mata Lomba</strong></div>
                    <div class="event-row"><span>Sistem</span><strong>Tim/Individu</strong></div>
                    <div class="event-divider"></div>
                    <div class="event-highlight">
                        <h2>Total Hadiah</h2>
                        <p>Piala • Sertifikat • Uang Pembinaan</p>
                    </div>
                </div>
            </div>
        </div>

        <svg class="hero-wave" viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path fill="#ffffff" d="M0,64L80,69.3C160,75,320,85,480,85.3C640,85,800,75,960,64C1120,53,1280,43,1360,37.3L1440,32L1440,120L0,120Z"></path>
        </svg>
    </section>