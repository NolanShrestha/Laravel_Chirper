<?php
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public $message = '';

    public function store(): void
    {
        $validated = $this->validate();

        // Ensure the user is authenticated
        if ($user = auth()->user()) {
            // Create a chirp for the authenticated user
            $user->chirps()->create([
                'message' => $validated['message']
            ]);

            $this->message = '';
            $this->dispatch('chirp-created');
        } else {
            // Handle unauthenticated state, e.g., redirect or show an error
            session()->flash('error', __('You must be logged in to chirp.'));
        }
    }
};
?>
<div>
    <form wire:submit="store">
        <textarea
            wire:model="message"
            placeholder="{{ __('What\'s on your mind?') }}"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        ></textarea>
        <x-input-error :messages="$errors->get('message')" class="mt-2" />
        <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
    </form>
</div>
