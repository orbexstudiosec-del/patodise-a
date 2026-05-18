@extends('admin.layout')

@section('title', 'Dashboard')
@section('heading', 'Resumen')

@section('content')
    {{-- KPIs de tienda --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach ([
            ['Fotografías', $stats['photos']],
            ['Publicadas', $stats['published']],
            ['Pedidos', $stats['orders']],
            ['Pendientes', $stats['pending_orders']],
            ['Ingresos', '$' . number_format($stats['revenue'], 2)],
        ] as [$label, $value])
            <div class="bg-white border border-ink-200 rounded-2xl p-5">
                <p class="text-xs text-ink-500 uppercase tracking-wide">{{ $label }}</p>
                <p class="font-display text-3xl mt-1">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- ============== ANALYTICS ============== --}}
    <section class="mt-8">
        <div class="flex items-end justify-between mb-3">
            <div>
                <p class="text-xs uppercase tracking-widest text-ink-500">Analítica del sitio</p>
                <h2 class="font-display text-2xl">Visitas a la web</h2>
            </div>
            <span class="text-xs text-ink-500">Excluye admin y peticiones AJAX</span>
        </div>

        {{-- KPIs de visitas --}}
        <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach ([
                ['Hoy', number_format($analytics['today'])],
                ['Últimos 7 días', number_format($analytics['last_7d'])],
                ['Últimos 30 días', number_format($analytics['last_30d'])],
                ['Únicos 30 días', number_format($analytics['unique_30d'])],
                ['Total histórico', number_format($analytics['total'])],
            ] as [$label, $value])
                <div class="bg-white border border-ink-200 rounded-2xl p-5">
                    <p class="text-xs text-ink-500 uppercase tracking-wide">{{ $label }}</p>
                    <p class="font-display text-3xl mt-1">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        {{-- Chart 14 días --}}
        <div class="mt-6 bg-white border border-ink-200 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">Últimos 14 días</h3>
                <div class="flex items-center gap-4 text-xs">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-ink-900"></span> Visitas</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-ink-400"></span> Únicos</span>
                </div>
            </div>
            <div class="overflow-x-auto -mx-1 px-1"><div class="flex items-end gap-2 h-48 min-w-[560px]" x-data="{ hover: null }">
                @foreach ($chart as $i => $d)
                    @php
                        $hVisits = max(2, (int) round($d['visits'] / $chartMax * 160));
                        $hUnique = max(0, (int) round($d['unique'] / $chartMax * 160));
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-1 group relative" @mouseenter="hover = {{ $i }}" @mouseleave="hover = null">
                        <div class="w-full flex items-end justify-center gap-0.5 h-44">
                            <div class="w-1/2 bg-ink-900 rounded-t" style="height: {{ $hVisits }}px"></div>
                            <div class="w-1/2 bg-ink-400 rounded-t" style="height: {{ $hUnique }}px"></div>
                        </div>
                        <p class="text-[10px] text-ink-500 leading-none">{{ $d['label'] }}</p>
                        {{-- Tooltip --}}
                        <div x-show="hover === {{ $i }}" x-transition.opacity
                             class="absolute bottom-full mb-2 bg-ink-900 text-white text-xs px-2 py-1 rounded whitespace-nowrap z-10 pointer-events-none">
                            <p class="font-medium">{{ $d['date'] }}</p>
                            <p>Visitas: <span class="font-semibold">{{ $d['visits'] }}</span></p>
                            <p>Únicos: <span class="font-semibold">{{ $d['unique'] }}</span></p>
                        </div>
                    </div>
                @endforeach
            </div></div>
        </div>

        {{-- Top páginas + referers --}}
        <div class="mt-6 grid lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 bg-white border border-ink-200 rounded-2xl">
                <div class="px-5 py-4 border-b border-ink-200">
                    <h3 class="font-semibold">Páginas más visitadas <span class="text-xs text-ink-500 ml-1">(últimos 30 días)</span></h3>
                </div>
                @if ($topPages->isEmpty())
                    <p class="px-5 py-8 text-center text-ink-500 text-sm">Sin datos aún. Visita el sitio público para empezar a recolectar.</p>
                @else
                    <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[520px]">
                        <thead class="bg-ink-50 text-ink-500 text-left">
                            <tr>
                                <th class="px-5 py-2.5">Ruta</th>
                                <th class="px-5 py-2.5 text-right">Visitas</th>
                                <th class="px-5 py-2.5 text-right">Únicos</th>
                                <th class="px-5 py-2.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink-100">
                            @foreach ($topPages as $p)
                                @php $pct = $analytics['last_30d'] > 0 ? round($p->visits / $analytics['last_30d'] * 100, 1) : 0; @endphp
                                <tr class="hover:bg-ink-50">
                                    <td class="px-5 py-2.5 font-mono text-xs"><a href="{{ $p->path }}" target="_blank" class="hover:text-ink-900 text-ink-700">{{ $p->path }}</a></td>
                                    <td class="px-5 py-2.5 text-right font-semibold">{{ number_format($p->visits) }}</td>
                                    <td class="px-5 py-2.5 text-right text-ink-500">{{ number_format($p->uniques) }}</td>
                                    <td class="px-5 py-2.5 w-32">
                                        <div class="h-1.5 bg-ink-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-ink-900" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] text-ink-500">{{ $pct }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-ink-200 rounded-2xl">
                <div class="px-5 py-4 border-b border-ink-200">
                    <h3 class="font-semibold">De dónde llegan</h3>
                </div>
                @if ($topReferers->isEmpty())
                    <p class="px-5 py-8 text-center text-ink-500 text-sm">Sin referers registrados aún.</p>
                @else
                    <ul class="divide-y divide-ink-100 text-sm">
                        @foreach ($topReferers as $r)
                            <li class="px-5 py-3 flex items-start justify-between gap-3">
                                <a href="{{ $r->referer }}" target="_blank" rel="noopener" class="truncate text-ink-700 hover:text-ink-900 text-xs" title="{{ $r->referer }}">{{ \Illuminate\Support\Str::limit(parse_url($r->referer, PHP_URL_HOST) ?: $r->referer, 28) }}</a>
                                <span class="font-semibold">{{ number_format($r->visits) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Visitas recientes --}}
        <div class="mt-6 bg-white border border-ink-200 rounded-2xl">
            <div class="px-5 py-4 border-b border-ink-200">
                <h3 class="font-semibold">Actividad reciente</h3>
            </div>
            @if ($recentVisits->isEmpty())
                <p class="px-5 py-8 text-center text-ink-500 text-sm">Sin visitas aún.</p>
            @else
                <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[560px]">
                    <thead class="bg-ink-50 text-ink-500 text-left">
                        <tr>
                            <th class="px-5 py-2.5">Fecha</th>
                            <th class="px-5 py-2.5">Ruta</th>
                            <th class="px-5 py-2.5">Usuario</th>
                            <th class="px-5 py-2.5">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @foreach ($recentVisits as $v)
                            <tr class="hover:bg-ink-50">
                                <td class="px-5 py-2.5 text-xs text-ink-500">{{ $v->created_at?->format('d/m H:i') }}</td>
                                <td class="px-5 py-2.5 font-mono text-xs">{{ $v->path }}</td>
                                <td class="px-5 py-2.5 text-xs">{{ $v->user?->name ?? '—' }}</td>
                                <td class="px-5 py-2.5 text-xs text-ink-500">{{ $v->ip }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </div>
    </section>

    {{-- Pedidos recientes --}}
    <div class="mt-8 bg-white border border-ink-200 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-ink-200 flex items-center justify-between">
            <h2 class="font-semibold">Pedidos recientes</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-ink-900">Ver todos →</a>
        </div>
        <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[560px]">
            <thead class="bg-ink-50 text-ink-500 text-left">
                <tr>
                    <th class="px-5 py-3">N.º</th>
                    <th class="px-5 py-3">Cliente</th>
                    <th class="px-5 py-3">Items</th>
                    <th class="px-5 py-3">Total</th>
                    <th class="px-5 py-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($latestOrders as $order)
                    <tr class="hover:bg-ink-50">
                        <td class="px-5 py-3 font-mono"><a href="{{ route('admin.orders.show', $order) }}" class="text-ink-900">{{ $order->order_number }}</a></td>
                        <td class="px-5 py-3">{{ $order->customer_name }}</td>
                        <td class="px-5 py-3">{{ $order->items->sum('quantity') }}</td>
                        <td class="px-5 py-3">${{ number_format($order->total, 2) }}</td>
                        <td class="px-5 py-3"><span class="text-xs bg-ink-100 px-2 py-1 rounded-full">{{ $order->status_label }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-ink-500">No hay pedidos todavía.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
@endsection
