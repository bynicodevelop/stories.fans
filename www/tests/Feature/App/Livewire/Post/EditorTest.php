<?php

namespace Tests\Feature\App\Livewire\Post;

use App\Jobs\MediaManager;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Livewire\TemporaryUploadedFile;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class EditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_clear()
    {
        $file = TemporaryUploadedFile::fake()->image('avatar.jpg');

        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->set('content', 'new content')
            ->set('isPremium', true)
            ->set('media', $file)
            ->set('mediaTmpUrl', 'url')
            ->call('clear')
            ->assertSet('content', null)
            ->assertSet('isPremium', false)
            ->assertSet('media', null)
            ->assertSet('mediaTmpUrl', null);
    }

    public function test_uploading_true()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('uploading', true)
            ->assertSet('isUploading', true);
    }

    public function test_uploading_false()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('uploading', false)
            ->assertSet('isUploading', false);
    }

    public function test_notifyNewPostCreated()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('notifyNewPostCreated')
            ->assertEmitted('$refresh');
    }

    public function test_updatedMedia()
    {
        Storage::fake('tmp');

        $file = TemporaryUploadedFile::fake()->image('avatar.jpg');

        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->assertSet('mediaTmpUrl', null)
            ->set('media', $file)
            ->assertNotSet('mediaTmpUrl', null)
            ->assertNoRedirect();
    }

    public function test_isDisabled_to_be_disabled()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('isDisabled')
            ->assertSet('isDisabled', true);
    }

    public function test_isDisabled_to_be_not_disabled_content()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('isDisabled')
            ->set('content', 'new content')
            ->assertSet('isDisabled', false);
    }

    public function test_isDisabled_to_be_not_disabled_media()
    {
        $file = TemporaryUploadedFile::fake()->image('avatar.jpg');

        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('isDisabled')
            ->set('media', $file)
            ->assertSet('isDisabled', false);
    }

    public function test_isDisabled_to_be_not_disabled_invalid_media()
    {
        $file = TemporaryUploadedFile::fake()->image('avatar.text');

        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('isDisabled')
            ->set('media', $file)
            ->assertSet('isDisabled', true);
    }

    public function test_post_invalid_content()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->call('post')
            ->assertEmitted('showMessage', [
                "message" => "post.required-content"
            ]);
    }

    public function test_post_send_content()
    {
        Queue::fake();

        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor')
            ->set('content', 'new content')
            ->call('post')
            ->assertEmitted('showMessage', [
                "message" => "post.post-send"
            ])
            ->assertSet('content', null)
            ->assertSet('isPremium', false)
            ->assertSet('media', null)
            ->assertSet('mediaTmpUrl', null)
            ->assertSet('isDisabled', true)
            ->assertNoRedirect();

        Queue::assertPushedOn('media', MediaManager::class);
    }

    public function test_send_post_with_unauthenticated_user()
    {
        Livewire::test('post.editor')
            ->assertForbidden();
    }

    public function test_redirect_after_create_post_on_mobile()
    {
        Queue::fake();

        $this->actingAs(User::factory()->create());

        Livewire::test('post.editor', [
            'isMobile' => true,
        ])
            ->set('content', 'new content')
            ->call('post')
            ->assertRedirect('/home');
    }
}
