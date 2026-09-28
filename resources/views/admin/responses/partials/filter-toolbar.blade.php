{{-- Quick Filter Tabs, Search Bar & Demographic Segmentation Toolbar --}}
<div x-data="responsesFilter({
        showAdvanced: {{ request()->anyFilled(['status', 'tenure', 'age', 'gender', 'education', 'income']) ? 'true' : 'false' }},
        selectedDirectorate: '{{ request('directorate', '') }}',
        selectedUnit: '{{ request('unit', '') }}',
        directorateUnits: {{ Js::from($demographics['directorate_units'] ?? []) }},
        allUnits: {{ Js::from($demographics['units'] ?? []) }}
     })"
     class="bg-white rounded-2xl border border-gray-200/90 shadow-xs p-5 space-y-4">

    <!-- Quick Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1 border-b border-gray-100 scrollbar-none text-xs">
        <span class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider shrink-0 mr-1">Filter NPS:</span>

        <a href="{{ route('admin.responses.index', request()->except('nps_category')) }}"
            class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ !request()->filled('nps_category') ? 'bg-primary-700 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Semua Kategori ({{ $stats['total'] }})
        </a>

        <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'promoter'])) }}"
            class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('nps_category') === 'promoter' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
            Promoters ({{ $stats['promoters'] }})
        </a>

        <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'passive'])) }}"
            class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('nps_category') === 'passive' ? 'bg-amber-700 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
            Passives ({{ $stats['passives'] }})
        </a>

        <a href="{{ route('admin.responses.index', array_merge(request()->except('nps_category'), ['nps_category' => 'detractor'])) }}"
            class="px-3 py-1.5 rounded-xl font-bold transition shrink-0 {{ request('nps_category') === 'detractor' ? 'bg-red-700 text-white shadow-xs' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
            Detractors ({{ $stats['detractors'] }})
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.responses.index') }}" class="space-y-3">
        @if(request('nps_category'))
        <input type="hidden" name="nps_category" value="{{ request('nps_category') }}">
        @endif
        @if(request('per_page'))
        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
        @endif

        <!-- Row 1: Filter Utama -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <!-- Search Bar -->
            <div class="lg:col-span-3 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari ID, saran, kata kunci..."
                    class="w-full h-10 pl-10 pr-9 rounded-xl border border-gray-200 text-xs text-gray-900 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white">
                @if(request('search'))
                <a href="{{ route('admin.responses.index', request()->except('search')) }}"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition" title="Hapus pencarian">
                    ✕
                </a>
                @endif
            </div>

            <!-- Periode Dropdown -->
            <div class="lg:col-span-2">
                <select name="period_id"
                    class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Periode</option>
                    @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                        {{ $period->name }} {{ $period->is_active ? '(Aktif)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Direktorat Dropdown (Reaktif terhadap Satuan Kerja) -->
            <div class="lg:col-span-2">
                <select name="directorate" x-model="selectedDirectorate"
                    :class="selectedDirectorate ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : ''"
                    class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
                    <option value="">Semua Direktorat</option>
                    @foreach($demographics['directorates'] ?? [] as $d)
                    <option value="{{ $d }}" {{ request('directorate') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Satuan Kerja Dropdown (Otomatis Filter Realtime Sesuai Direktorat) -->
            <div class="lg:col-span-2">
                <select name="unit" x-ref="unitSelect" x-model="selectedUnit"
                    :class="selectedUnit ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : ''"
                    class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer">
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

            <!-- Profesi Dropdown -->
            <div class="lg:col-span-2">
                <select name="profession"
                    class="w-full h-10 px-3 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-600 transition bg-white cursor-pointer {{ request('profession') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                    <option value="">Semua Profesi</option>
                    @foreach($demographics['professions'] ?? [] as $p)
                    <option value="{{ $p }}" {{ request('profession') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Controls: Filter & Toggle Demografi Lanjutan -->
            <div class="lg:col-span-1 flex items-center gap-1.5">
                <x-button type="submit" variant="primary" size="md" class="w-full justify-center">
                    <span>Filter</span>
                </x-button>

                <!-- Toggle Demografi Button -->
                @php
                    $activeDemoCount = collect(['status', 'tenure', 'age', 'gender', 'education', 'income'])->filter(fn($f) => request()->filled($f))->count();
                @endphp
                <button type="button" @click="showAdvanced = !showAdvanced"
                    :class="showAdvanced || {{ $activeDemoCount }} > 0 ? 'bg-primary-50 text-primary-700 border-primary-300' : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50'"
                    class="h-10 px-2.5 rounded-xl border text-xs font-bold flex items-center justify-center gap-1 transition shrink-0 cursor-pointer"
                    title="Filter Karakteristik Demografi (Usia, Gender, Pendidikan, Pendapatan, Status, Masa Kerja)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    @if($activeDemoCount > 0)
                    <span class="w-4 h-4 rounded-full bg-primary-600 text-white text-[10px] flex items-center justify-center font-black">{{ $activeDemoCount }}</span>
                    @endif
                </button>

                @if($hasActiveFilters)
                <a href="{{ route('admin.responses.index') }}"
                    class="h-10 px-2.5 rounded-xl border border-gray-200 hover:border-gray-300 text-gray-400 hover:text-gray-700 text-xs font-bold flex items-center justify-center transition bg-white shrink-0"
                    title="Reset Filter">
                    ✕
                </a>
                @endif
            </div>
        </div>

        <!-- Row 2: Filter Demografi Lanjutan (Collapsible) -->
        <div x-show="showAdvanced" x-collapse x-cloak class="pt-3 border-t border-gray-100">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 items-center">
                <!-- Status Kepegawaian -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Status Pegawai</label>
                    <select name="status"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('status') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                        <option value="">Semua Status</option>
                        @foreach($demographics['statuses'] ?? [] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Masa Kerja -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Masa Kerja</label>
                    <select name="tenure"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('tenure') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                        <option value="">Semua Masa Kerja</option>
                        @foreach($demographics['tenures'] ?? [] as $tn)
                        <option value="{{ $tn }}" {{ request('tenure') === $tn ? 'selected' : '' }}>{{ $tn }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rentang Usia -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Rentang Usia</label>
                    <select name="age"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('age') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                        <option value="">Semua Usia</option>
                        @foreach($demographics['ages'] ?? [] as $ag)
                        <option value="{{ $ag }}" {{ request('age') === $ag ? 'selected' : '' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Jenis Kelamin</label>
                    <select name="gender"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('gender') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                        <option value="">Semua Gender</option>
                        @foreach($demographics['genders'] ?? [] as $gen)
                        <option value="{{ $gen }}" {{ request('gender') === $gen ? 'selected' : '' }}>{{ $gen }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pendidikan Terakhir -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Pendidikan</label>
                    <select name="education"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('education') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                        <option value="">Semua Pendidikan</option>
                        @foreach($demographics['educations'] ?? [] as $edu)
                        <option value="{{ $edu }}" {{ request('education') === $edu ? 'selected' : '' }}>{{ $edu }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah Pendapatan -->
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">Pendapatan</label>
                    <select name="income"
                        class="w-full h-9 px-2.5 text-xs bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer {{ request('income') ? 'font-bold text-primary-700 bg-primary-50/50 border-primary-300' : '' }}">
                        <option value="">Semua Pendapatan</option>
                        @foreach($demographics['incomes'] ?? [] as $inc)
                        <option value="{{ $inc }}" {{ request('income') === $inc ? 'selected' : '' }}>{{ $inc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>

    <!-- Active Filter Badges Bar -->
    @if($hasActiveFilters)
    <div class="pt-2 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs text-gray-500">
        <span class="font-bold text-gray-400 text-[11px] uppercase tracking-wider">Filter Aktif:</span>

        @if(request('search'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Pencarian: "<strong>{{ request('search') }}</strong>"</span>
            <a href="{{ route('admin.responses.index', request()->except('search')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('period_id'))
        @php $activeP = $periods->firstWhere('id', request('period_id')); @endphp
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-50 text-primary-800 font-semibold text-xs border border-primary-200">
            <span>Periode: <strong>{{ $activeP?->name ?? request('period_id') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('period_id')) }}" class="text-primary-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('directorate'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Direktorat: <strong>{{ request('directorate') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('directorate')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('unit'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Satker: <strong>{{ request('unit') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('unit')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('profession'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Profesi: <strong>{{ request('profession') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('profession')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('status'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Status: <strong>{{ request('status') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('status')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('tenure'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Masa Kerja: <strong>{{ request('tenure') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('tenure')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('age'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Usia: <strong>{{ request('age') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('age')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('gender'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Gender: <strong>{{ request('gender') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('gender')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('education'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Pendidikan: <strong>{{ request('education') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('education')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('income'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold text-xs border border-gray-200">
            <span>Pendapatan: <strong>{{ request('income') }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('income')) }}" class="text-gray-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        @if(request('nps_category'))
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold text-xs border border-emerald-200">
            <span>Kategori: <strong>{{ ucfirst(request('nps_category')) }}</strong></span>
            <a href="{{ route('admin.responses.index', request()->except('nps_category')) }}" class="text-emerald-400 hover:text-red-500 font-bold ml-0.5">✕</a>
        </span>
        @endif

        <a href="{{ route('admin.responses.index') }}" class="text-xs font-bold text-red-600 hover:text-red-800 ml-1 transition">
            Hapus Semua
        </a>
    </div>
    @endif
</div>
