@php
    $hasFilters = request()->anyFilled(['profession', 'directorate', 'unit', 'status', 'tenure', 'age', 'gender', 'education', 'income']);
@endphp

<div class="bg-white p-5 rounded-2xl border border-gray-200/90 shadow-xs space-y-3.5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-primary-50 text-primary-700 flex items-center justify-center font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <div>
                <h3 class="text-xs font-bold text-gray-900">Segmentasi Analitik & Filter Data</h3>
                <p class="text-[11px] text-gray-400">Saring metrik berdasarkan unit kerja RS maupun demografi pegawai (usia, gender, pendidikan, pendapatan).</p>
            </div>
        </div>

        <!-- Active Filters Reset -->
        @if($hasFilters)
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-[11px] text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                    Filter Aktif:
                </span>

                @if(request('directorate'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-primary-50 text-primary-800 font-semibold text-[11px] border border-primary-200">
                    <span>Direktorat: <strong>{{ request('directorate') }}</strong></span>
                    <a href="{{ route('admin.dashboard', array_merge(request()->except(['directorate', 'unit']), ['period_id' => $selectedPeriod?->id])) }}" class="text-primary-500 hover:text-red-500 font-bold ml-0.5" title="Hapus filter direktorat">✕</a>
                </span>
                @endif

                @if(request('unit'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700 font-semibold text-[11px] border border-gray-200">
                    <span>Satker: <strong>{{ request('unit') }}</strong></span>
                    <a href="{{ route('admin.dashboard', array_merge(request()->except('unit'), ['period_id' => $selectedPeriod?->id])) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5" title="Hapus filter satuan kerja">✕</a>
                </span>
                @endif

                @if(request('profession'))
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700 font-semibold text-[11px] border border-gray-200">
                    <span>Profesi: <strong>{{ request('profession') }}</strong></span>
                    <a href="{{ route('admin.dashboard', array_merge(request()->except('profession'), ['period_id' => $selectedPeriod?->id])) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5" title="Hapus filter profesi">✕</a>
                </span>
                @endif

                <a href="{{ route('admin.dashboard', ['period_id' => $selectedPeriod?->id]) }}"
                   class="text-[11px] font-bold text-red-600 hover:text-red-700 flex items-center gap-1 bg-red-50 hover:bg-red-100 px-2 py-0.5 rounded transition" title="Bersihkan seluruh filter">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Reset Filter</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Filter Controls Form -->
    <form method="GET" action="{{ route('admin.dashboard') }}" class="space-y-3">
        <!-- Row 1: Organisasi & Kepegawaian RS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-center">
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
                <select name="directorate" onchange="if(this.form.elements['unit']) this.form.elements['unit'].value = ''; this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('directorate') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Direktorat</option>
                    @foreach($demographics['directorates'] ?? [] as $dir)
                        <option value="{{ $dir }}" {{ request('directorate') === $dir ? 'selected' : '' }}>{{ $dir }}</option>
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

            <!-- 4. Kelompok Profesi -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Kelompok Profesi</label>
                <select name="profession" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('profession') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Seluruh Profesi</option>
                    @foreach($demographics['professions'] ?? [] as $prof)
                        <option value="{{ $prof }}" {{ request('profession') === $prof ? 'selected' : '' }}>{{ $prof }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 5. Status Kepegawaian -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Status Kepegawaian</label>
                <select name="status" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('status') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Seluruh Status</option>
                    @foreach($demographics['statuses'] ?? [] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 6. Masa Kerja -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Masa Kerja di RS</label>
                <select name="tenure" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('tenure') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Seluruh Masa Kerja</option>
                    @foreach($demographics['tenures'] ?? [] as $tn)
                        <option value="{{ $tn }}" {{ request('tenure') === $tn ? 'selected' : '' }}>{{ $tn }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: Profil Demografi Individu Responden -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-center pt-2 border-t border-gray-100">
            <!-- 7. Usia -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Rentang Usia</label>
                <select name="age" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('age') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Usia</option>
                    @foreach($demographics['ages'] ?? [] as $ag)
                        <option value="{{ $ag }}" {{ request('age') === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 8. Jenis Kelamin -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Jenis Kelamin</label>
                <select name="gender" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('gender') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Gender</option>
                    @foreach($demographics['genders'] ?? [] as $gen)
                        <option value="{{ $gen }}" {{ request('gender') === $gen ? 'selected' : '' }}>{{ $gen }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 9. Pendidikan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Pendidikan Terakhir</label>
                <select name="education" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('education') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Pendidikan</option>
                    @foreach($demographics['educations'] ?? [] as $edu)
                        <option value="{{ $edu }}" {{ request('education') === $edu ? 'selected' : '' }}>{{ $edu }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 10. Pendapatan -->
            <div>
                <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-500 mb-1">Jumlah Pendapatan</label>
                <select name="income" onchange="this.form.submit()"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('income') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Pendapatan</option>
                    @foreach($demographics['incomes'] ?? [] as $inc)
                        <option value="{{ $inc }}" {{ request('income') === $inc ? 'selected' : '' }}>{{ $inc }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>
