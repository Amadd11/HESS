{{-- Header & Multi-Dimensional Filters --}}
<div class="space-y-4">
    <!-- Top Header Banner & Export -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 md:p-8 shadow-xs border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-5 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-primary-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-1.5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-emerald-400 text-xs font-semibold tracking-wide backdrop-blur-xs border border-white/10">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Engine Analitik Sentimen Kualitatif HESS
            </div>
            <h1 class="text-2xl md:text-3xl font-black tracking-tight text-white">
                Sentiment Analysis Dashboard
            </h1>
            <p class="text-xs md:text-sm text-slate-300 font-normal max-w-2xl leading-relaxed">
                Analisis mendalam emosi, persepsi, dan aspirasi terbuka responden survei kepuasan kerja rumah sakit secara visual dan terstruktur.
            </p>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2.5 shrink-0 self-start md:self-auto">
            <a href="{{ route('admin.sentiment.report', request()->query()) }}" target="_blank"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold shadow-xs hover:shadow-md transition active:scale-95 border border-white/20 backdrop-blur-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Laporan Eksekutif</span>
            </a>

            <a href="{{ route('admin.sentiment.export', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-xs hover:shadow-md transition active:scale-95 border border-emerald-500/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="bg-white p-5 rounded-3xl border border-gray-200/90 shadow-xs space-y-3.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-gray-900">Panel Segmentasi & Filter Sentimen</h3>
                    <p class="text-[11px] text-gray-400">Pilah metrik sentimen berdasarkan variabel demografi dan kategori persepsi.</p>
                </div>
            </div>

            @if($hasActiveFilters)
            <div class="flex items-center gap-2">
                <span class="text-[11px] text-amber-700 font-semibold bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                    Filter Aktif
                </span>
                <a href="{{ route('admin.sentiment.index', ['period_id' => $selectedPeriod?->id]) }}"
                   class="text-[11px] font-bold text-red-600 hover:text-red-700 flex items-center gap-1 bg-red-50 hover:bg-red-100 px-2.5 py-0.5 rounded-full transition">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Reset Filter</span>
                </a>
            </div>
            @endif
        </div>

        <form method="GET" action="{{ route('admin.sentiment.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3 items-center">
            <!-- 1. Periode -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Periode Survei</label>
                <select name="period_id" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl font-bold text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" {{ ($selectedPeriod?->id === $p->id) ? 'selected' : '' }}>
                            {{ $p->name }} {{ $p->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Direktorat -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Direktorat</label>
                <select name="directorate" onchange="this.form.unit.value = ''; this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('directorate') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Direktorat</option>
                    @foreach($demographics['directorates'] ?? [] as $d)
                        <option value="{{ $d }}" {{ request('directorate') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Satuan Kerja -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Satuan Kerja</label>
                <select name="unit" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('unit') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Satuan Kerja</option>
                    @if(! empty($demographics['directorate_units']))
                        @if(request('directorate') && isset($demographics['directorate_units'][request('directorate')]))
                            @foreach($demographics['directorate_units'][request('directorate')] as $u)
                                <option value="{{ $u }}" {{ request('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        @else
                            @foreach($demographics['directorate_units'] as $dirName => $dirUnits)
                                <optgroup label="{{ $dirName }}">
                                    @foreach($dirUnits as $u)
                                        <option value="{{ $u }}" {{ request('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    @else
                        @foreach($demographics['units'] ?? [] as $u)
                            <option value="{{ $u }}" {{ request('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- 3. Profesi -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Kelompok Profesi</label>
                <select name="profession" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('profession') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Profesi</option>
                    @foreach($demographics['professions'] ?? [] as $prof)
                        <option value="{{ $prof }}" {{ request('profession') === $prof ? 'selected' : '' }}>{{ $prof }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 4. Status Pegawai -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Status Pegawai</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('status') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Status</option>
                    @foreach($demographics['statuses'] ?? [] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 5. Masa Kerja -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Masa Kerja</label>
                <select name="tenure" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('tenure') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Masa Kerja</option>
                    @foreach($demographics['tenures'] ?? [] as $tn)
                        <option value="{{ $tn }}" {{ request('tenure') === $tn ? 'selected' : '' }}>{{ $tn }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 6. Sentimen -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Kategori Sentimen</label>
                <select name="sentiment" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('sentiment') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="all">Semua Sentimen</option>
                    <option value="positive" {{ request('sentiment') === 'positive' ? 'selected' : '' }}>Hanya Positif</option>
                    <option value="neutral" {{ request('sentiment') === 'neutral' ? 'selected' : '' }}>Hanya Netral</option>
                    <option value="negative" {{ request('sentiment') === 'negative' ? 'selected' : '' }}>Hanya Negatif</option>
                </select>
            </div>
        </form>
    </div>
</div>
