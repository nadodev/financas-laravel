import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Add Alpine data and event handlers
document.addEventListener('alpine:init', () => {
    Alpine.data('layout', () => ({
        sidebarOpen: window.innerWidth >= 768,
        
        init() {
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    this.sidebarOpen = true;
                } else {
                    this.sidebarOpen = false;
                }
            });
        }
    }));
});

Alpine.start();
