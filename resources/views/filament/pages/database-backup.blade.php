<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Top Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- 1. Create Backup Card -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                            💾
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-slate-100">Create Full Database Backup</h3>
                            <p class="text-xs text-slate-500">Export Entire Database as .SQL</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                        Generate a complete snapshot of all destinations, bus routes, blog posts, forum messages, user accounts, and system configuration in a secure SQL file.
                    </p>
                </div>

                <div>
                    <button 
                        type="button" 
                        wire:click="createBackup" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-sm shadow-sm transition disabled:opacity-50">
                        <span wire:loading.remove wire:target="createBackup">🚀 Generate Backup Now</span>
                        <span wire:loading wire:target="createBackup">⏳ Generating backup...</span>
                    </button>
                </div>
            </div>

            <!-- 2. Upload & Restore Card -->
            <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                            📤
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900 dark:text-slate-100">Restore from SQL File</h3>
                            <p class="text-xs text-slate-500">Upload & Restore External .SQL File</p>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-4">
                        Upload a previously exported database backup from your computer to restore all tables and data immediately.
                    </p>
                </div>

                <form wire:submit.prevent="restoreUploadedFile" class="space-y-3">
                    <input 
                        type="file" 
                        wire:model="uploadedFile" 
                        accept=".sql"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-slate-800 dark:file:text-slate-200 cursor-pointer" 
                    />
                    @error('uploadedFile') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror

                    @if($uploadedFile)
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:confirm="Warning! Restoring will overwrite existing database records with the contents of this SQL file. Do you wish to proceed?"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs shadow-sm transition disabled:opacity-50">
                            <span wire:loading.remove wire:target="restoreUploadedFile">⚠️ Upload & Restore Now</span>
                            <span wire:loading wire:target="restoreUploadedFile">⏳ Restoring database...</span>
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Backups List Table Card -->
        <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🗄️</span>
                    <h3 class="font-bold text-base text-slate-900 dark:text-slate-100">Available Database Backups</h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        {{ count($this->backups) }} Backups
                    </span>
                </div>
            </div>

            @if(empty($this->backups))
                <div class="py-12 text-center text-slate-500 dark:text-slate-400">
                    <p class="text-3xl mb-2">📁</p>
                    <p class="font-medium text-sm">No backup files found yet.</p>
                    <p class="text-xs text-slate-400 mt-1">Click "Generate Backup Now" above to create your first database snapshot.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg">File Name</th>
                                <th class="px-4 py-3">File Size</th>
                                <th class="px-4 py-3">Created Date & Time</th>
                                <th class="px-4 py-3 text-right rounded-r-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($this->backups as $backup)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                    <td class="px-4 py-3.5 font-mono text-xs font-medium text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                        <span class="text-emerald-500">📄</span>
                                        {{ $backup['name'] }}
                                    </td>
                                    <td class="px-4 py-3.5 text-xs text-slate-600 dark:text-slate-300">
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 font-mono text-[11px]">
                                            {{ $backup['size'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs text-slate-600 dark:text-slate-300 font-mono">
                                        {{ $backup['created_at'] }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right space-x-2">
                                        <!-- Download Button -->
                                        <button 
                                            type="button" 
                                            wire:click="downloadBackup('{{ $backup['name'] }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition"
                                            title="Download SQL File">
                                            ⬇️ Download
                                        </button>

                                        <!-- Restore Button -->
                                        <button 
                                            type="button" 
                                            wire:click="restoreBackup('{{ $backup['name'] }}')"
                                            wire:confirm="Warning! Are you sure you want to restore the database from {{ $backup['name'] }}? Existing data will be overwritten."
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 transition"
                                            title="Restore Database">
                                            🔄 Restore
                                        </button>

                                        <!-- Delete Button -->
                                        <button 
                                            type="button" 
                                            wire:click="deleteBackup('{{ $backup['name'] }}')"
                                            wire:confirm="Are you sure you want to permanently delete backup {{ $backup['name'] }}?"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 transition"
                                            title="Delete Backup File">
                                            🗑️ Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
