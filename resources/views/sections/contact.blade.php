{{-- ======================================================
                        CONTACT (Kontak Resmi)
    ======================================================= --}}
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header text-center">
                <span>CONTACT US</span>
                <h2>Hubungi Panitia</h2>
                <p>Punya pertanyaan, ingin menjadi sponsor, atau butuh bantuan teknis? Kami siap membantu.</p>
            </div>

            <div class="contact-grid">
                {{-- PANITIA INTI --}}
                <div class="contact-persons">
                    <h3><i class="fas fa-user-tie"></i> Panitia Inti</h3>
                    <div class="person-list">
                        <a href="https://wa.me/62882005612447" target="_blank" class="person-item">
                            <div class="person-avatar"><i class="fab fa-whatsapp"></i></div>
                            <div class="person-detail">
                                <strong>Aldira Harlyansyah</strong>
                                <span>Ketua Pelaksana</span>
                                <small>+62 882-0056-12447</small>
                            </div>
                        </a>
                        <a href="https://wa.me/6283822698200" target="_blank" class="person-item">
                            <div class="person-avatar"><i class="fab fa-whatsapp"></i></div>
                            <div class="person-detail">
                                <strong>Maysya Salsabila</strong>
                                <span>Wakil Ketua</span>
                                <small>+62 838-2269-8200</small>
                            </div>
                        </a>
                        <a href="https://wa.me/6285725137397" target="_blank" class="person-item">
                            <div class="person-avatar"><i class="fab fa-whatsapp"></i></div>
                            <div class="person-detail">
                                <strong>Yana Oktafia</strong>
                                <span>Humas</span>
                                <small>+62 857-2513-7397</small>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- KONTAK LAINNYA --}}
                <div class="contact-other">
                    <h3><i class="fas fa-address-book"></i> Kontak Lainnya</h3>
                    <div class="other-list">
                        <a href="mailto:SailAndHunt.13@gmail.com" class="other-item">
                            <i class="fas fa-envelope"></i> SailAndHunt.13@gmail.com
                        </a>
                        <a href="https://instagram.com/sailandhuntchapter_1" target="_blank" class="other-item">
                            <i class="fab fa-instagram"></i> @sailandhuntchapter_1
                        </a>
                        <div class="other-item">
                            <i class="fas fa-map-marker-alt"></i> SMA Negeri 27 Bandung
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD CTA --}}
            <div class="contact-cta">
                <img src="{{ asset('assets/logo/logo-sailandhunt.png') }}" alt="Sail & Hunt" class="cta-logo">
                <h3>Siap Menjadi Bagian Sejarah?</h3>
                <p>Ini adalah tahun pertama Sail & Hunt. Daftarkan unitmu sekarang dan jadilah bagian dari sejarah baru kompetisi PMR Jawa Barat!</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg"><i class="fas fa-user-plus"></i> Daftar Sekarang</a>
                    <a href="{{ route('download.show', 'juklak-juknis') }}" class="btn btn-outline btn-lg" target="_blank">
                        <i class="fas fa-book-open"></i> Download Juklak
                    </a>
                </div>
            </div>
        </div>
    </section>