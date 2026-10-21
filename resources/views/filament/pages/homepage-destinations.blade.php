<x-filament-panels::page>
    <div class="space-y-4">
        <!-- Information & Statistics Banner -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-gradient-to-br from-emerald-500/10 to-teal-500/10 border border-emerald-500/20 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">මුල් පිටුවේ ප්‍රදර්ශිත ගමනාන්ත</p>
                    <p class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-100 mt-1">
                        {{ $this->getFeaturedCount() }} <span class="text-xs font-normal text-slate-500">/ 6 නිර්දේශිතයි</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Featured on Homepage Grid</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-300 flex items-center justify-center text-2xl">
                    🌟
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-slate-500/10 border border-slate-500/20 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-300">සියලු සක්‍රිය ගමනාන්ත</p>
                    <p class="text-3xl font-extrabold text-slate-900 dark:text-slate-100 mt-1">
                        {{ $this->getTotalCount() }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Total Destinations in Database</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-500/20 text-slate-600 dark:text-slate-300 flex items-center justify-center text-2xl">
                    📍
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700 dark:text-amber-300">ප්‍රමුඛතා අනුපිළිවෙල (Ordering)</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-200 mt-1">
                        අඩු අංකය (1, 2, 3...) මුලින්ම මුල් පිටුවේ පළමු කාඩ්පත් ලෙස දර්ශනය වේ.
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-600 dark:text-amber-300 flex items-center justify-center text-2xl">
                    🔢
                </div>
            </div>
        </div>

        <!-- Instruction Alert -->
        <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm text-sm text-slate-600 dark:text-slate-300 flex items-start gap-3">
            <span class="text-xl">ℹ️</span>
            <div>
                <strong>උපදෙස්:</strong> මුල් පිටුවේ ප්‍රදර්ශනය කළ යුතු සංචාරක ගමනාන්ත තෝරාගැනීමට පහත වගුවේ ඇති <span class="font-bold text-emerald-600">"මුල් පිටුවේ පෙන්වන්න" (Show on Homepage)</span> ටොගලය සක්‍රිය හෝ අක්‍රිය කරන්න. "අනුපිළිවෙල" තීරුවේ අංක ක්ෂණිකව සංස්කරණය කළ හැක.
            </div>
        </div>

        <!-- Livewire Table Component -->
        <div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
