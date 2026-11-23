@php
    $icons = ['success' => "\u{2713}", 'info' => 'i', 'warning' => '!', 'error' => "\u{2715}"];
    $kind = isset($icons[$type]) ? $type : 'info';
@endphp
<div class="notification notification-{{ $kind }}" role="{{ $kind === 'error' ? 'alert' : 'status' }}">
    <span class="notification-icon" aria-hidden="true">{{ $icons[$kind] }}</span>
    <span class="notification-text">{{ $message }}</span>
    <button type="button" class="notification-close" aria-label="Dismiss message">&times;</button>
</div>
