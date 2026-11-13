<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white shadow-2xl border border-emerald-500/25 p-6 sm:p-8">
        <!-- Ambient decorative background glow orbs -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
        <div class="absolute -bottom-24 -left-20 w-80 h-80 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
            <!-- Left Info Area -->
            <div class="space-y-4 max-w-3xl">
                <!-- Status Badges Strip -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase bg-emerald-400/15 text-emerald-300 border border-emerald-400/30 backdrop-blur-md shadow-xs">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        Admin Command Center
                    </span>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-slate-300 bg-white/5 backdrop-blur-md border border-white/10">
                        <svg class="w-3.5 h-3.5 text-emerald-400" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        MySQL 8.x Live
                    </span>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-slate-300 bg-white/5 backdrop-blur-md border border-white/10">
                        <svg class="w-3.5 h-3.5 text-amber-400" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ now()->format('l, d F Y • h:i A') }} (SLT)
                    </span>
                </div>

                <!-- Main Greeting & Description -->
                <div>
                    @php
                        $hour = now()->format('H');
                        $greetingSi = ($hour < 12) ? 'සුබ උදෑසනක්' : (($hour < 17) ? 'සුබ දහවලක්' : 'සුබ සැඳෑවක්');
                    @endphp
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white flex flex-wrap items-center gap-2.5">
                        <span>Elahera Serisara Control Center</span>
                        <span class="text-xs sm:text-sm font-semibold px-2.5 py-0.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">v2.5</span>
                    </h2>
                    <p class="text-sm text-slate-300/90 leading-relaxed mt-1.5 max-w-2xl">
                        {{ $greetingSi }}, <strong class="text-white font-bold">{{ auth()->user()->name ?? 'Administrator' }}</strong>! Comprehensive management hub for verified tourist destinations, transit routes, bus transfer junctions, community traveler stories, and database backups.
                    </p>
                </div>

                <!-- Live KPI Counter Tiles -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-2">
                    <a href="{{ route('filament.admin.resources.destinations.index') }}" 
                       class="group p-3 rounded-2xl bg-white/5 hover:bg-emerald-500/15 border border-white/10 hover:border-emerald-500/40 backdrop-blur-md transition-all duration-200">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 group-hover:text-emerald-200 flex items-center justify-between">
                            <span>Destinations</span>
                            <span class="text-xs">→</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-white mt-1">{{ $destinationsCount }}</div>
                        <div class="text-[10px] text-slate-400 truncate">ස්ථාන සංඛ්‍යාව</div>
                    </a>

                    <a href="{{ route('filament.admin.resources.bus-stops.index') }}" 
                       class="group p-3 rounded-2xl bg-white/5 hover:bg-cyan-500/15 border border-white/10 hover:border-cyan-500/40 backdrop-blur-md transition-all duration-200">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-cyan-300 group-hover:text-cyan-200 flex items-center justify-between">
                            <span>Bus Stops</span>
                            <span class="text-xs">→</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-white mt-1">
                            {{ $busStopsCount }}
                            @if($hubsCount > 0)
                                <span class="text-xs font-semibold text-cyan-300 font-normal">({{ $hubsCount }} Hubs)</span>
                            @endif
                        </div>
                        <div class="text-[10px] text-slate-400 truncate">නැවතුම්පළ</div>
                    </a>

                    <a href="{{ route('filament.admin.resources.bus-routes.index') }}" 
                       class="group p-3 rounded-2xl bg-white/5 hover:bg-teal-500/15 border border-white/10 hover:border-teal-500/40 backdrop-blur-md transition-all duration-200">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-teal-300 group-hover:text-teal-200 flex items-center justify-between">
                            <span>Bus Routes</span>
                            <span class="text-xs">→</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-white mt-1">{{ $routesCount }}</div>
                        <div class="text-[10px] text-slate-400 truncate">බස් මාර්ග</div>
                    </a>

                    <a href="{{ route('filament.admin.resources.destination-categories.index') }}" 
                       class="group p-3 rounded-2xl bg-white/5 hover:bg-amber-500/15 border border-white/10 hover:border-amber-500/40 backdrop-blur-md transition-all duration-200">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-amber-300 group-hover:text-amber-200 flex items-center justify-between">
                            <span>Categories</span>
                            <span class="text-xs">→</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-white mt-1">{{ $categoriesCount }}</div>
                        <div class="text-[10px] text-slate-400 truncate">වර්ගීකරණ</div>
                    </a>

                    <a href="{{ route('filament.admin.resources.users.index') }}" 
                       class="group p-3 rounded-2xl bg-white/5 hover:bg-purple-500/15 border border-white/10 hover:border-purple-500/40 backdrop-blur-md transition-all duration-200 col-span-2 sm:col-span-1">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-purple-300 group-hover:text-purple-200 flex items-center justify-between">
                            <span>Travelers</span>
                            <span class="text-xs">→</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black text-white mt-1">{{ $usersCount }}</div>
                        <div class="text-[10px] text-slate-400 truncate">සාමාජිකයින්</div>
                    </a>
                </div>
            </div>

            <!-- Right Fast Action Quick Launcher Stack -->
            <div class="flex flex-col sm:flex-row xl:flex-col gap-3 flex-shrink-0">
                <a href="{{ url('/') }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2.5 px-5 py-3 rounded-2xl bg-white text-slate-900 hover:bg-emerald-50 font-bold text-xs shadow-lg shadow-black/20 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] group">
                    <svg class="w-4 h-4 text-emerald-600 transition-transform group-hover:rotate-12 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>View Public Portal</span>
                </a>

                <a href="{{ route('filament.admin.pages.homepage-destinations') }}"
                   class="inline-flex items-center justify-center gap-2.5 px-5 py-3 rounded-2xl bg-emerald-500/20 hover:bg-emerald-500/30 text-white font-bold text-xs border border-emerald-400/30 backdrop-blur-md transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4 text-amber-300 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span>Curate Homepage Grid</span>
                </a>

                <a href="{{ route('filament.admin.resources.bus-stops.index') }}"
                   class="inline-flex items-center justify-center gap-2.5 px-5 py-3 rounded-2xl bg-cyan-500/20 hover:bg-cyan-500/30 text-white font-bold text-xs border border-cyan-400/30 backdrop-blur-md transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4 text-cyan-300 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Bus Stops & Hubs</span>
                </a>

                <a href="{{ route('filament.admin.pages.database-backup-restore') }}"
                   class="inline-flex items-center justify-center gap-2.5 px-5 py-3 rounded-2xl bg-teal-500/20 hover:bg-teal-500/30 text-white font-bold text-xs border border-teal-400/30 backdrop-blur-md transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-4 h-4 text-emerald-300 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M4 7c0-2 1-3 3-3h10c2 0 3 1 3 3M4 7h16m-5 4h.01M9 11h.01M9 15h6"/>
                    </svg>
                    <span>Database Backup Vault</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
