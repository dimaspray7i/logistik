<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white font-medium text-sm rounded-btn shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:ring-offset-2 active:bg-red-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150']) }}>
    {{ $slot }}
</button>

