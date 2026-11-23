@props([
    'name' => 'foto',
    'label' => 'Foto',
    'hint' => null,
    'required' => false,
    'currentUrl' => null,
    'maxKb' => null,
])

@php
    $id = 'f_'.preg_replace('/[^a-z0-9_]/i', '_', $name);
    $error = $errors->first($name);
    $maxKb = $maxKb ?? config('lapor.foto_maks_kb');
    $labelHint = $hint ?? 'JPG, PNG, atau WebP · maksimal '.round($maxKb / 1024).' MB';
    $desc = trim(($id.'-hint').' '.($error ? $id.'-err' : ''));
@endphp

<div class="field">
    <span class="field__label">
        {{ $label }}
        @if ($required)
            <span class="req" aria-hidden="true">*</span>
        @else
            <span class="opt">opsional</span>
        @endif
    </span>

    <input type="hidden" name="hapus_foto" value="0" data-hapus-foto>

    <div class="upload" data-upload data-max-kb="{{ $maxKb }}">
        <input
            type="file"
            id="{{ $id }}"
            name="{{ $name }}"
            class="upload__input"
            accept="image/jpeg,image/png,image/webp"
            data-upload-input
            aria-describedby="{{ $desc }}"
            @if ($error) aria-invalid="true" @endif
        >

        <div class="upload__row">
            <span class="upload__icon" aria-hidden="true">
                <x-icon name="camera" :size="21" />
            </span>
            <div class="upload__text">
                <label for="{{ $id }}" class="upload__title">Pilih foto</label>
                <p class="upload__hint" id="{{ $id }}-hint">{{ $labelHint }}</p>
            </div>
            <label for="{{ $id }}" class="btn btn--ghost btn--sm">
                <x-icon name="upload" :size="15" />
                Jelajahi
            </label>
        </div>

        <div class="upload__preview" data-upload-preview @if (! $currentUrl) hidden @endif>
            <img src="{{ $currentUrl }}" alt="Pratinjau foto" data-upload-img>
            <button type="button" class="upload__remove" data-upload-remove>
                <x-icon name="x" :size="13" />
                Hapus
            </button>
        </div>

        <p class="field__error" data-upload-error role="alert" hidden></p>
    </div>

    @if ($error)
        <p class="field__error" id="{{ $id }}-err" role="alert">
            <x-icon name="alert-circle" :size="15" />
            {{ $error }}
        </p>
    @endif
</div>
