<button
    {{ $attributes->merge(['type' => 'submit', 'disabled' => false, 'class' => 'inline-flex justify-center items-center px-4 py-2 bg-pink-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 active:bg-pink-900 focus:outline-none focus:border-pink-900 focus:ring focus:ring-pink-300 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
