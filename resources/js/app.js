import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    /* ============ Tema dark/light ============ */
    Alpine.store('theme', {
        isDark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.isDark = !this.isDark;
            document.documentElement.classList.toggle('dark', this.isDark);
            try {
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            } catch (e) {}
        },
    });

    /* ============ Carrito ============ */
    Alpine.store('cart', {
        open: false,
        count: window.__initialCart?.count ?? 0,
        subtotal: window.__initialCart?.subtotal ?? 0,
        items: [],
        lastAdded: null,
        loading: false,

        csrf() {
            return document.querySelector('meta[name=csrf-token]')?.content || '';
        },

        async post(url, formData) {
            this.loading = true;
            try {
                if (!(formData instanceof FormData)) {
                    const fd = new FormData();
                    for (const k in formData) fd.append(k, formData[k]);
                    formData = fd;
                }
                if (!formData.has('_token')) formData.append('_token', this.csrf());

                const res = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const json = await res.json();
                this.applySnapshot(json);
                if (json.addedItem) {
                    this.lastAdded = json.addedItem;
                    this.open = true;
                }
                return json;
            } catch (e) {
                console.error('Cart error', e);
                alert('No pudimos añadir al carrito. Intenta de nuevo.');
            } finally {
                this.loading = false;
            }
        },

        applySnapshot(json) {
            if (typeof json.count !== 'undefined') this.count = json.count;
            if (typeof json.subtotal !== 'undefined') this.subtotal = json.subtotal;
            if (Array.isArray(json.items)) this.items = json.items;
        },

        addPhoto(url, payload) {
            return this.post(url, payload);
        },
        addGallery(url) {
            return this.post(url, {});
        },

        async remove(key) {
            const fd = new FormData();
            fd.append('_method', 'DELETE');
            fd.append('_token', this.csrf());
            const res = await fetch(`/carrito/eliminar/${encodeURIComponent(key)}`, {
                method: 'POST',
                body: fd,
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (res.ok) this.applySnapshot(await res.json());
        },

        async refresh() {
            try {
                const res = await fetch('/carrito/resumen', { headers: { 'Accept': 'application/json' } });
                if (res.ok) this.applySnapshot(await res.json());
            } catch (e) {}
        },

        money(n) {
            return '$' + Number(n || 0).toFixed(2);
        },
    });
});

/* ============ Protección de imágenes ============ */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.photo-protected img').forEach((img) => {
        img.addEventListener('contextmenu', (e) => e.preventDefault());
        img.addEventListener('dragstart', (e) => e.preventDefault());
        img.setAttribute('draggable', 'false');
    });
});

window.Alpine = Alpine;
Alpine.start();
