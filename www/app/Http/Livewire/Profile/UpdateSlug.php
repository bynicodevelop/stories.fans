<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateSlug extends Component
{
    /**
     * @var String
     */
    public $slug;

    /**
     * @var User
     */
    public $user;

    /**
     *
     * @var boolean
     */
    public $isDisabled;

    public function mount()
    {
        $this->user = Auth::user();

        $this->slug = $this->user['slug'];
    }

    protected function rules(): array
    {
        return [
            'slug' => "required|unique:users,slug,{$this->user['id']}",
        ];
    }

    protected function messages(): array
    {
        return [
            'slug.required' => __('profile.slug-required'),
            'slug.unique' => __('profile.slug-unique'),
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function updateSlug()
    {
        $this->user->update([
            'slug' => $this->slug,
        ]);

        $this->emit('saved');
    }

    public function render()
    {
        return view('profile.update-slug');
    }
}
