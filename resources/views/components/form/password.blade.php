@props([
    'name',
    'label',
    'hint' => null,
    'required' => true,
    'checklist' => false,
    'autocomplete' => 'current-password',
])

@php
    $id = 'f_'.preg_replace('/[^a-z0-9_]/i', '_', $name);
    $error = $errors->first($name);
    $desc = collect([$hint ? $id.'-hint' : null, $error ? $id.'-err' : null])->filter()->implode(' ');
@endphp

<div class="field">
    <label class="field__label" for="{{ $id }}">
        {{ $label }}
        @if ($required)
            <span class="req" aria-hidden="true">*</span>
        @endif
    </label>

    <div class="input-wrap">
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="password"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required @endif
            @if ($desc !== '') aria-describedby="{{ $desc }}" @endif
            @if ($error) aria-invalid="true" @endif
            {{ $checklist ? 'data-pw-check' : '' }}
            {{ $attributes->merge(['class' => 'input']) }}
        >

        <button type="button" class="pw-toggle" data-pw-toggle="{{ $id }}" aria-label="Tampilkan password" aria-pressed="false">
            <span class="pw-toggle__on"><x-icon name="eye" :size="18" /></span>
            <span class="pw-toggle__off"><x-icon name="eye-off" :size="18" /></span>
        </button>
    </div>

    @if ($hint)
        <p class="field__hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif

    @if ($checklist)
        <div class="pw-reqs">
            <span class="pw-req" data-req="length">
                <x-icon name="check" :size="14" />
                8 karakter
            </span>
            <span class="pw-req" data-req="letter">
                <x-icon name="check" :size="14" />
                huruf
            </span>
            <span class="pw-req" data-req="number">
                <x-icon name="check" :size="14" />
                angka
            </span>
        </div>
    @endif

    @if ($error)
        <p class="field__error" id="{{ $id }}-err" role="alert">
            <x-icon name="alert-circle" :size="15" />
            {{ $error }}
        </p>
    @endif
</div>
