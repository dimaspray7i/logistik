<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white font-medium text-sm rounded-btn shadow-sm hover:bg-[#c23a33] focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 active:bg-[#a8312b] disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150']) }}>
    {{ $slot }}
</button>

