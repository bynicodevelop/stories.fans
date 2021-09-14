<?php

namespace Tests\Feature\App\Services;

use App\Services\VideoConversionService;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\Format\FormatInterface;
use FFMpeg\Format\Video\X264;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Tests\TestCase;

class VideoConversionServiceTest extends TestCase
{
    public function test_convert()
    {
        Storage::fake('local');

        $baseName = "123456";
        $fileNameStart = "file.mp4";

        $file = UploadedFile::fake()->create($fileNameStart);

        Storage::disk('local')->put("tmp/{$fileNameStart}", $file);

        $dimensions = Mockery::mock(Dimension::class);
        $dimensions->shouldReceive('getWidth')->once()->andReturn("1920");
        $dimensions->shouldReceive('getheight')->once()->andReturn("1080");

        $stream = Mockery::mock(Stream::class);
        $stream->shouldReceive('getDimensions')->once()->andReturn($dimensions);
        $stream->shouldReceive('get')->with('r_frame_rate')->once()->andReturn("30/1");

        $mediaOpener = Mockery::mock(MediaOpener::class);
        $mediaOpener->shouldReceive('getVideoStream')->once()->andReturn($stream);

        $HLSExporter = Mockery::mock(HLSExporter::class);
        $HLSExporter->shouldReceive('withRotatingEncryptionKey')->once()->andReturnSelf();
        $HLSExporter->shouldReceive('addFormat')->times(3)->with(Mockery::type(FormatInterface::class))->andReturnSelf();

        $mediaOpener->shouldReceive('exportForHLS')->once()->andReturn($HLSExporter);

        $HLSExporter->shouldReceive('toDisk')->with("local")->once()->andReturnSelf();
        $HLSExporter->shouldReceive('save')->with("private/{$baseName}/{$baseName}.m3u8")->once();

        $ffmpegMock = $this->createPartialMock(FFMpeg::class, []);
        $ffmpegMock->shouldReceive('fromDisk')->with('local')->times(2)->andReturnSelf();
        $ffmpegMock->shouldReceive('open')->with("tmp/{$fileNameStart}")->times(2)->andReturn($mediaOpener);

        $videoConversionService = new VideoConversionService();

        $videoConversionService
            ->fromDisk('local')
            ->open("tmp/{$fileNameStart}")
            ->convertInFormats([1920, 1280, 854])
            ->baseName($baseName)
            ->setExtension("m3u8")
            ->toDisk('local')
            ->save("private/");
    }
}
