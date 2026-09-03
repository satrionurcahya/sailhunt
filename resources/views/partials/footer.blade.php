<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <img src="{{ asset('assets/logo/logo-sailandhunt.png') }}" alt="Sail & Hunt" class="footer-logo">
                <p class="footer-tagline">Adventure is the Realm of Pirates.</p>
                <p>Kompetisi PMR tingkat Jawa Barat, diselenggarakan oleh <br> <strong>PMR SMA Negeri 27 Bandung</strong>.</p>
            </div>
            <div class="footer-menu">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/') }}#about">Tentang</a></li>
                    <li><a href="{{ url('/') }}#timeline">Timeline</a></li>
                    <li><a href="{{ url('/') }}#competition">Perlombaan</a></li>
                    <li><a href="{{ url('/') }}#faq">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-menu">
                <h4>Event</h4>
                <ul>
                    <li>19 Mata Lomba</li>
                    <li>5 Cabang</li>
                    <li>Tingkat PMR Madya & Wira</li>
                </ul>
            </div>
            <div class="footer-menu">
                <h4>Ikuti Kami</h4>
                <div class="social-links">
                    <a href="https://instagram.com/sailandhuntchapter_1" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="mailto:SailAndHunt.13@gmail.com" aria-label="Email"><i class="fas fa-envelope"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© {{ date('Y') }} Sail & Hunt Chapter I</p>
        </div>
    </div>
</footer>