<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DatabaseBackupService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    /**
     * Test creating, listing, and deleting backups.
     */
    public function test_can_create_and_list_backups(): void
    {
        $service = new DatabaseBackupService();

        $filename = $service->createBackup();
        $this->assertNotEmpty($filename);
        $this->assertStringEndsWith('.sql', $filename);

        $backups = $service->listBackups();
        $this->assertNotEmpty($backups);

        $found = false;
        foreach ($backups as $b) {
            if ($b['name'] === $filename) {
                $found = true;
                $this->assertGreaterThan(0, $b['raw_size']);
                break;
            }
        }
        $this->assertTrue($found);

        // Delete test backup
        $deleted = $service->deleteBackup($filename);
        $this->assertTrue($deleted);
    }
}
