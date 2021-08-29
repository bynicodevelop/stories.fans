<?php

namespace App\Http\Livewire\Post;

use App\Events\PostCreatedEvent;
use App\Jobs\MediaManager;
use App\Models\Media;
use ContentRequiresException;
use Facades\Livewire\GenerateSignedUploadUrl;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\FileUploadConfiguration;
use Livewire\WithFileUploads;

class Editor extends Component
{
    use WithFileUploads;

    public function startUpload($name, $fileInfo, $isMultiple)
    {
        if (FileUploadConfiguration::isUsingS3()) {
            throw_if($isMultiple, S3DoesntSupportMultipleFileUploads::class);

            $file = UploadedFile::fake()->create($fileInfo[0]['name'], $fileInfo[0]['size'] / 1024, $fileInfo[0]['type']);

            $this->emit('upload:generatedSignedUrlForS3', $name, GenerateSignedUploadUrl::forS3($file))->self();

            return;
        }

        $this->emit('upload:generatedSignedUrl', $name, GenerateSignedUploadUrl::forLocal())->self();
    }

    public function getListeners()
    {
        return [
            'echo-private:post-created-event,PostCreatedEvent' => 'notifyNewPostCreated',
            'preUploadMedia',
        ];
    }

    /**
     *
     * @var String $content
     */
    public $content;

    /**
     *
     * @var boolean $isPremium
     */
    public $isPremium = false;

    /**
     *
     * @var boolean $isDisabled
     */
    public $isDisabled = false;

    /**
     *
     * @var User $user
     */
    public $user;

    public $media;

    protected function rules(): array
    {
        return [
            'media' => config('livewire.temporary_file_upload.rules')
        ];
    }

    // TODO: Ne fonctionne pas pourquoi ?
    protected function messages(): array
    {
        return [
            "media.dimensions" => __("post.media-dimensions"),
            "media.max" => __("post.media-max"),
        ];
    }

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function notifyNewPostCreated()
    {
        Log::debug("refresh");
        $this->emitTo('post.feed', '$refresh');
    }

    public function updatedMedia()
    {
        $this->validate();
    }

    public function post()
    {
        if (empty($this->content) && empty($this->media)) {
            throw new ContentRequiresException();
        }

        $post = $this->user->posts()->create([
            "content" => empty($this->content) ? null : $this->content,
            "is_premium" => $this->isPremium,
        ]);

        if (!empty($this->media)) {
            $typeMimeExploded = explode('/', $this->media->getMimeType());

            // $fileManager = new MediaManager(
            //     $post,
            //     strtolower($typeMimeExploded[0]) == 'video' ? Media::VIDEO : Media::IMAGE,
            //     $this->media->getFileName(),
            //     $this->media->getClientOriginalExtension()
            // );

            // $fileManager->handle();
            // dd("ok");

            dispatch(new MediaManager(
                $post,
                strtolower($typeMimeExploded[0]) == 'video' ? Media::VIDEO : Media::IMAGE,
                $this->media->getFileName(),
                $this->media->getClientOriginalExtension()
            ))->onQueue('media');

            $this->emitTo('alert-component', 'showMessage', [
                "message" => "post.post-send"
            ]);
        } else {
            $this->notifyNewPostCreated();
        }

        $this->content = "";
        $this->isPremium = false;

        $this->media = null;
    }

    public function isDisabled()
    {
        if (empty($this->content) && empty($this->media)) {
            $this->isDisabled = true;
            return;
        }

        $this->isDisabled = false;
    }

    public function render()
    {
        $this->isDisabled();

        $havePlan = $this->user->plans()->count() > 0;

        return view('livewire.post.editor', compact('havePlan'));
    }
}
