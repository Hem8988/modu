@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'px-3 py-2 sm:py-2.5 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm font-size[16px] min-h-[40px] sm:min-h-[44px] w-full -webkit-appearance-none appearance-none']) }}>
