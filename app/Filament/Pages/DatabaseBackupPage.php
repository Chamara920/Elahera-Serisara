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

    protected static string|UnitEnum|null $navigationGroup = 'Database';

    protected static ?string $navigationLabel = 'Backup & Restore';

    protected static ?string $title = 'Database Backup & Restore';

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
                ->title('Backup created successfully')
                ->body("File name: {$filename}")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Backup generation failed')
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
                ->title('Backup file not found')
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
                ->title('Database restored successfully')
                ->body("Database successfully restored from {$filename}.")
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Database restoration failed')
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
                ->title('Backup file deleted successfully')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Could not delete backup file')
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
                ->title('Database successfully restored from uploaded SQL file')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Upload restoration failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
