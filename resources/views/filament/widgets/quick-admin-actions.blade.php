<x-filament-widgets::widget>
    <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/20 flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                    <svg class="w-4 h-4" width="16" height="16" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm sm:text-base">Quick Action Launchers & Control Shortcuts</h3>
                    <p class="text-xs text-slate-500">Fast one-click access to core administration modules (ක්ෂණික මෙවලම්)</p>
                </div>
            </div>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800 self-start sm:self-auto">
                ⚡ 8 Direct Launchers
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2.5 sm:gap-3">
            <!-- 1. Add Destination -->
            <a href="{{ route('filament.admin.resources.destinations.create') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-emerald-50/80 hover:to-emerald-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-emerald-950/40 dark:hover:to-emerald-900/20 border border-slate-200/80 hover:border-emerald-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 group-hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Add Destination</span>
                <span class="text-[10px] text-slate-400 mt-0.5">නව ගමනාන්තය</span>
            </a>

            <!-- 2. Bus Stops Master -->
            <a href="{{ route('filament.admin.resources.bus-stops.index') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-cyan-50/80 hover:to-cyan-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-cyan-950/40 dark:hover:to-cyan-900/20 border border-slate-200/80 hover:border-cyan-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-cyan-700 dark:hover:text-cyan-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/10 group-hover:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Bus Stops Hub</span>
                <span class="text-[10px] text-slate-400 mt-0.5">නැවතුම්පළ / මාරුවීම්</span>
            </a>

            <!-- 3. Transit Routes -->
            <a href="{{ route('filament.admin.resources.bus-routes.index') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-teal-50/80 hover:to-teal-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-teal-950/40 dark:hover:to-teal-900/20 border border-slate-200/80 hover:border-teal-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-teal-700 dark:hover:text-teal-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-teal-500/10 group-hover:bg-teal-500/20 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Bus Routes</span>
                <span class="text-[10px] text-slate-400 mt-0.5">බස් කාලසටහන්</span>
            </a>

            <!-- 4. Categories & Tags -->
            <a href="{{ route('filament.admin.resources.destination-categories.index') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-amber-50/80 hover:to-amber-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-amber-950/40 dark:hover:to-amber-900/20 border border-slate-200/80 hover:border-amber-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-amber-700 dark:hover:text-amber-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 group-hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Categories</span>
                <span class="text-[10px] text-slate-400 mt-0.5">වර්ගීකරණ ටැග්</span>
            </a>

            <!-- 5. Moderate Reviews -->
            <a href="{{ route('filament.admin.resources.destination-reviews.index') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-rose-50/80 hover:to-rose-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-rose-950/40 dark:hover:to-rose-900/20 border border-slate-200/80 hover:border-rose-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-rose-700 dark:hover:text-rose-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 group-hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Reviews</span>
                <span class="text-[10px] text-slate-400 mt-0.5">සමාලෝචන පරීක්ෂාව</span>
            </a>

            <!-- 6. Post Notice / Event -->
            <a href="{{ route('filament.admin.resources.news-events.create') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-blue-50/80 hover:to-blue-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-blue-950/40 dark:hover:to-blue-900/20 border border-slate-200/80 hover:border-blue-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Post Notice</span>
                <span class="text-[10px] text-slate-400 mt-0.5">පුවත් & නිවේදන</span>
            </a>

            <!-- 7. Write Blog Article -->
            <a href="{{ route('filament.admin.resources.blog-posts.create') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-purple-50/80 hover:to-purple-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-purple-950/40 dark:hover:to-purple-900/20 border border-slate-200/80 hover:border-purple-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-purple-700 dark:hover:text-purple-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Write Blog</span>
                <span class="text-[10px] text-slate-400 mt-0.5">සංචාරක අත්දැකීම්</span>
            </a>

            <!-- 8. Database Vault -->
            <a href="{{ route('filament.admin.pages.database-backup-restore') }}" 
               class="flex flex-col items-center justify-center p-3 rounded-2xl bg-gradient-to-b from-slate-50 to-white hover:from-emerald-50/80 hover:to-emerald-100/40 dark:from-slate-800/60 dark:to-slate-800/30 dark:hover:from-emerald-950/40 dark:hover:to-emerald-900/20 border border-slate-200/80 hover:border-emerald-400/60 dark:border-slate-700/60 text-slate-700 dark:text-slate-200 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all duration-200 shadow-xs hover:shadow-md hover:-translate-y-0.5 text-center group">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 group-hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-2 transition-colors shadow-xs flex-shrink-0" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" width="20" height="20" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M4 7c0-2 1-3 3-3h10c2 0 3 1 3 3M4 7h16m-5 4h.01M9 11h.01M9 15h6"/>
                    </svg>
                </div>
                <span class="text-xs font-bold leading-tight">Backup Vault</span>
                <span class="text-[10px] text-slate-400 mt-0.5">දත්ත සමුදා ආරක්ෂාව</span>
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
