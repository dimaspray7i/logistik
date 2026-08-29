<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-200">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Informasi Pendaftaran</h2>
        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
            Logistik CRM adalah sistem internal untuk manajemen operasional logistik.
        </p>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="text-sm text-amber-800">
                <p class="font-semibold mb-1">Pendaftaran Tidak Dibuka untuk Umum</p>
                <p class="leading-relaxed">Akun Customer dibuat oleh Administrator setelah proses verifikasi dan pengikatan data perusahaan Anda. Hubungi tim kami untuk mendapatkan akses.</p>
            </div>
        </div>
    </div>

    <div class="space-y-3">
        <a href="{{ route('login') }}" 
           class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-[#D6453D] hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D6453D] transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Masuk ke Sistem
        </a>
        <a href="{{ url('/') }}" 
           class="w-full flex items-center justify-center gap-2 py-3 px-4 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
            Kembali ke Beranda
        </a>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-100 text-center">
        <p class="text-xs text-gray-500">
            Sudah memiliki akun yang dibuat Admin?
            <a href="{{ route('login') }}" class="font-semibold text-[#D6453D] hover:text-red-700">Masuk di sini</a>
        </p>
    </div>
</x-guest-layout>