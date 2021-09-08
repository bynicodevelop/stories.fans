<?php

namespace App\Http\Livewire\Post;

use App\Jobs\MediaManager;
use App\Models\Media;
use App\Models\Post;
use App\Traits\MediaHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Editor extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use MediaHelper;

    public function getListeners()
    {
        return [
            // 'echo-private:refresh-posts-create,RefreshPostsEvent' => 'notifyNewPostCreated',
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
     * @var boolean $isDisabled
     */
    public $isUploading = false;

    /**
     *
     * @var User $user
     */
    public $user;

    public $media;

    /**
     * @var string
     */
    public $mediaTmpUrl;

    /**
     * @var string
     */
    public $attributes;

    /**
     * @var boolean
     */
    public $isMobile = false;

    /**
     * @var boolean
     */
    public $havePlan = false;

    protected function rules(): array
    {
        return [
            'media' => config('livewire.temporary_file_upload.rules')
        ];
    }

    // TODO: Ne fonctionne pas ! pourquoi ?
    protected function messages(): array
    {
        return [
            "media.dimensions" => __("post.media-dimensions"),
            "media.max" => __("post.media-max"),
        ];
    }

    public function mount($attributes = null, $isMobile = false)
    {
        $this->authorize('create', Post::class);

        /**
         * @var User $user
         */
        $this->user = Auth::user();

        $this->havePlan = $this->user->plans()->count() > 0;
        $this->attributes = $attributes;
        $this->isMobile = $isMobile;
    }

    public function clear(): void
    {
        $this->content = null;
        $this->isPremium = false;
        $this->media = null;
        $this->mediaTmpUrl = null;
    }

    public function notifyNewPostCreated()
    {
        Log::debug("refresh");

        $this->emitTo('post.feed', '$refresh');
    }

    public function updatedMedia()
    {
        $this->validate();

        $this->mediaTmpUrl = $this->media->temporaryUrl();
    }

    public function isDisabled()
    {
        if (empty($this->content) && empty($this->media)) {
            $this->isDisabled = true;
            return;
        }

        $this->isDisabled = false;
    }

    public function getTypeMedia(string $mimeType): string
    {
        $typeMimeExploded = explode('/', $mimeType);

        return strtolower($typeMimeExploded[0]) == 'video' ? Media::VIDEO : Media::IMAGE;
    }


    public function uploading(bool $value)
    {
        $this->isUploading = $value;
    }

    public function post()
    {
        if (empty($this->content) && empty($this->media)) {
            $this->emitTo('alert-component', 'showMessage', [
                "message" => "post.required-content"
            ]);

            return;
        }

        extract($this->getMediaProperties($this->media));

        // $fileManager = new MediaManager(
        //     $this->user,
        //     empty($this->content) ? null : $this->content,
        //     $this->isPremium,
        //     $typeMedia,
        //     $fileName,
        //     $extFile
        // );

        // $fileManager->handle();
        // dd("ok");

        dispatch(new MediaManager(
            $this->user,
            empty($this->content) ? null : $this->content,
            $this->isPremium,
            $typeMedia,
            $fileName,
            $extFile
        ))->onQueue('media');

        $this->emitTo('alert-component', 'showMessage', [
            "message" => "post.post-send"
        ]);

        $this->clear();
        $this->isDisabled();
        $this->dispatchBrowserEvent('clear');

        if ($this->isMobile) {
            return redirect()->route('home');
        }
    }

    public function render()
    {
        $this->isDisabled();

        return view('livewire.post.editor');
    }
}
