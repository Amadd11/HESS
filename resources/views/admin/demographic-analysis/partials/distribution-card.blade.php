@props([
    'data' => [
        'title' => '',
        'subtitle' => '',
        'theme_classes' => ['bg' => 'bg-primary-50', 'text' => 'text-primary-700', 'border' => 'border-primary-100'],
        'icon' => 'clock',
        'chart_id' => '',
        'items' => [],
        'has_data' => false,
        'total' => 0,
        'gender_ratio' => null,
    ],
    'title' => null,
    'subtitle' => null,
    'chartId' => null,
    'icon' => null,
])

<div class="bg-white p-4 sm:p-5 md:p-6 rounded-2xl border border-gray-200/90 shadow-xs flex flex-col justify-between">
    <div>
        <!-- Card Header -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl {{ $data['theme_classes']['bg'] }} {{ $data['theme_classes']['text'] }} flex items-center justify-center font-bold shrink-0">
                    @if(($icon ?? $data['icon']) === 'users')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    @elseif(($icon ?? $data['icon']) === 'currency')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @elseif(($icon ?? $data['icon']) === 'briefcase')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    @elseif(($icon ?? $data['icon']) === 'academic')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    @endif
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-900">{{ $title ?? $data['title'] }}</h3>
                    <p class="text-[11px] text-gray-400">{{ $subtitle ?? $data['subtitle'] }}</p>
                </div>
            </div>
            <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $data['theme_classes']['bg'] }} {{ $data['theme_classes']['text'] }} border {{ $data['theme_classes']['border'] }}">
                {{ count($data['items']) }} Kelompok
            </span>
        </div>

        @if($data['has_data'])
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
            <!-- Donut Chart Canvas -->
            <div class="sm:col-span-5 flex items-center justify-center min-h-[220px]">
                <div id="{{ $chartId ?? $data['chart_id'] }}" class="w-full"></div>
            </div>

            <!-- Breakdown Table & List -->
            <div class="sm:col-span-7 space-y-2.5">
                @foreach($data['items'] as $item)
                <div class="p-2 sm:p-2.5 rounded-xl {{ $item['row_bg'] }} border hover:bg-gray-100/60 transition">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <div class="flex items-center gap-1.5 truncate mr-2">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $item['color'] }}"></span>
                            <span class="font-semibold text-gray-800 truncate" title="{{ $item['label'] }}">{{ $item['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if(!empty($item['drill_url']))
                                <a href="{{ $item['drill_url'] }}"
                                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white border border-gray-200 text-gray-800 font-bold hover:bg-primary-50 hover:border-primary-300 hover:text-primary-700 transition shadow-2xs group/btn text-xs"
                                   title="Lihat {{ number_format($item['count']) }} data respon {{ $item['label'] }}">
                                    <span>{{ number_format($item['count']) }}</span>
                                    <svg class="w-3 h-3 text-gray-400 group-hover/btn:text-primary-600 transition-transform group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            @else
                                <span class="font-bold text-gray-400 text-xs px-2 py-0.5">0</span>
                            @endif
                            <span class="text-[11px] font-bold px-1.5 py-0.5 rounded-md border {{ $item['badge_style'] }}">
                                {{ number_format($item['percentage'], 1) }}%
                            </span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200/80 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full transition-all duration-500" style="width: {{ $item['percentage'] }}%; background-color: {{ $item['color'] }}"></div>
                    </div>
                </div>
                @endforeach

                @if(!empty($data['gender_ratio']))
                <div class="text-[11px] text-gray-500 bg-gray-50 p-2.5 rounded-xl border border-gray-100 flex items-center justify-between">
                    <span>Rasio Komparasi:</span>
                    <span class="font-bold text-gray-800">{{ $data['gender_ratio'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @else
        <div class="text-center py-10 text-gray-400">
            <p class="text-xs font-semibold">Belum ada data responden pada filter ini.</p>
        </div>
        @endif
    </div>

    <!-- Footer Meta -->
    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
        <span>Total Sampel Terisi:</span>
        <span class="font-bold text-gray-800">{{ number_format($data['total']) }} Pegawai</span>
    </div>
</div>
