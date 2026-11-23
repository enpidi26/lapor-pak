@php
    $wa = preg_replace('/\D+/', '', config('lapor.whatsapp'));
@endphp

<div class="help">
    <aside>
        <div class="sheet">
            <h2 class="sheet__title">Di halaman ini</h2>
            <nav class="help__nav mt-12" aria-label="Daftar isi">
                <a href="#mulai">Cara melapor</a>
                <a href="#status">Arti status</a>
                <a href="#ubah">Ubah atau hapus</a>
                <a href="#privasi">Privasi data</a>
                <a href="#akun">Akun dan password</a>
                <a href="#darurat">Keadaan darurat</a>
                <a href="#kontak">Kontak pengurus</a>
            </nav>

            <p class="sheet__foot" style="margin-bottom: 12px">Butuh jawaban cepat?</p>

            <a class="btn btn--ink btn--block" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">
                <x-icon name="whatsapp" :size="15" />
                WhatsApp pengurus
            </a>
        </div>
    </aside>

    <div class="help__body">
        <section class="sheet sheet--xl" id="mulai">
            <span class="label">panduan</span>
            <h2 class="h2 mt-12">Cara melapor</h2>
            <p class="body mt-8">Empat langkah, semuanya dari ponsel.</p>

            <ol class="step-list">
                <li>
                    <span class="step-list__n">01</span>
                    <div>
                        <h3>Masuk atau daftar</h3>
                        <p>Daftar dengan nama, email, nomor WhatsApp, dan alamat. Kurang dari satu menit.</p>
                    </div>
                </li>
                <li>
                    <span class="step-list__n">02</span>
                    <div>
                        <h3>Pilih jenis masalah</h3>
                        <p>Kebersihan, penerangan, jalan, keamanan, air, atau lainnya. Jenis ini menentukan siapa yang menangani.</p>
                    </div>
                </li>
                <li>
                    <span class="step-list__n">03</span>
                    <div>
                        <h3>Tulis cerita dan tanggal</h3>
                        <p>Sebutkan lokasi atau patokan, sejak kapan, dan dampaknya. Foto opsional tetapi membantu.</p>
                    </div>
                </li>
                <li>
                    <span class="step-list__n">04</span>
                    <div>
                        <h3>Kirim, lalu pantau</h3>
                        <p>Buka halaman Laporan untuk melihat status dan catatan terbaru.</p>
                    </div>
                </li>
            </ol>
        </section>

        <section class="sheet" id="status">
            <span class="label">istilah</span>
            <h2 class="h2 mt-12">Arti status laporan</h2>
            <p class="body mt-8">Status bergerak maju satu langkah, tidak bisa dilompati.</p>

            <div class="help-rows">
                <div class="help-row">
                    <x-status-badge status="pending" />
                    <div>
                        <h3>Menunggu</h3>
                        <p>Laporan sudah diterima dan berada di antrean pengurus. Tidak ada yang perlu dilakukan.</p>
                    </div>
                </div>

                <div class="help-row">
                    <x-status-badge status="proses" />
                    <div>
                        <h3>Diproses</h3>
                        <p>Pengurus sudah membaca dan mulai menindaklanjuti. Catatan penanganan muncul di halaman laporan.</p>
                    </div>
                </div>

                <div class="help-row">
                    <x-status-badge status="selesai" />
                    <div>
                        <h3>Selesai</h3>
                        <p>Masalah ditangani dan hasilnya dicatat. Status selesai tidak bisa dikembalikan.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="sheet" id="ubah">
            <span class="label">aturan</span>
            <h2 class="h2 mt-12">Kapan laporan bisa diubah</h2>

            <div class="cols cols--2 mt-16">
                <div class="sheet tile--mint" style="padding: 18px">
                    <h3 class="h3">Masih Menunggu</h3>
                    <p class="body mt-8">Buka detail laporan lalu pilih Ubah. Judul, cerita, tanggal, dan foto bisa diperbaiki. Laporan juga bisa dihapus.</p>
                </div>
                <div class="sheet" style="padding: 18px">
                    <h3 class="h3">Diproses atau Selesai</h3>
                    <p class="body mt-8">Isi laporan dikunci agar riwayat penanganan tetap jujur. Hubungi pengurus RT bila ada data yang keliru.</p>
                </div>
            </div>

            <p class="tiny mt-12">Menghapus laporan bersifat permanen, termasuk fotonya.</p>
        </section>

        <section class="sheet" id="privasi">
            <span class="label">privasi</span>
            <h2 class="h2 mt-12">Siapa yang bisa melihat laporan</h2>

            <div class="bullets">
                <div class="bullet">
                    <span class="bullet__icon"><x-icon name="lock" :size="13" /></span>
                    <div>
                        <h4>Hanya kamu dan pengurus RT</h4>
                        <p>Tidak ada halaman publik berisi daftar laporan warga.</p>
                    </div>
                </div>
                <div class="bullet">
                    <span class="bullet__icon"><x-icon name="user-check" :size="13" /></span>
                    <div>
                        <h4>Kontak untuk verifikasi</h4>
                        <p>Nomor WhatsApp dan alamat dipakai pengurus untuk mengecek lokasi.</p>
                    </div>
                </div>
                <div class="bullet">
                    <span class="bullet__icon"><x-icon name="shield" :size="13" /></span>
                    <div>
                        <h4>Tidak dibagikan ke pihak lain</h4>
                        <p>Data tidak dijual atau dibagikan ke pihak ketiga.</p>
                    </div>
                </div>
                <div class="bullet">
                    <span class="bullet__icon"><x-icon name="trash" :size="13" /></span>
                    <div>
                        <h4>Kamu memegang kendali</h4>
                        <p>Laporan yang belum ditinjau bisa dihapus sendiri.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="sheet" id="akun">
            <span class="label">akun</span>
            <h2 class="h2 mt-12">Masalah saat masuk</h2>

            <dl class="qa">
                <div>
                    <dt>Lupa password</dt>
                    <dd>Hubungi pengurus {{ config('lapor.rt') }} lewat WhatsApp. Pengurus akan membantu mengatur ulang akses.</dd>
                </div>
                <div>
                    <dt>Email salah atau tidak aktif</dt>
                    <dd>Minta pengurus memperbarui email akunmu. Jangan membuat akun baru agar riwayat laporan tetap di satu tempat.</dd>
                </div>
                <div>
                    <dt>Ingin menghapus akun</dt>
                    <dd>Hubungi pengurus RT. Laporan yang masih diproses perlu diselesaikan lebih dulu.</dd>
                </div>
            </dl>
        </section>

        <section class="sheet tile--cream" id="darurat">
            <div class="row" style="align-items: flex-start; gap: 12px">
                <span class="fact2__icon" style="background: var(--paper)"><x-icon name="alert" :size="16" /></span>
                <div>
                    <h2 class="h2">Kalau ini keadaan darurat, jangan menunggu</h2>
                    <p class="body mt-8">
                        Lapor Pak bukan layanan darurat. Untuk kebakaran, kecelakaan, bencana, atau
                        orang yang butuh pertolongan medis, hubungi <a class="link" href="tel:112">112</a>
                        atau pengurus RT langsung.
                    </p>
                </div>
            </div>
        </section>

        <section class="sheet" id="kontak">
            <span class="label">kontak</span>
            <h2 class="h2 mt-12">Hubungi pengurus {{ config('lapor.rt') }}</h2>

            <div class="contacts">
                <a class="contact" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">
                    <span class="contact__icon"><x-icon name="whatsapp" :size="18" /></span>
                    <span>
                        <strong>WhatsApp</strong>
                        <small>Balasan pada jam kerja</small>
                    </span>
                </a>

                <div class="contact">
                    <span class="contact__icon"><x-icon name="map-pin" :size="18" /></span>
                    <span>
                        <strong>{{ config('lapor.rt') }}</strong>
                        <small>{{ config('lapor.kelurahan') }}</small>
                    </span>
                </div>
            </div>
        </section>
    </div>
</div>
