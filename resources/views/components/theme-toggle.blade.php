{{-- resources/views/components/theme-toggle.blade.php --}}

<button
    x-data
    x-init="
        const lightIcon = $refs.light;
        const darkIcon = $refs.dark;

        function setInitialTheme() {
            const savedTheme = localStorage.getItem('theme');

            if (savedTheme === 'dark' || 
               (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                lightIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                darkIcon.classList.remove('hidden');
            }
        }

        setInitialTheme();

        $el.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');

            const isDark = document.documentElement.classList.contains('dark');

            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            lightIcon.classList.toggle('hidden', !isDark);
            darkIcon.classList.toggle('hidden', isDark);
        });
    "
    type="button"
    {{ $attributes->merge([
        'class' => 'relative inline-flex items-center justify-center w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 transition-all duration-300 hover:scale-105'
    ]) }}
>

    <!-- Sun Icon -->
    <svg x-ref="light" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hidden">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
      </svg>

      <!-- Moon Icon -->
      <svg x-ref="dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 hidden">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
      </svg>
      


</button>