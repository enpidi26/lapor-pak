@props(['icon' => 'inbox', 'title', 'text'])

<div class="empty">
    <span class="empty__icon" aria-hidden="true">
        <x-icon :name="$icon" :size="30" />
    </span>
    <h3>{{ $title }}</h3>
    <p>{{ $text }}</p>
    {{ $slot }}
</div>
