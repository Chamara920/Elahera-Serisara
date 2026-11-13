<x-filament-panels::page>
    <div class="space-y-6">

        <!-- 1. Top Header Banner: Disaster Recovery Vault -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-900 border border-emerald-500/30 p-6 sm:p-8 shadow-2xl text-white">
            <div class="absolute -top-16 -right-16 w-64 h-64 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none animate-pulse"></div>
            <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-400/15 text-emerald-300 border border-emerald-400/30 backdrop-blur-md shadow-xs">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                            </span>
                            Disaster Recovery & Backup Vault
                        </span>
                        <span class="text-xs text-slate-300 font-mono px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10">
                            Engine: {{ strtoupper($this->databaseStats['driver']) }} 8.x • Port 3306
                        </span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2.5">
                        <span>Database Backup & Snapshot Manager</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                        Create high-integrity transactional SQL snapshots, export stand-alone database dumps to your PC, and restore historical system states with zero data loss risk. (දත්ත සමුදාය සුරක්ෂිත කිරීම සහ ප්‍රතිසාධනය)
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-4 py-2 rounded-2xl bg-white/10 text-xs font-bold backdrop-blur-md border border-white/10 text-emerald-300 flex items-center gap-2 shadow-xs">
                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Automated Snapshot Safe</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Live Database Diagnostic KPI Metrics (4-Card Grid) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Metric 1: Database Identity -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5 transition-all hover:shadow-md">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7M4 7c0-2 1-3 3-3h10c2 0 3 1 3 3M4 7h16m-5 4h.01M9 11h.01M9 15h6"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Active Schema</div>
                    <div class="text-base font-black text-slate-900 dark:text-slate-100 truncate mt-0.5" title="{{ $this->databaseStats['database_name'] }}">
                        {{ $this->databaseStats['database_name'] }}
                    </div>
                    <div class="text-[11px] text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 font-semibold mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Connected (Localhost:3306)</span>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Live Size & Structured Tables -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5 transition-all hover:shadow-md">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Storage Footprint</div>
                    <div class="text-base font-black text-slate-900 dark:text-slate-100 mt-0.5">
                        {{ $this->databaseStats['db_size_mb'] }} MB
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">
                        {{ $this->databaseStats['table_count'] }} Relational Tables
                    </div>
                </div>
            </div>

            <!-- Metric 3: Core Records Count -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5 transition-all hover:shadow-md">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Stored Data Assets</div>
                    <div class="text-base font-black text-slate-900 dark:text-slate-100 mt-0.5">
                        {{ $this->databaseStats['total_records'] }} Core Rows
                    </div>
                    <div class="text-[10px] text-slate-500 font-medium truncate mt-0.5" title="Destinations, Halts, Routes, Articles, Users">
                        {{ $this->databaseStats['destinations_count'] }} Dest, {{ $this->databaseStats['bus_stops_count'] }} Halts, {{ $this->databaseStats['blogs_count'] }} Blogs, {{ $this->databaseStats['users_count'] }} Users
                    </div>
                </div>
            </div>

            <!-- Metric 4: Backup Snapshots Vault -->
            <div class="p-5 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center gap-3.5 transition-all hover:shadow-md">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                    <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/>
                    </svg>
                </div>
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Saved Snapshots</div>
                    <div class="text-base font-black text-slate-900 dark:text-slate-100 mt-0.5">
                        {{ $this->databaseStats['total_backups'] }} ({{ $this->databaseStats['total_backup_size'] }})
                    </div>
                    <div class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                        @if($this->databaseStats['last_backup'])
                            Latest: {{ \Carbon\Carbon::parse($this->databaseStats['last_backup']['created_at'])->diffForHumans() }}
                        @else
                            No snapshots created yet
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Two Core Action Panels: Create Snapshot & Upload/Deploy -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Action Panel 1: Create Backup -->
            <div class="p-6 sm:p-7 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3.5 mb-3.5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100">Generate Full Database Snapshot</h3>
                            <p class="text-xs text-slate-500">Export complete transactional SQL archive with schema & records (බැකප් එකක් සාදන්න)</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                        Creates an isolated, compressed `.sql` snapshot capturing all verified destination waypoints, bus stops, transit routes, traveler stories, reviews, and administrator settings into the secure local vault.
                    </p>

                    <!-- Snapshot Scope Checklist Pills -->
                    <div class="p-4 mb-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60 text-xs">
                        <div class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-2 mb-2.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Snapshot Integrity Scope Checklist:</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600 dark:text-slate-400">
                            <div class="flex items-center gap-1.5"><span class="text-emerald-500 font-bold">✓</span> Destinations & Halts</div>
                            <div class="flex items-center gap-1.5"><span class="text-emerald-500 font-bold">✓</span> Bus Stops & Hubs</div>
                            <div class="flex items-center gap-1.5"><span class="text-emerald-500 font-bold">✓</span> Bus Timetables & Trips</div>
                            <div class="flex items-center gap-1.5"><span class="text-emerald-500 font-bold">✓</span> Travel Blogs & Comments</div>
                            <div class="flex items-center gap-1.5"><span class="text-emerald-500 font-bold">✓</span> News Notices & Events</div>
                            <div class="flex items-center gap-1.5"><span class="text-emerald-500 font-bold">✓</span> Users & Security Keys</div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button 
                        type="button" 
                        wire:click="createBackup" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 active:from-emerald-800 active:to-teal-800 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition-all hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50">
                        <svg class="w-4 h-4 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="createBackup">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        <span wire:loading.remove wire:target="createBackup">Generate Snapshot to Vault</span>
                        <span wire:loading wire:target="createBackup" class="flex items-center gap-2">
                            <svg class="animate-spin h-3.5 w-3.5 text-white flex-shrink-0" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating snapshot archive...
                        </span>
                    </button>

                    <button 
                        type="button" 
                        wire:click="createAndDownloadBackup" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition-all hover:scale-[1.01] disabled:opacity-50">
                        <svg class="w-4 h-4 text-slate-500 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="createAndDownloadBackup">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span wire:loading.remove wire:target="createAndDownloadBackup">Generate & Download to PC</span>
                        <span wire:loading wire:target="createAndDownloadBackup">Preparing download...</span>
                    </button>
                </div>
            </div>

            <!-- Action Panel 2: Restore from SQL File -->
            <div class="p-6 sm:p-7 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3.5 mb-3.5">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0 shadow-xs" style="width: 40px; height: 40px; min-width: 40px;">
                            <svg class="w-5 h-5 flex-shrink-0" width="20" height="20" style="width: 20px; height: 20px; min-width: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100">Restore Database from SQL File</h3>
                            <p class="text-xs text-slate-500">Upload & deploy an existing SQL snapshot (දත්ත ගබඩාව ප්‍රතිසාධනය කරන්න)</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                        Upload an external `.sql` backup file to revert the database state. This will execute the SQL script, resetting the application records to the exact state captured in that file.
                    </p>

                    <!-- Precaution Note Banner -->
                    <div class="p-4 mb-5 rounded-2xl bg-amber-500/10 border border-amber-500/25 text-xs text-amber-900 dark:text-amber-200">
                        <div class="font-bold flex items-center gap-2 mb-1.5 text-amber-800 dark:text-amber-300">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Restoration Precaution Note (ප්‍රවේශම් වන්න):</span>
                        </div>
                        <p class="text-[11px] leading-relaxed">
                            Restoring will overwrite current active tables. We strongly advise generating a fresh backup using the left button before deploying an older archive file.
                        </p>
                    </div>
                </div>

                <form wire:submit.prevent="restoreUploadedFile" class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <input 
                            type="file" 
                            wire:model="uploadedFile" 
                            accept=".sql"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500/15 file:text-amber-700 hover:file:bg-amber-500/25 dark:file:bg-amber-950/40 dark:file:text-amber-300 cursor-pointer" 
                        />
                    </div>
                    @error('uploadedFile') <span class="text-xs text-rose-500 font-semibold block">{{ $message }}</span> @enderror

                    @if($uploadedFile)
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:confirm="CAUTION! Restoring will overwrite existing database records with the contents of this uploaded SQL file. Are you absolutely certain you want to proceed?"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-bold text-xs shadow-md transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="restoreUploadedFile">Deploy & Restore Uploaded Database Now</span>
                            <span wire:loading wire:target="restoreUploadedFile" class="flex items-center gap-2">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Restoring database snapshot...
                            </span>
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- 4. Saved Snapshots Directory Table Card -->
        <div class="p-6 sm:p-7 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 shadow-xs flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                        <svg class="w-4 h-4 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900 dark:text-slate-100">Saved Snapshot Archives in Storage Vault</h3>
                        <p class="text-xs text-slate-500">Repository location: <code class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 font-mono text-[11px] text-slate-700 dark:text-slate-300">storage/app/backups/</code></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800">
                        {{ count($this->backups) }} Available Archive Files
                    </span>
                </div>
            </div>

            @if(empty($this->backups))
                <div class="py-16 text-center">
                    <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 shadow-xs flex-shrink-0" style="width: 48px; height: 48px; min-width: 48px;">
                        <svg class="w-6 h-6 flex-shrink-0" width="24" height="24" style="width: 24px; height: 24px; min-width: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <p class="font-bold text-base text-slate-800 dark:text-slate-200">No backup snapshots found yet</p>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                        Click "Generate Snapshot to Vault" above to create your first protected database archive.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800 shadow-xs">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-4">File Name</th>
                                <th class="px-5 py-4">Size</th>
                                <th class="px-5 py-4">Timestamp</th>
                                <th class="px-5 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($this->backups as $backup)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                    <td class="px-5 py-4 font-mono text-xs font-bold text-slate-900 dark:text-slate-100 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0" style="width: 28px; height: 28px; min-width: 28px;">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="truncate max-w-md">{{ $backup['name'] }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-xs">
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 font-mono text-[11px] font-bold text-slate-700 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                            {{ $backup['size'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-300">
                                        <div class="font-mono font-medium">{{ $backup['created_at'] }}</div>
                                        <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($backup['created_at'])->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-right space-x-2">
                                        <!-- Download Button -->
                                        <button 
                                            type="button" 
                                            wire:click="downloadBackup('{{ $backup['name'] }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800 transition shadow-xs"
                                            title="Download SQL File to Computer">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            <span>Download</span>
                                        </button>

                                        <!-- Restore Button -->
                                        <button 
                                            type="button" 
                                            wire:click="restoreBackup('{{ $backup['name'] }}')"
                                            wire:confirm="WARNING! Are you sure you want to restore the database from snapshot: {{ $backup['name'] }}? All current records will be overwritten."
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800 transition shadow-xs"
                                            title="Restore Database from this file">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            <span>Restore</span>
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            wire:click="deleteBackup('{{ $backup['name'] }}')"
                                            wire:confirm="Are you sure you want to permanently delete backup {{ $backup['name'] }}?"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800 transition shadow-xs"
                                            title="Delete Backup File Permanently">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" width="14" height="14" style="width: 14px; height: 14px; min-width: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Delete</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- 5. Security & Compliance Checklist Banner -->
        <div class="p-6 sm:p-7 rounded-3xl bg-slate-50 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400">
            <div class="flex items-center gap-2.5 mb-3 font-extrabold text-slate-900 dark:text-slate-100 text-sm">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" width="16" height="16" style="width: 16px; height: 16px; min-width: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Elahera Serisara Snapshot Protection & Enterprise Best Practices</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                    <strong class="text-slate-800 dark:text-slate-200 block mb-1">1. Off-Site Storage Redundancy</strong>
                    Download a copy of the `.sql` archive regularly to an encrypted USB or secure cloud drive for disaster protection.
                </div>
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                    <strong class="text-slate-800 dark:text-slate-200 block mb-1">2. Prior to Schema Migrations</strong>
                    Always click "Generate Snapshot to Vault" before applying database structural changes, composer upgrades, or bulk edits.
                </div>
                <div class="p-4 rounded-2xl bg-white dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                    <strong class="text-slate-800 dark:text-slate-200 block mb-1">3. Automated Cron Execution</strong>
                    To automate hourly or daily snapshots, configure Laravel's scheduler in server cron: <code class="px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-mono">php artisan schedule:run</code>.
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
