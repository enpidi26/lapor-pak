@props(['name' => 'circle', 'size' => 20])

@php
    // Petakan nama ikon lama ke nama Lucide; nama Lucide langsung diteruskan.
    $map = [
        'logo' => 'messages-square',
        'chart' => 'chart-column',
        'home' => 'house',
        'list' => 'list',
        'plus' => 'plus',
        'user' => 'user-round',
        'help' => 'circle-help',
        'logout' => 'log-out',
        'menu' => 'menu',
        'search' => 'search',
        'x' => 'x',
        'check' => 'check',
        'clock' => 'clock-3',
        'image' => 'image',
        'edit' => 'square-pen',
        'trash' => 'trash-2',
        'lock' => 'lock',
        'eye' => 'eye',
        'eye-off' => 'eye-off',
        'camera' => 'camera',
        'upload' => 'upload',
        'alert' => 'triangle-alert',
        'alert-circle' => 'circle-alert',
        'info' => 'info',
        'message' => 'message-square',
        'map-pin' => 'map-pin',
        'arrow-right' => 'arrow-right',
        'arrow-left' => 'arrow-left',
        'arrow-up-right' => 'arrow-up-right',
        'chevron-right' => 'chevron-right',
        'chevron-left' => 'chevron-left',
        'chevron-down' => 'chevron-down',
        'refresh' => 'refresh-cw',
        'file' => 'file-text',
        'trend-up' => 'trending-up',
        'trend-down' => 'trending-down',
        'shield' => 'shield-check',
        'droplet' => 'droplets',
        'lamp' => 'lightbulb',
        'road' => 'construction',
        'dots' => 'ellipsis',
        'sparkles' => 'sparkles',
        'user-check' => 'user-check',
        'send' => 'send',
        'users' => 'users',
        'calendar' => 'calendar-days',
        'filter' => 'list-filter',
        'phone' => 'phone',
        'mail' => 'mail',
        'inbox' => 'inbox',
    ];

    $lucide = $map[$name] ?? $name;
@endphp

@if ($name === 'whatsapp')
    <svg {{ $attributes->merge(['class' => 'icon', 'width' => $size, 'height' => $size, 'viewBox' => '0 0 24 24', 'fill' => 'currentColor', 'aria-hidden' => 'true', 'focusable' => 'false']) }}>
        <path d="M12.04 2c-5.46 0-9.9 4.44-9.9 9.9 0 1.75.46 3.45 1.32 4.95L2.05 22l5.3-1.38a9.86 9.86 0 0 0 4.69 1.19h.01c5.46 0 9.9-4.44 9.9-9.9 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 1.67c2.2 0 4.27.86 5.83 2.42a8.2 8.2 0 0 1 2.42 5.83c0 4.54-3.7 8.23-8.25 8.23a8.2 8.2 0 0 1-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24zm-3.2 4.32c-.15 0-.4.06-.6.28-.21.22-.8.78-.8 1.9s.82 2.2.93 2.36c.11.15 1.6 2.55 3.95 3.47 1.96.77 2.36.62 2.78.58.43-.04 1.38-.56 1.57-1.1.2-.55.2-1.02.14-1.12-.06-.1-.21-.16-.43-.27-.22-.11-1.38-.68-1.6-.76-.21-.08-.37-.11-.52.11-.15.22-.58.76-.71.92-.13.15-.26.17-.48.06-.22-.11-.95-.35-1.8-1.11-.67-.6-1.12-1.33-1.25-1.55-.13-.22-.01-.34.1-.45.1-.1.22-.26.33-.39.11-.13.15-.22.22-.37.08-.15.04-.28-.02-.39-.06-.11-.5-1.25-.7-1.71-.18-.44-.37-.38-.52-.39h-.44z"/>
    </svg>
@else
    <i data-lucide="{{ $lucide }}" {{ $attributes->merge(['class' => 'icon', 'width' => $size, 'height' => $size, 'aria-hidden' => 'true']) }}></i>
@endif
