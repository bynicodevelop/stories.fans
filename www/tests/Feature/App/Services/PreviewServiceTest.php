<?php

namespace Tests\Feature\App\Services;

use App\Services\PreviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PreviewServiceTest extends TestCase
{
    /**
     * Doit vérifier que le fichier est bien déplacé
     * d'un dossier à un autre avec une visibilité public
     */
    // public function test_create_public_preview()
    // {
    //     Storage::fake('local');

    //     $fileNameStart = "file.jpg";
    //     $fileNameEnd = "123456.jpg";

    //     $file = UploadedFile::fake()->image($fileNameStart);

    //     Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

    //     $previewService = new PreviewService();

    //     $previewService
    //         ->open("tmp/{$fileNameStart}")
    //         ->fromDisk('local')
    //         ->toDisk('local')
    //         ->save("private/{$fileNameEnd}");

    //     Storage::disk('local')->assertExists("private/{$fileNameEnd}");

    //     $this->assertEquals(Storage::disk('local')->getVisibility("private/{$fileNameEnd}"), 'public');
    // }

    /**
     * Doit vérifier que le fichier est bien déplacé
     * d'un dossier à un autre avec une visibilité privé
     */
    public function test_create_private_preview()
    {
        Storage::fake('local');

        $fileNameStart = "file.jpg";
        $fileNameEnd = "123456";

        $file = UploadedFile::fake()->image($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $previewService = new PreviewService();

        $previewService
            ->open("tmp/{$fileNameStart}")
            ->setVisibility('private')
            ->fromDisk('local')
            ->toDisk('local')
            ->fileName($fileNameEnd)
            ->save("private/");

        Storage::disk('local')->assertExists("private/{$fileNameEnd}/{$fileNameEnd}-preview.jpg");

        $this->assertEquals(Storage::disk('local')->getVisibility("private/{$fileNameEnd}-{$fileNameEnd}-preview.jpg"), 'private');
    }

    /**
     * Controlle la taille de l'image
     */
}
