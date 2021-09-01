<?php

namespace Tests\Feature\App\Livewire\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PostCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_unautenticated_case()
    {
        Livewire::test('post.post-comment', [
            'post' => new Post([
                'id' => 1
            ])
        ])->assertDontSeeHtml('<form wire:submit.prevent="sendComment">');
    }

    public function test_clear()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.post-comment', [
            'post' => new Post([
                'id' => 1
            ])
        ])
            ->set('comment', 'new comment')
            ->set('isDisabled', false)
            ->call('clear')
            ->assertSet('comment', null)
            ->assertSet('isDisabled', true);
    }

    public function test_isDisabled_property()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.post-comment', [
            'post' => new Post([
                'id' => 1
            ])
        ])
            ->assertSet('isDisabled', true)
            ->set('comment', 'new comment')
            ->assertSet('isDisabled', false);
    }

    public function test_sendComment_without_comment()
    {
        $this->actingAs(User::factory()->create());

        Livewire::test('post.post-comment', [
            'post' => new Post([
                'id' => 1
            ])
        ])->call('sendComment')
            ->assertEmitted('showMessage', [
                "message" => "post.required-comment"
            ]);
    }



    public function test_sendComment_comment()
    {
        $user = User::factory()->has(Post::factory()->count(1))->create();

        $this->actingAs($user);

        Livewire::test('post.post-comment', [
            'post' => $user['posts'][0]
        ])->set('comment', 'new comment')
            ->call('sendComment')
            ->assertEmitted('$refresh')
            ->assertSet('comment', null)
            ->assertSet('isDisabled', true);

        $this->assertEquals($user['posts'][0]->comments()->count(), 1);
    }
}
