@props([
    'status' => 'DRAFT',
    'label' => null,
])

@php
    $statusKey = is_object($status) && isset($status->value) ? $status->value : strtoupper((string) $status);

    $badgeClass = match($statusKey) {
        // Blue Statuses
        'READY', 'SIAP DIKIRIM', 'SIAP KIRIM' => 'badge-ready',
        'IN_TRANSIT', 'DALAM PERJALANAN', 'DALAM TRANSIT', 'PROCESSING', 'DIPROSES', 'IN_USE', 'SEDANG DIGUNAKAN' => 'badge-in-transit',

        // Green Statuses
        'DELIVERED', 'TERKIRIM', 'COMPLETED', 'SELESAI', 'AVAILABLE', 'TERSEDIA', 'ACTIVE', 'AKTIF' => 'badge-delivered',
        'ARRIVED', 'TIBA DI TUJUAN', 'TIBA' => 'badge-arrived',

        // Orange/Amber Statuses
        'DELAYED', 'TERTUNDA', 'MAINTENANCE', 'PERAWATAN' => 'badge-delayed',
        'PENDING', 'MENUNGGU' => 'badge-pending',

        // Red Statuses
        'CANCELLED', 'DIBATALKAN', 'FAILED', 'GAGAL' => 'badge-cancelled',

        // Gray Statuses
        'DRAFT', 'DRAF', 'INACTIVE', 'NON-AKTIF', 'NON AKTIF' => 'badge-draft',

        default => 'badge-draft',
    };

    $labelText = $label ?? (is_object($status) && method_exists($status, 'label') ? $status->label() : match($statusKey) {
        'DRAFT', 'DRAF' => 'Draf',
        'READY', 'SIAP KIRIM', 'SIAP DIKIRIM' => 'Siap Dikirim',
        'IN_TRANSIT', 'DALAM PERJALANAN', 'DALAM TRANSIT' => 'Dalam Perjalanan',
        'ARRIVED', 'TIBA' => 'Tiba di Tujuan',
        'DELIVERED', 'TERKIRIM' => 'Terkirim',
        'DELAYED', 'TERTUNDA' => 'Tertunda',
        'CANCELLED', 'DIBATALKAN' => 'Dibatalkan',
        'PENDING', 'MENUNGGU' => 'Menunggu',
        'PROCESSING', 'DIPROSES' => 'Diproses',
        'COMPLETED', 'SELESAI' => 'Selesai',
        'AVAILABLE', 'TERSEDIA' => 'Tersedia',
        'IN_USE', 'SEDANG DIGUNAKAN' => 'Sedang Digunakan',
        'MAINTENANCE', 'PERAWATAN' => 'Perawatan',
        'ACTIVE', 'AKTIF' => 'Aktif',
        'INACTIVE', 'NON-AKTIF' => 'Non-Aktif',
        'FAILED', 'GAGAL' => 'Gagal',
        default => ucfirst(strtolower($statusKey)),
    });
@endphp

<span {{ $attributes->merge(['class' => "badge-pill {$badgeClass}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0 opacity-90"></span>
    <span>{{ $labelText }}</span>
</span>
