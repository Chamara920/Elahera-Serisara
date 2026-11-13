<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

/**
 * Class DatabaseBackupService
 *
 * Provides backup generation, listing, downloading, and restoration for MySQL database.
 */
class DatabaseBackupService
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (! File::isDirectory($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * Get backup directory path.
     */
    public function getBackupDir(): string
    {
        return $this->backupDir;
    }

    /**
     * Create a new database backup.
     */
    public function createBackup(): string
    {
        $filename = 'elahera_sirisara_backup_'.date('Y_m_d_His').'.sql';
        $filePath = $this->backupDir.DIRECTORY_SEPARATOR.$filename;

        // Try mysqldump first; if unsuccessful, fall back to robust PDO dump
        $success = $this->dumpViaMysqldump($filePath);
        if (! $success) {
            $this->dumpViaPdo($filePath);
        }

        return $filename;
    }

    /**
     * List all available backup files.
     */
    public function listBackups(): array
    {
        if (! File::isDirectory($this->backupDir)) {
            return [];
        }

        $files = File::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'name' => $file->getFilename(),
                    'path' => $file->getRealPath(),
                    'size' => $this->formatBytes($file->getSize()),
                    'raw_size' => $file->getSize(),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                ];
            }
        }

        usort($backups, fn ($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $backups;
    }

    /**
     * Restore database from an SQL file.
     */
    public function restoreBackup(string $filePath): void
    {
        if (! File::exists($filePath)) {
            throw new Exception("Backup file not found at: {$filePath}");
        }

        $sql = File::get($filePath);

        // Strip comments that might confuse unprepared parser
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::unprepared($sql);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup(string $filename): bool
    {
        $safeName = basename($filename);
        $filePath = $this->backupDir.DIRECTORY_SEPARATOR.$safeName;
        if (File::exists($filePath)) {
            return File::delete($filePath);
        }

        return false;
    }

    /**
     * Pure PDO SQL generator ensuring 100% portable backup without CLI dependencies.
     */
    public function dumpViaPdo(string $filePath): void
    {
        $pdo = DB::connection()->getPdo();
        $driver = DB::getDriverName();
        $tables = Schema::getTableListing();

        $handle = fopen($filePath, 'w+');
        fwrite($handle, "-- Elahera Sirisara Database Backup\n");
        fwrite($handle, '-- Generated: '.date('Y-m-d H:i:s')."\n");
        if ($driver === 'mysql') {
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($handle, "SET NAMES utf8mb4;\n\n");
        } else {
            fwrite($handle, "PRAGMA foreign_keys = OFF;\n\n");
        }

        foreach ($tables as $table) {
            // Drop existing table
            fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

            // Table Structure
            if ($driver === 'sqlite') {
                $createRow = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name = ?", [$table]);
                $createStatement = $createRow[0]->sql ?? null;
            } else {
                $createRow = DB::select("SHOW CREATE TABLE `{$table}`");
                $createStatement = ((array) $createRow[0])['Create Table'] ?? null;
            }
            if ($createStatement) {
                fwrite($handle, $createStatement.";\n\n");
            }

            // Table Data
            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                foreach ($rows->chunk(50) as $chunk) {
                    $insertStatements = [];
                    foreach ($chunk as $record) {
                        $values = [];
                        foreach ((array) $record as $val) {
                            if (is_null($val)) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = $pdo->quote($val);
                            }
                        }
                        $insertStatements[] = '('.implode(', ', $values).')';
                    }
                    if (! empty($insertStatements)) {
                        $firstRow = (array) $chunk->first();
                        $columns = array_map(fn ($c) => "`{$c}`", array_keys($firstRow));
                        $colList = implode(', ', $columns);
                        fwrite($handle, "INSERT INTO `{$table}` ({$colList}) VALUES\n".implode(",\n", $insertStatements).";\n\n");
                    }
                }
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Try mysqldump command line.
     */
    protected function dumpViaMysqldump(string $filePath): bool
    {
        $mysqldump = 'mysqldump';
        if (File::exists('C:\\wamp64\\bin\\mysql\\mysql8.4.7\\bin\\mysqldump.exe')) {
            $mysqldump = 'C:\\wamp64\\bin\\mysql\\mysql8.4.7\\bin\\mysqldump.exe';
        }

        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $user = config('database.connections.mysql.username', 'root');
        $pass = config('database.connections.mysql.password', '');
        $database = config('database.connections.mysql.database', 'elahera_sirisara');

        $passArg = $pass ? "-p\"{$pass}\"" : '';
        $cmd = "\"{$mysqldump}\" --default-character-set=utf8mb4 -h {$host} -P {$port} -u {$user} {$passArg} {$database} > \"{$filePath}\"";

        try {
            exec($cmd, $output, $returnVar);

            return $returnVar === 0 && File::exists($filePath) && File::size($filePath) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
