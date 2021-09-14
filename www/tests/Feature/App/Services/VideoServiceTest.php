<?php

namespace Tests\Feature\App\services;

use App\Exceptions\ExtractPreviewAtRequiresException;
use App\Models\Media;
use App\Services\VideoService;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFProbe\DataMapping\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Mockery;
use ProtoneMedia\LaravelFFMpeg\Exporters\MediaExporter;
use ProtoneMedia\LaravelFFMpeg\MediaOpener;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Psr\Http\Message\StreamInterface;
use Tests\TestCase;

class VideoServiceTest extends TestCase
{
    public function test_make_sur_extractPreviewAt_is_define()
    {
        Storage::fake('local');

        $this->expectException(ExtractPreviewAtRequiresException::class);

        $fileNameStart = "file.mp4";

        $mediaOpener = Mockery::mock(MediaOpener::class);

        $ffmpegMock = $this->createPartialMock(FFMpeg::class, []);
        $ffmpegMock->shouldReceive('fromDisk')->with('local')->once()->andReturnSelf();
        $ffmpegMock->shouldReceive('open')->with("tmp/{$fileNameStart}")->once()->andReturn($mediaOpener);

        $videoService = new VideoService();

        $videoService
            ->fromDisk('local')
            ->open("tmp/{$fileNameStart}");
    }

    /**
     * Vérifie que le service peu générer une preview
     */
    public function test_generatePreview()
    {
        Storage::fake('local');

        $baseName = "123456";
        $fileNameStart = "file.mp4";

        $file = UploadedFile::fake()->create($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $dimensions = Mockery::mock(Dimension::class);
        $dimensions->shouldReceive('getWidth')->once()->andReturn("1280");

        $stream = Mockery::mock(Stream::class);
        $stream->shouldReceive('getDimensions')->once()->andReturn($dimensions);

        $mediaExporter = Mockery::mock(MediaExporter::class);
        $mediaExporter->shouldReceive('getFrameContents')->once();

        $mediaOpener = Mockery::mock(MediaOpener::class);
        $mediaOpener->shouldReceive('getDurationInSeconds')->once()->andReturn(10);
        $mediaOpener->shouldReceive('getVideoStream')->once()->andReturn($stream);
        $mediaOpener->shouldReceive('getFrameFromSeconds')->with(10 * .33)->once()->andReturnSelf();
        $mediaOpener->shouldReceive('export')->once()->andReturn($mediaExporter);

        $ffmpegMock = $this->createPartialMock(FFMpeg::class, []);
        $ffmpegMock->shouldReceive('fromDisk')->with('local')->once()->andReturnSelf();
        $ffmpegMock->shouldReceive('open')->with("tmp/{$fileNameStart}")->once()->andReturn($mediaOpener);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();
        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();

        $mockImage->shouldReceive('resize')->with(720, null, Mockery::type('Closure'))->once();

        $stream = $mockImage->shouldReceive('stream')->once()->andReturn($this->createMock(StreamInterface::class, []));
        $stream->shouldReceive('detach')->andReturn(fopen('php://memory', 'r+'));

        $videoService = new VideoService();

        $videoService
            ->fromDisk('local')
            ->extractPreviewAt(.33)
            ->open("tmp/{$fileNameStart}")
            ->baseName($baseName)
            ->setExtension('jpg')
            ->fileName('-preview')
            ->resize(720)
            ->save("private/");

        Storage::disk('local')->assertExists("private/{$baseName}/{$baseName}-preview.jpg");
    }

    public function test_getOrientation_PROTRAIT()
    {
        Storage::fake('local');

        $baseName = "123456";
        $fileNameStart = "file.mp4";

        $file = UploadedFile::fake()->create($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $dimensions = Mockery::mock(Dimension::class);
        $dimensions->shouldReceive('getWidth')->once()->andReturn("1280");

        $stream = Mockery::mock(Stream::class);
        $stream->shouldReceive('getDimensions')->once()->andReturn($dimensions);

        $mediaExporter = Mockery::mock(MediaExporter::class);
        $mediaExporter->shouldReceive('getFrameContents')->once();

        $mediaOpener = Mockery::mock(MediaOpener::class);
        $mediaOpener->shouldReceive('getDurationInSeconds')->once()->andReturn(10);
        $mediaOpener->shouldReceive('getVideoStream')->once()->andReturn($stream);
        $mediaOpener->shouldReceive('getFrameFromSeconds')->with(10 * .33)->once()->andReturnSelf();
        $mediaOpener->shouldReceive('export')->once()->andReturn($mediaExporter);

        $ffmpegMock = $this->createPartialMock(FFMpeg::class, []);
        $ffmpegMock->shouldReceive('fromDisk')->with('local')->once()->andReturnSelf();
        $ffmpegMock->shouldReceive('open')->with("tmp/{$fileNameStart}")->once()->andReturn($mediaOpener);

        $imageMock = $this->createPartialMock(Image::class, []);

        $mockImage = $imageMock->shouldReceive('make')->once()->andReturnSelf();
        $mockImage->shouldReceive('orientate')->once()->andReturnSelf();
        $mockImage->shouldReceive('getWidth')->once()->andReturn(1280);
        $mockImage->shouldReceive('getHeight')->once()->andReturn(720);

        $videoService = new VideoService();

        $videoService
            ->fromDisk('local')
            ->extractPreviewAt(.33)
            ->open("tmp/{$fileNameStart}");

        $this->assertEquals($videoService->getOrientation(), Media::LANDSCAPE);
    }
}
