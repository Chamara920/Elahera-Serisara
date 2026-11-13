<x-filament-widgets::widget>
    @if($totalPending > 0)
        <div class="rounded-3xl border border-amber-300 dark:border-amber-700/60 bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-transparent dark:from-amber-950/40 dark:to-orange-950/20 p-6 shadow-sm backdrop-blur-md">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-700 dark:text-amber-400 flex items-center justify-center font-bold flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                        <span class="relative flex h-5 w-5" style="width: 20px; height: 20px;">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <svg class="relative w-5 h-5 text-amber-600 dark:text-amber-400" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base flex flex-wrap items-center gap-2">
                            <span>Pending Content Moderation Queue</span>
                            <span class="px-3 py-0.5 rounded-full text-xs font-black bg-amber-500 text-white shadow-xs">
                                {{ $totalPending }} Item{{ $totalPending === 1 ? '' : 's' }} Awaiting Review
                            </span>
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">
                            New user-submitted reviews, blogs, and public news require admin verification before going live. (අනුමැතිය අපේක්ෂිත අයිතම)
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                <!-- 1. Destination Reviews -->
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-xs transition hover:border-amber-400/50">
                    <div>
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Destination Reviews</div>
                        <div class="text-xl font-black text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ $pendingReviews->count() }} <span class="text-xs font-semibold text-amber-600">pending</span>
                        </div>
                    </div>
                    <a href="{{ route('filament.admin.resources.destination-reviews.index') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-all hover:scale-[1.02]">
                        Review Now →
                    </a>
                </div>

                <!-- 2. Community Blog Articles -->
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-xs transition hover:border-amber-400/50">
                    <div>
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Blog Articles</div>
                        <div class="text-xl font-black text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ $pendingBlogs->count() }} <span class="text-xs font-semibold text-amber-600">pending</span>
                        </div>
                    </div>
                    <a href="{{ route('filament.admin.resources.blog-posts.index') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-all hover:scale-[1.02]">
                        Review Now →
                    </a>
                </div>

                <!-- 3. News Notices & Events -->
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-between shadow-xs transition hover:border-amber-400/50">
                    <div>
                        <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">News & Events</div>
                        <div class="text-xl font-black text-slate-900 dark:text-slate-100 mt-0.5">
                            {{ $pendingNews->count() }} <span class="text-xs font-semibold text-amber-600">pending</span>
                        </div>
                    </div>
                    <a href="{{ route('filament.admin.resources.news-events.index') }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-all hover:scale-[1.02]">
                        Review Now →
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-3xl border border-emerald-200/80 dark:border-emerald-800/60 bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-slate-50/50 dark:from-emerald-950/30 dark:to-slate-900 p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg flex-shrink-0 shadow-xs" style="width: 36px; height: 36px; min-width: 36px;">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-sm flex items-center gap-2">
                        <span>All Content Moderation is Up to Date</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300">Clean Queue</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        There are currently no pending destination reviews, blog posts, or public news notices waiting for review.
                    </p>
                </div>
            </div>
            <div class="text-xs text-emerald-700 dark:text-emerald-400 font-mono font-bold flex items-center gap-1.5 self-end sm:self-auto bg-emerald-500/10 px-3 py-1.5 rounded-xl border border-emerald-500/20">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Queue Cleared • සියල්ල යාවත්කාලීනයි</span>
            </div>
        </div>
    @endif
</x-filament-widgets::widget>
