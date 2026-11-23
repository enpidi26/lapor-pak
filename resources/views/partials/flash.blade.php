@if (session('sukses') || session('galat') || $errors->any())
    <div class="flash" aria-live="polite">
        @if (session('sukses'))
            <div class="alert alert--ok" role="status" data-flash>
                <span class="alert__icon" aria-hidden="true"><x-icon name="check" :size="17" /></span>
                <div class="alert__body">{{ session('sukses') }}</div>
                <button type="button" class="alert__close" data-flash-close aria-label="Tutup"><x-icon name="x" :size="15" /></button>
            </div>
        @endif

        @if (session('galat'))
            <div class="alert alert--warn" role="alert" data-flash data-flash-sticky>
                <span class="alert__icon" aria-hidden="true"><x-icon name="alert" :size="17" /></span>
                <div class="alert__body">{{ session('galat') }}</div>
                <button type="button" class="alert__close" data-flash-close aria-label="Tutup"><x-icon name="x" :size="15" /></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert--error" role="alert" data-flash data-flash-sticky>
                <span class="alert__icon" aria-hidden="true"><x-icon name="alert" :size="17" /></span>
                <div class="alert__body">
                    <p class="alert__title">{{ $errors->count() === 1 ? 'Ada isian yang perlu diperbaiki' : $errors->count().' isian perlu diperbaiki' }}</p>
                    <ul>
                        @foreach ($errors->all() as $pesan)
                            <li>{{ $pesan }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="alert__close" data-flash-close aria-label="Tutup"><x-icon name="x" :size="15" /></button>
            </div>
        @endif
    </div>
@endif
