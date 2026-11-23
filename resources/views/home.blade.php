@extends('layouts.public')

@section('title', 'Beranda')
@section('description', 'Lapor Pak membantu warga menyampaikan pengaduan lingkungan dengan mudah dan memantau penanganannya secara transparan.')

@section('content')
    {{-- ============================================================ hero --}}
    <section class="hero">
        <div class="container hero__inner">
            <div data-reveal>
                <span class="hero__badge">
                    <span class="dot" aria-hidden="true"></span>
                    Ruang Aspirasi &amp; Pengaduan Warga
                </span>

                <h1 class="hero__title">
                    Suara Warga,<br>
                    Langkah <span class="accent">Perubahan.</span>
                </h1>

                <p class="hero__lead">
                    Laporkan permasalahan di lingkungan sekitar dengan mudah, cepat, dan
                    transparan melalui Lapor Pak. Setiap pengaduan tercatat dan dapat dipantau
                    sampai selesai.
                </p>

                <div class="hero__actions">
                    <a class="btn btn--primary btn--lg" href="{{ auth()->check() ? route(auth()->user()->dashboardRoute()) : route('register') }}">
                        Mulai Buat Pengaduan
                        <x-icon name="arrow-right" :size="17" class="icon--arrow" />
                    </a>
                    <a class="btn btn--ghost btn--lg" href="#cara-kerja">Pelajari Cara Kerjanya</a>
                </div>

                <p class="hero__trust">
                    <x-icon name="shield-check" :size="17" />
                    Akses pengaduan terbatas untuk Anda dan pengelola.
                </p>
            </div>

            <div class="hero__art" data-reveal="scale" style="--reveal-delay:120ms" aria-label="Ilustrasi alur pengaduan">
                <div class="hero__art-bg" aria-hidden="true"></div>

                <div class="hero__card">
                    <div class="hero__card-head">
                        <span class="left">
                            <span class="hero__card-mark" aria-hidden="true"><x-icon name="messages-square" :size="16" /></span>
                            Pengaduan Lingkungan
                        </span>
                        <span class="hero__card-tag">ILUSTRASI</span>
                    </div>

                    <div class="hero__card-body">
                        <div class="hero__scene" aria-hidden="true">
                            <svg viewBox="0 0 400 180" role="img" aria-label="Ilustrasi lingkungan perkotaan">
                                <rect width="400" height="180" fill="#eff6ff"/>
                                <circle cx="326" cy="35" r="20" fill="#fde68a"/>
                                <rect y="140" width="400" height="40" fill="#dbeafe"/>
                                <path d="M0 160h400" stroke="#93c5fd" stroke-width="2"/>
                                <path d="M0 176h400" stroke="#fff" stroke-width="3" stroke-dasharray="28 18"/>
                                <path d="M55 140V67h58v73M131 140V42h66v98M215 140V82h56v58" fill="#bfdbfe" stroke="#60a5fa" stroke-width="2"/>
                                <path d="M65 81h12m15 0h12M65 102h12m15 0h12M65 122h12m15 0h12M143 57h14m14 0h14M143 78h14m14 0h14M143 99h14m14 0h14M143 120h14m14 0h14M227 96h12m12 0h12M227 117h12m12 0h12" stroke="#60a5fa" stroke-width="5"/>
                                <path d="M310 140V99m0-12V60h29" stroke="#475569" stroke-width="4" fill="none"/>
                                <path d="M330 60h17" stroke="#fbbf24" stroke-width="7" stroke-linecap="round"/>
                                <path d="M35 140v-30m0-8V85" stroke="#059669" stroke-width="5"/>
                                <circle cx="35" cy="92" r="17" fill="#6ee7b7"/>
                                <path d="M365 140v-30" stroke="#059669" stroke-width="5"/>
                                <circle cx="365" cy="99" r="21" fill="#6ee7b7"/>
                            </svg>
                        </div>

                        <p class="hero__card-eyebrow">Contoh Pengaduan</p>
                        <h2 class="hero__card-title">Lampu jalan perlu perbaikan</h2>
                        <p class="hero__card-desc">Laporan sederhana. Dampak nyata untuk lingkungan.</p>

                        <div class="hero__steps">
                            <div class="hero__step">
                                <span class="hero__step-icon hero__step-icon--done" aria-hidden="true"><x-icon name="circle-check" :size="16" /></span>
                                <div>
                                    <strong>Pengaduan diterima</strong>
                                    <span>Informasi tercatat dengan baik</span>
                                </div>
                            </div>
                            <div class="hero__step">
                                <span class="hero__step-icon hero__step-icon--now" aria-hidden="true"><x-icon name="loader-circle" :size="16" /></span>
                                <div>
                                    <strong>Dalam penanganan</strong>
                                    <span>Pengelola menindaklanjuti laporan</span>
                                </div>
                            </div>
                            <div class="hero__step">
                                <span class="hero__step-icon hero__step-icon--todo" aria-hidden="true"><x-icon name="check" :size="16" /></span>
                                <div>
                                    <strong>Lingkungan lebih nyaman</strong>
                                    <span>Perubahan dimulai dari kepedulian</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero__float">
                    <span class="hero__float-icon" aria-hidden="true"><x-icon name="heart-handshake" :size="19" /></span>
                    <div>
                        <strong>Kepedulian Anda berarti</strong>
                        <span>Bersama, kita bisa membuat perubahan.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========================================================== tentang --}}
    <section class="section" id="tentang">
        <div class="container">
            <div class="about-grid">
                <div data-reveal>
                    <span class="eyebrow">Tentang Lapor Pak</span>
                    <h2 class="section-title" style="margin-top:16px">Lingkungan yang lebih baik, dimulai dari kita.</h2>
                </div>
                <p class="lead" data-reveal style="--reveal-delay:80ms;align-self:center">
                    Lapor Pak menjadi jembatan antara warga dan pengelola untuk menangani
                    permasalahan publik. Mulai dari jalan rusak hingga sampah menumpuk,
                    sampaikan masalah di sekitar Anda agar dapat ditindaklanjuti.
                </p>
            </div>

            <div class="feature-grid" data-stagger>
                <div class="feature">
                    <span class="feature__icon" aria-hidden="true"><x-icon name="send" :size="25" /></span>
                    <h3>Mudah disampaikan</h3>
                    <p>Buat pengaduan dalam beberapa langkah. Sertakan deskripsi dan foto agar masalah lebih mudah dipahami.</p>
                </div>
                <div class="feature">
                    <span class="feature__icon" aria-hidden="true"><x-icon name="scan-eye" :size="25" /></span>
                    <h3>Transparan dipantau</h3>
                    <p>Ikuti status penanganan pengaduan Anda, mulai dari menunggu peninjauan sampai selesai ditangani.</p>
                </div>
                <div class="feature">
                    <span class="feature__icon" aria-hidden="true"><x-icon name="handshake" :size="25" /></span>
                    <h3>Berdampak bersama</h3>
                    <p>Bantu pengelola mengenali masalah dan membangun lingkungan yang lebih aman serta nyaman.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================================================= cara kerja --}}
    <section class="section section--tint" id="cara-kerja">
        <div class="container">
            <div class="section__head section__head--center" data-reveal>
                <span class="eyebrow">Cara Melapor</span>
                <h2>Sampaikan dalam tiga langkah.</h2>
                <p>Mudah untuk Anda, berarti untuk lingkungan.</p>
            </div>

            <div class="steps-grid" data-stagger>
                <div class="step-card">
                    <span class="step-card__n">01</span>
                    <h3>Buat akun warga</h3>
                    <p>Daftarkan diri menggunakan email dan lengkapi informasi kontak Anda.</p>
                </div>
                <div class="step-card">
                    <span class="step-card__n">02</span>
                    <h3>Sampaikan pengaduan</h3>
                    <p>Tuliskan masalah, tanggal kejadian, dan tambahkan foto bukti bila tersedia.</p>
                </div>
                <div class="step-card">
                    <span class="step-card__n">03</span>
                    <h3>Pantau perkembangannya</h3>
                    <p>Buka dashboard untuk melihat tindak lanjut pengaduan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- =========================================================== status --}}
    <section class="section" id="status">
        <div class="container">
            <div class="section__head section__head--center" data-reveal>
                <span class="eyebrow">Status Pengaduan</span>
                <h2>Jelas di setiap tahap.</h2>
                <p>Ketahui apa yang sedang terjadi pada pengaduan Anda.</p>
            </div>

            <div class="status-grid" data-stagger>
                <div class="status-card">
                    <x-status-badge status="pending" />
                    <p>Pengaduan menunggu peninjauan admin. Informasi sudah tercatat dan berada dalam antrean.</p>
                </div>
                <div class="status-card">
                    <x-status-badge status="proses" />
                    <p>Pengaduan sedang ditindaklanjuti. Pengelola mulai menangani masalah yang dilaporkan.</p>
                </div>
                <div class="status-card">
                    <x-status-badge status="selesai" />
                    <p>Pengaduan telah selesai ditangani. Hasil penanganan dicatat dan dapat dilihat warga.</p>
                </div>
            </div>

            <div class="cta" data-reveal>
                <div>
                    <h2>Punya kepedulian? Sampaikan.</h2>
                    <p>Jadilah bagian dari perubahan untuk lingkungan kita. Setiap laporan membantu pengelola bertindak lebih cepat.</p>
                </div>
                <a class="btn btn--light btn--lg" href="{{ auth()->check() ? route(auth()->user()->dashboardRoute()) : route('register') }}">
                    Mulai Sekarang
                    <x-icon name="arrow-right" :size="17" class="icon--arrow" />
                </a>
            </div>
        </div>
    </section>
@endsection
