@props([
    'sections' => [],
])

<aside
    class="hidden lg:block lg:w-64 flex-shrink-0"
    x-data="{
        activeSection: '{{ count($sections) > 0 ? $sections[0]['id'] : '' }}',
        observer: null,
        init() {
            this.setupIntersectionObserver();
        },
        setupIntersectionObserver() {
            const options = {
                rootMargin: '-20% 0px -70% 0px',
                threshold: 0
            };

            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeSection = entry.target.id;
                    }
                });
            }, options);

            document.querySelectorAll('section[id]').forEach(section => {
                this.observer.observe(section);
            });
        }
    }"
    {{ $attributes }}
>
    <div class="sticky top-8">
        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-4">On this page</p>
        <nav class="">
            @foreach($sections as $section)
                <a
                    href="#{{ $section['id'] }}"
                    @click.prevent="document.getElementById('{{ $section['id'] }}').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                    :class="activeSection === '{{ $section['id'] }}' ? 'text-primary-600 dark:text-primary-400 font-medium border-l-2 border-primary-600 dark:border-primary-400' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 border-l-2 border-gray-200 dark:border-white/20'"
                    class="block pl-4 py-2 text-sm transition-colors"
                >
                    {{ $section['title'] }}
                </a>
            @endforeach
        </nav>
    </div>
</aside>
