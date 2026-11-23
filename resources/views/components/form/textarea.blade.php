@props([
    'name',
    'label',
    'hint' => null,
    'required' => false,
    'rows' => 5,
    'maxlength' => 2000,
    'value' => null,
])

@php
    $id = 'f_'.preg_replace('/[^a-z0-9_]/i', '_', $name);
    $error = $errors->first($name);
    $desc = collect([$hint ? $id.'-hint' : null, $error ? $id.'-err' : null])->filter()->implode(' ');
    $isi = old($name, $value);
@endphp

<div class="field">
    <label class="field__label" for="{{ $id }}">
        {{ $label }}
        @if ($required)
            <span class="req" aria-hidden="true">*</span>
        @else
            <span class="opt">opsional</span>
        @endif
    </label>

    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        maxlength="{{ $maxlength }}"
        @if ($required) required @endif
        @if ($desc !== '') aria-describedby="{{ $desc }}" @endif
        @if ($error) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => 'textarea']) }}
    >{{ $isi }}</textarea>

    <div class="field__note-row">
        @if ($hint)
            <p class="field__hint" id="{{ $id }}-hint">{{ $hint }}</p>
        @else
            <span></span>
        @endif
        <span class="counter" data-counter-for="{{ $id }}">
            <span data-counter-value>{{ mb_strlen((string) $isi) }}</span>/{{ $maxlength }}
        </span>
    </div>

    @if ($error)
        <p class="field__error" id="{{ $id }}-err" role="alert">
            <x-icon name="alert-circle" :size="15" />
            {{ $error }}
        </p>
    @endif
</div>
