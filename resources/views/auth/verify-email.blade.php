<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Verifikasi Email</h2>
        <p class="text-sm text-gray-500 mt-1">Terima kasih telah mendaftar!</p>
    </div>

    <div class="mb-6 text-sm text-gray-600 bg-gray-50 p-4 rounded-xl border border-gray-200">
        Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkannya kembali.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-sm font-medium text-green-800">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
            </p>
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-[#D6453D] hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D6453D] transition-all duration-200">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-200">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>   