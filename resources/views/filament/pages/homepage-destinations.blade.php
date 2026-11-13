<x-filament-panels::page>
    <div class="space-y-6">

        <!-- 1. Header Curation Studio Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-teal-950 to-slate-900 border border-teal-500/30 p-6 sm:p-8 shadow-2xl text-white">
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400/20 text-amber-300 border border-amber-400/30 backdrop-blur-md shadow-xs">
                            <svg class="w-3.5 h-3.5 text-amber-300 flex-shrink-0" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Homepage Grid Curator Studio
                        </span>
                        <span class="text-xs text-slate-300 font-mono px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10">
                            Recommended: 6 Hero Slots (මුල් පිටුවේ ප්‍රදර්ශනය)
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2.5">
                        <span>Featured Destinations Curation Studio</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                        Hand-pick and prioritize which attractions are prominently highlighted on the visitor landing page grid. Reorder priority sequence directly in the visual cards or in the master catalog table below.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ url('/') }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white text-slate-900 hover:bg-emerald-50 font-bold text-xs shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Preview Live Landing Page</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Live Visual Showcase Slot Visualizer (Top 6 Featured Cards) -->
        <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold flex-shrink-0" style="width: 32px; height: 32px; min-width: 32px;">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm sm:text-base">Live Homepage Showcase Slots (ප්‍රදර්ශන පෙරදසුන)</h3>
                        <p class="text-xs text-slate-500">Visual mockup of the 6 premier cards ordered by priority sequence</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold px-3 py-1 rounded-full {{ $this->getFeaturedCount() >= 6 ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800' }}">
                        {{ $this->getFeaturedCount() }} of 6 Slots Active
                    </span>
                </div>
            </div>

            @php
                $featuredDests = $this->getFeaturedDestinations();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                @for($slotIndex = 0; $slotIndex < 6; $slotIndex++)
                    @php
                        $dest = $featuredDests->get($slotIndex);
                        $slotNum = $slotIndex + 1;
                        $rankBadge = match($slotNum) {
                            1 => '🥇 Rank 1 (Top Hero)',
                            2 => '🥈 Rank 2',
                            3 => '🥉 Rank 3',
                            default => "Slot #{$slotNum}",
                        };
                    @endphp

                    @if($dest)
                        <div class="relative flex flex-col justify-between overflow-hidden rounded-2xl border border-emerald-500/30 dark:border-emerald-500/30 bg-slate-50/50 dark:bg-slate-800/40 p-3 shadow-xs hover:shadow-md transition-all group">
                            <!-- Slot rank badge -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold {{ $slotNum <= 3 ? 'bg-amber-100 dark:bg-amber-900/60 text-amber-800 dark:text-amber-200 border border-amber-300 dark:border-amber-700' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                    {{ $rankBadge }}
                                </span>
                                <span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400 font-bold">Prio: {{ $dest->featured_priority }}</span>
                            </div>

                            <!-- Thumbnail Image -->
                            <div class="relative w-full h-28 rounded-xl overflow-hidden mb-2.5 bg-slate-200 dark:bg-slate-700">
                                <img src="{{ $dest->getFeaturedImageUrl() }}" alt="{{ $dest->title_en }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded-md text-[9px] font-bold bg-slate-900/80 text-white backdrop-blur-xs">
                                    {{ $dest->category }}
                                </span>
                            </div>

                            <!-- Title & Subtitle -->
                            <div class="min-w-0 mb-3">
                                <h4 class="text-xs font-black text-slate-900 dark:text-slate-100 truncate" title="{{ $dest->title_en }}">
                                    {{ $dest->title_en }}
                                </h4>
                                @if(!empty($dest->title_si))
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $dest->title_si }}</p>
                                @endif
                                <div class="flex items-center gap-2 mt-1.5 text-[10px] text-slate-400">
                                    <span>👁️ {{ $dest->views_count }}</span>
                                    <span>★ {{ $dest->averageRating() }}</span>
                                </div>
                            </div>

                            <!-- Fast Edit Link -->
                            <a href="{{ route('filament.admin.resources.destinations.edit', $dest->id) }}"
                               class="w-full inline-flex items-center justify-center gap-1 py-1.5 rounded-xl text-[11px] font-bold bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-400 transition shadow-xs">
                                <span>Edit Attraction</span>
                                <span class="text-xs">→</span>
                            </a>
                        </div>
                    @else
                        <!-- Empty Available Slot -->
                        <div class="flex flex-col items-center justify-center text-center p-5 rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-800/20 text-slate-400 min-h-[220px]">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-2 font-bold text-sm">
                                +{{ $slotNum }}
                            </div>
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Empty Slot #{{ $slotNum }}</span>
                            <span class="text-[10px] text-slate-400 mt-1 leading-tight">
                                Toggle any destination in table below to feature
                            </span>
                        </div>
                    @endif
                @endfor
            </div>
        </div>

        <!-- 3. Metric Stat Cards Bar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Stat 1: Featured Count -->
            <div class="p-5 rounded-3xl bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-500/20 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">Showcase Capacity</div>
                    <div class="text-3xl font-black text-slate-900 dark:text-slate-100 mt-1 flex items-baseline gap-1.5">
                        <span>{{ $this->getFeaturedCount() }}</span>
                        <span class="text-xs font-semibold text-slate-400">/ 6 Target Slots</span>
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium mt-1">
                        {{ $this->getFeaturedCount() >= 6 ? '✅ Showcase slots filled to optimum' : 'ℹ️ ' . (6 - $this->getFeaturedCount()) . ' slots currently unoccupied' }}
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>

            <!-- Stat 2: Total in Catalog -->
            <div class="p-5 rounded-3xl bg-slate-500/10 border border-slate-500/20 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Total in Repository</div>
                    <div class="text-3xl font-black text-slate-900 dark:text-slate-100 mt-1">
                        {{ $this->getTotalCount() }}
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium mt-1">
                        All verified tourist attractions across Elahera
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-500/15 text-slate-600 dark:text-slate-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Stat 3: Priority Ordering Guide -->
            <div class="p-5 rounded-3xl bg-amber-500/10 border border-amber-500/20 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-300">Sort Sequence Logic</div>
                    <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-1 leading-relaxed">
                        Numbers: <span class="font-black text-amber-600 dark:text-amber-400">0, 1, 2, 3...</span> appear first on the visitor grid.
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium mt-1">
                        Double-click priority values directly in table to edit
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 4. Interactive Livewire Table Container -->
        <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-sm overflow-hidden">
            <div class="mb-3 px-2 flex items-center justify-between">
                <div>
                    <h3 class="font-extrabold text-sm sm:text-base text-slate-900 dark:text-slate-100">All Destinations Master Catalog (සම්පූර්ණ ගමනාන්ත ලැයිස්තුව)</h3>
                    <p class="text-xs text-slate-500">Toggle "Show on Homepage" to instantly showcase an item, or edit sort priority directly</p>
                </div>
            </div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
