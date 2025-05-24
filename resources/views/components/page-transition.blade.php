@props(['name' => 'default'])

<div x-data="{ 
    show: false,
    name: '{{ $name }}'
}"
    x-init="
        () => {
            setTimeout(() => { show = true }, 50);
            $watch('show', value => {
                if (!value) {
                    setTimeout(() => { $el.remove() }, 500);
                }
            });
        }
    "
    x-show="show"
    x-transition:enter="transform transition-transform duration-500"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transform transition-transform duration-500"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="-translate-y-full"
    class="min-h-screen"
    {{ $attributes }}>
    {{ $slot }}
</div>

<script>
document.addEventListener('alpine:init', () => {
    window.Alpine.store('navigation', {
        current: null,
        async navigate(path, direction = 'up') {
            const response = await fetch(path);
            const html = await response.text();
            const temp = document.createElement('div');
            temp.innerHTML = html;
            
            const content = temp.querySelector('#page-content').innerHTML;
            const currentPage = document.querySelector('#page-content');
            
            // Create new page element
            const newPage = document.createElement('div');
            newPage.innerHTML = content;
            newPage.style.position = 'fixed';
            newPage.style.top = '0';
            newPage.style.left = '0';
            newPage.style.width = '100%';
            newPage.style.zIndex = '50';
            newPage.style.backgroundColor = 'white';
            
            // Set initial transform
            newPage.style.transform = direction === 'up' ? 'translateY(100%)' : 'translateY(-100%)';
            document.body.appendChild(newPage);
            
            // Trigger animation
            setTimeout(() => {
                newPage.style.transition = 'transform 500ms ease-in-out';
                newPage.style.transform = 'translateY(0)';
                
                if (currentPage) {
                    currentPage.style.transition = 'transform 500ms ease-in-out';
                    currentPage.style.transform = direction === 'up' ? 'translateY(-100%)' : 'translateY(100%)';
                }
            }, 50);
            
            // Clean up after animation
            setTimeout(() => {
                if (currentPage) currentPage.remove();
                newPage.style.position = 'static';
                newPage.id = 'page-content';
            }, 500);
            
            // Update browser history
            window.history.pushState({}, '', path);
        }
    });
});

// Handle browser back/forward buttons
window.addEventListener('popstate', (event) => {
    const direction = event.state?.direction || 'down';
    Alpine.store('navigation').navigate(window.location.pathname, direction);
});
</script> 