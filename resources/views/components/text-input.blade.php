@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-btn border-gray-200 text-sm text-gray-900 bg-white shadow-sm placeholder:text-gray-400 focus:border-primary focus:ring-2 focus:ring-primary/20 disabled:bg-gray-50 disabled:text-gray-500 transition-all duration-150']) }}>

