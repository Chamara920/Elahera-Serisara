<?php

namespace App\Filament\Pages;

use App\Models\BlogPost;
use App\Models\BusRoute;
use App\Models\BusStop;
use App\Models\Destination;
use App\Models\DestinationReview;
use App\Models\ForumTopic;
use App\Models\NewsEvent;
use App\Models\User;
use App\Services\DatabaseBackupService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
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

    public function getDatabaseStatsProperty(): array
    {
        $service = app(DatabaseBackupService::class);
        $backups = $service->listBackups();

        // 1. Database and Table metrics
        $tableCount = 0;
        $dbSizeMb = '0.00';
        try {
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            $sizeResult = DB::select('SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb FROM information_schema.TABLES WHERE table_schema = DATABASE()');
            $dbSizeMb = number_format($sizeResult[0]->size_mb ?? 0, 2);
        } catch (\Throwable $e) {
            // fallback
        }

        // 2. Storage used by backups
        $totalBackupBytes = 0;
        foreach ($backups as $b) {
            $totalBackupBytes += ($b['raw_size'] ?? 0);
        }
        $backupSizeFormatted = $this->formatBytes($totalBackupBytes);

        // 3. Last backup info
        $lastBackup = ! empty($backups) ? $backups[0] : null;

        // 4. Core record metrics
        $recordsCount = 0;
        try {
            $recordsCount = Destination::count()
                + BusStop::count()
                + BusRoute::count()
                + User::count()
                + BlogPost::count()
                + NewsEvent::count()
                + ForumTopic::count()
                + DestinationReview::count();
        } catch (\Throwable $e) {
        }

        return [
            'database_name' => config('database.connections.mysql.database') ?? 'elahera_sirisara',
            'driver' => config('database.default') ?? 'mysql',
            'table_count' => $tableCount,
            'db_size_mb' => $dbSizeMb,
            'total_backups' => count($backups),
            'total_backup_size' => $backupSizeFormatted,
            'last_backup' => $lastBackup,
            'total_records' => $recordsCount,
            'destinations_count' => Destination::count(),
            'bus_stops_count' => BusStop::count(),
            'bus_routes_count' => BusRoute::count(),
            'users_count' => User::count(),
            'blogs_count' => BlogPost::count(),
            'news_count' => NewsEvent::count(),
            'forum_count' => ForumTopic::count(),
            'reviews_count' => DestinationReview::count(),
        ];
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = (int) floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 2).' '.$units[$i];
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

    public function createAndDownloadBackup()
    {
        try {
            $service = app(DatabaseBackupService::class);
            $filename = $service->createBackup();
            $filePath = $service->getBackupDir().DIRECTORY_SEPARATOR.$filename;

            Notification::make()
                ->title('Backup created and downloading')
                ->body("File name: {$filename}")
                ->success()
                ->send();

            return response()->download($filePath, $filename);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Backup generation failed')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    public function downloadBackup(string $filename)
    {
        $service = app(DatabaseBackupService::class);
        $filePath = $service->getBackupDir().DIRECTORY_SEPARATOR.basename($filename);

        if (! File::exists($filePath)) {
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
            $filePath = $service->getBackupDir().DIRECTORY_SEPARATOR.basename($filename);

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
