@php
    $user = auth()->user();
    $admin = $user->isAdmin();
@endphp

<aside class="sidebar" id="sidebar" aria-label="Navigasi utama">
    <div class="sidebar__head">
        <x-logo :href="route($user->dashboardRoute())" />
        <button type="button" class="sidebar__close" data-sidebar-close aria-label="Tutup navigasi">
            <x-icon name="x" :size="20" />
        </button>
    </div>

    <div class="sidebar__nav">
        <p class="sidebar__label">Menu {{ $admin ? 'Administrator' : 'Warga' }}</p>

        <nav class="nav-list" aria-label="Navigasi utama">
            @if ($admin)
                <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}" @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                    <x-icon name="layout-dashboard" />
                    Dashboard
                </a>
                <a class="nav-item {{ request()->routeIs('admin.laporan.*') ? 'is-active' : '' }}" href="{{ route('admin.laporan.index') }}" @if(request()->routeIs('admin.laporan.*')) aria-current="page" @endif>
                    <x-icon name="files" />
                    Semua Pengaduan
                    @if ($jumlahMenunggu > 0)
                        <span class="nav-item__badge">{{ $jumlahMenunggu }}</span>
                    @endif
                </a>
            @else
                <a class="nav-item {{ request()->routeIs('beranda') ? 'is-active' : '' }}" href="{{ route('beranda') }}" @if(request()->routeIs('beranda')) aria-current="page" @endif>
                    <x-icon name="layout-dashboard" />
                    Dashboard
                </a>
                <a class="nav-item {{ request()->routeIs('laporan.index', 'laporan.show', 'laporan.edit') ? 'is-active' : '' }}" href="{{ route('laporan.index') }}" @if(request()->routeIs('laporan.index', 'laporan.show', 'laporan.edit')) aria-current="page" @endif>
                    <x-icon name="files" />
                    Pengaduan Saya
                </a>
                <a class="nav-item {{ request()->routeIs('laporan.create') ? 'is-active' : '' }}" href="{{ route('laporan.create') }}" @if(request()->routeIs('laporan.create')) aria-current="page" @endif>
                    <x-icon name="circle-plus" />
                    Buat Pengaduan
                </a>
                <a class="nav-item {{ request()->routeIs('profil.*') ? 'is-active' : '' }}" href="{{ route('profil.edit') }}" @if(request()->routeIs('profil.*')) aria-current="page" @endif>
                    <x-icon name="user-round" />
                    Profil Saya
                </a>
            @endif

            <a class="nav-item {{ request()->routeIs('bantuan') ? 'is-active' : '' }}" href="{{ route('bantuan') }}" @if(request()->routeIs('bantuan')) aria-current="page" @endif>
                <x-icon name="circle-help" />
                Pusat Bantuan
            </a>
        </nav>
    </div>

    <div class="sidebar__promo">
        <span class="sidebar__promo-icon" aria-hidden="true">
            <x-icon name="heart-handshake" />
        </span>
        <h3>Setiap suara berarti.</h3>
        <p>Bersama wujudkan lingkungan yang nyaman untuk semua.</p>
    </div>

    <div class="sidebar__foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar__logout">
                <x-icon name="log-out" />
                Keluar
            </button>
        </form>
    </div>
</aside>
