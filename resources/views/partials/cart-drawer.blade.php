{{-- Mini-drawer del carrito (slide-in derecha) controlado por Alpine.store('cart') --}}
<div x-data
     x-cloak
     x-show="$store.cart.open"
     @keydown.escape.window="$store.cart.open = false"
     class="fixed inset-0 z-[60]">
    {{-- Backdrop --}}
    <div x-show="$store.cart.open"
         x-transition.opacity.duration.200ms
         @click="$store.cart.open = false"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <aside x-show="$store.cart.open"
           x-transition:enter="transition transform ease-out duration-300"
           x-transition:enter-start="translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition transform ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="translate-x-full"
           @click.stop
           class="absolute right-0 top-0 h-full w-full sm:w-[420px] bg-ink-50 dark:bg-ink-900 shadow-2xl flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-ink-200">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-5 h-5"><path d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 5h13"/><circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/></svg>
                <h2 class="font-display text-lg">Tu carrito</h2>
                <span class="text-xs text-ink-500" x-text="$store.cart.count + ' item' + ($store.cart.count === 1 ? '' : 's')"></span>
            </div>
            <button @click="$store.cart.open = false" aria-label="Cerrar" class="w-9 h-9 rounded-full hover:bg-ink-100 inline-flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Acaba de añadirse --}}
        <template x-if="$store.cart.lastAdded">
            <div class="mx-4 mt-4 bg-ink-900 text-white rounded-2xl p-4 flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg bg-white/10 inline-flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="w-5 h-5"><path d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-ink-300 uppercase tracking-widest">Añadido</p>
                    <p class="text-sm font-medium truncate" x-text="$store.cart.lastAdded.title"></p>
                    <p class="text-xs text-ink-400 truncate" x-text="$store.cart.lastAdded.format_label"></p>
                </div>
            </div>
        </template>

        {{-- Lista de items --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3">
            <template x-if="$store.cart.items.length === 0">
                <div class="text-center text-ink-500 py-16">
                    <p class="text-sm">Tu carrito está vacío.</p>
                    <a href="{{ route('galleries.index') }}" @click="$store.cart.open = false"
                       class="mt-3 inline-block text-ink-900 underline text-sm">Ver galerías</a>
                </div>
            </template>

            <template x-for="item in $store.cart.items" :key="item.key">
                <div class="flex gap-3 bg-white dark:bg-ink-800 border border-ink-200 rounded-xl p-3">
                    <img :src="item.thumbnail" alt="" class="w-16 h-16 object-cover rounded-lg shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-ink-500" x-show="item.item_type === 'gallery'">PACK COMPLETO</p>
                        <p class="text-sm font-medium truncate" x-text="item.item_title"></p>
                        <p class="text-xs text-ink-500 truncate" x-text="item.format_label"></p>
                        <p class="text-xs text-ink-600 mt-0.5">
                            <span x-text="'$' + Number(item.unit_price).toFixed(2)"></span>
                            <span class="text-ink-400"> × </span>
                            <span x-text="item.quantity"></span>
                        </p>
                    </div>
                    <div class="text-right flex flex-col items-end justify-between">
                        <span class="font-semibold text-sm" x-text="'$' + Number(item.line_total).toFixed(2)"></span>
                        <button @click="$store.cart.remove(item.key)" class="text-xs text-ink-400 hover:text-red-600">Quitar</button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer del drawer --}}
        <div class="border-t border-ink-200 px-5 py-4 space-y-3" x-show="$store.cart.items.length > 0">
            <div class="flex items-center justify-between text-sm">
                <span class="text-ink-500">Subtotal</span>
                <span class="font-display text-xl" x-text="'$' + Number($store.cart.subtotal).toFixed(2)"></span>
            </div>
            <a href="{{ route('checkout.show') }}" class="block text-center bg-ink-900 text-white dark:bg-white dark:text-ink-900 py-3 rounded-full font-medium hover:bg-ink-700 dark:hover:bg-ink-200 transition">
                Continuar al checkout
            </a>
            <div class="flex items-center justify-between">
                <a href="{{ route('cart.index') }}" @click="$store.cart.open = false" class="text-sm text-ink-600 hover:text-ink-900">Ver carrito completo</a>
                <button @click="$store.cart.open = false" class="text-sm text-ink-500 hover:text-ink-900">Seguir comprando</button>
            </div>
        </div>
    </aside>
</div>

{{-- x-cloak handler --}}
<style>[x-cloak]{display:none!important}</style>
