@props([
    'document',
    'showRoute',          // route untuk view/preview (required)
    'deleteRoute' => null, // route untuk hapus (null = tidak tampilkan tombol hapus)
    'canDelete' => false, // apakah tombol hapus tampil
])

@php
    // Determine file icon type
    $mime = $document->mime_type ?? '';
    $ext  = strtolower(pathinfo($document->file_name ?? '', PATHINFO_EXTENSION));
    $isPdf   = $mime === 'application/pdf' || $ext === 'pdf';
    $isImage = str_starts_with($mime, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

    // Size display
    $sizeKb  = number_format(($document->file_size ?? 0) / 1024, 0);

    // Type label
    $typeLabel = is_object($document->type) && method_exists($document->type, 'label')
        ? $document->type->label()
        : ($document->type ?? '—');

    // Date label
    $uploadedAt = $document->created_at
        ? $document->created_at->translatedFormat('d M Y')
        : null;

    // Extension badge color
    $extBadgeClass = $isPdf
        ? 'bg-red-50 text-red-700 border-red-200'
        : ($isImage
            ? 'bg-blue-50 text-blue-700 border-blue-200'
            : 'bg-gray-100 text-gray-600 border-gray-200');
@endphp

<div class="group flex flex-col sm:flex-row sm:items-center gap-3 p-4 rounded-xl border border-gray-100 bg-gray-50/60 hover:bg-white hover:border-primary/20 hover:shadow-sm transition-all duration-200">

    {{-- File Icon + Info --}}
    <div class="flex items-start gap-3 flex-1 min-w-0">
        <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center {{ $isPdf ? 'bg-red-100 text-red-600' : ($isImage ? 'bg-blue-100 text-blue-600' : 'bg-primary/10 text-primary') }} border {{ $isPdf ? 'border-red-200' : ($isImage ? 'border-blue-200' : 'border-primary/10') }}">
            @if ($isPdf)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h1m4 0h1m-6 3h6"/>
                </svg>
            @elseif ($isImage)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-gray-900 truncate leading-tight" title="{{ $document->file_name }}">
                {{ $document->file_name }}
            </p>
            <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-1">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $extBadgeClass }}">
                    {{ $typeLabel }}
                </span>
                <span class="text-[11px] text-gray-400 font-mono uppercase">{{ $ext ?: '—' }}</span>
                <span class="text-[11px] text-gray-500">{{ $sizeKb }} KB</span>
                @if ($uploadedAt)
                    <span class="text-[11px] text-gray-400">&middot; {{ $uploadedAt }}</span>
                @endif
            </div>
            @if (!empty($document->title) && $document->title !== $document->file_name)
                <p class="text-[11px] text-gray-500 mt-0.5 truncate">{{ $document->title }}</p>
            @endif
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center gap-1.5 shrink-0 flex-wrap">
        <a href="{{ $showRoute }}"
           target="_blank"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition-all duration-150"
           title="Lihat / Preview {{ $document->file_name }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>Lihat</span>
        </a>

        <a href="{{ $showRoute }}?download=1"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all duration-150"
           title="Unduh {{ $document->file_name }}">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            <span class="hidden sm:inline">Unduh</span>
        </a>

        @if ($canDelete && $deleteRoute)
            <form action="{{ $deleteRoute }}" method="POST"
                  onsubmit="return confirm('Hapus dokumen \'{{ addslashes($document->file_name) }}\'? Tindakan ini tidak dapat dibatalkan.');"
                  class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 hover:border-red-300 transition-all duration-150"
                        title="Hapus {{ $document->file_name }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span class="hidden sm:inline">Hapus</span>
                </button>
            </form>
        @endif
    </div>

</div>
