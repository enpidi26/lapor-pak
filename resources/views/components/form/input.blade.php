@props([
    'name',
    'label',
    'hint' => null,
    'required' => false,
    'type' => 'text',
    'value' => null,
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

    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        @if ($desc !== '') aria-describedby="{{ $desc }}" @endif
        @if ($error) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => 'input']) }}
    >

    @if ($hint)
        <p class="field__hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="field__error" id="{{ $id }}-err" role="alert">
            <x-icon name="alert-circle" :size="15" />
            {{ $error }}
        </p>
    @endif
</div>
