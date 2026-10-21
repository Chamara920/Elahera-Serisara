<?php

namespace App\Filament\Pages;

use App\Services\DatabaseBackupService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use UnitEnum;

class DatabaseBackupPage extends Page
{
    use WithFileUploads;

    protected static string|UnitEnum|null $navigationGroup = 'දත්ත ගබඩාව (Database)';

    protected static ?string $navigationLabel = 'Backup සහ Restore';

    protected static ?string $title = 'දත්ත ගබඩාව Backup සහ Restore (Database Backup & Restore)';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'database-backup-restore';

    protected string $view = 'filament.pages.database-backup';

    /**
     * @var mixed
     */
    public $uploadedFile;

    public function getBackupsProperty(): array
    {
        $service = app(DatabaseBackupService::class);
        return $service->listBackups();
    }

    public function createBackup(): void
    {
        try {
            $service = app(DatabaseBackupService::class);
            $filename = $service->createBackup();

            Notification::make()
                ->title('Backup සාර්ථකව නිමවන ලදී (Backup Created)')
                ->body("ගොනුවේ නම: {$filename}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Backup අසාර්ථක විය (Backup Failed)')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function downloadBackup(string $filename)
    {
        $service = app(DatabaseBackupService::class);
        $filePath = $service->getBackupDir() . DIRECTORY_SEPARATOR . basename($filename);

        if (!File::exists($filePath)) {
            Notification::make()
                ->title('ගොනුව හමු නොවීය')
                ->danger()
                ->send();
            return null;
        }

        return response()->download($filePath, basename($filename));
    }

    public function restoreBackup(string $filename): void
    {
        try {
            $service = app(DatabaseBackupService::class);
            $filePath = $service->getBackupDir() . DIRECTORY_SEPARATOR . basename($filename);

            $service->restoreBackup($filePath);

            Notification::make()
                ->title('දත්ත ගබඩාව සාර්ථකව Restore කරන ලදී (Database Restored)')
                ->body("{$filename} ගොනුවෙන් දත්ත සාර්ථකව ප්‍රතිස්ථාපනය කෙරිණි.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Restore අසාර්ථක විය (Restore Failed)')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function deleteBackup(string $filename): void
    {
        $service = app(DatabaseBackupService::class);
        $deleted = $service->deleteBackup($filename);

        if ($deleted) {
            Notification::make()
                ->title('Backup ගොනුව සාර්ථකව මකා දමන ලදී (Backup Deleted)')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('ගොනුව මකා දැමීමට නොහැකි විය')
                ->danger()
                ->send();
        }
    }

    public function restoreUploadedFile(): void
    {
        $this->validate([
            'uploadedFile' => 'required|file|max:51200', // 50MB max
        ]);

        try {
            $service = app(DatabaseBackupService::class);
            $tempPath = $this->uploadedFile->getRealPath();

            $service->restoreBackup($tempPath);

            $this->reset('uploadedFile');

            Notification::make()
                ->title('Upload කළ SQL ගොනුවෙන් දත්ත ගබඩාව සාර්ථකව Restore කරන ලදී')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Upload Restore අසාර්ථක විය')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
