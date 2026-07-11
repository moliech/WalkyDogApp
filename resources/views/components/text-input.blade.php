@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition duration-200 outline-none text-brand-dark bg-white shadow-sm']) }}>