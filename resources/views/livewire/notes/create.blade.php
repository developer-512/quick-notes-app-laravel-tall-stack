<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use function Livewire\Volt\{rules, state, usesFileUploads, placeholder};

placeholder('<div>Loading...</div>');
usesFileUploads();
$lastNote = \App\Models\Notes::latest()->first();
$title = 'Quick Note # ' . (($lastNote->note_id??0) + 1);
state(['title' => fn() => $title, 'content', 'relevant_links', 'note', 'files','edit_note']);

rules(['title' => 'nullable',
    'content' => 'required|string',
    'relevant_links' => 'nullable',
    'files.*' => 'nullable'
]);


$store = function () {
    $validated = $this->validate();
    $note = auth()->user()->notes()->create($validated);
    if (is_array($this->files) && count($this->files) > 0) {
       // Log::debug(print_r($this->files, true));
        foreach ($this->files as $file) {
            $tmpFilePath = $file['path'];
            // Define a new file name and directory
            $newFileName = uniqid() . '-' . $file['name'];
            $directory = 'public/notes/files';
            // Store the file
            $storedFilePath = Storage::putFileAs($directory, $tmpFilePath, $newFileName);
            $storedFilePath=str_replace('public/','',$storedFilePath);
           // $path = $file->store('files', 'public');
            $file_name = $file['name'];
               $note->files()->create([
                   'file_path' => $storedFilePath,
                   'file_name' => $file_name,
                   'file_raw_data'=>json_encode($file)
               ]);
        }
    }
    $this->note = $note;
    $this->content='';
    $this->relevant_links='';
    $this->title= 'Quick Note # ' . ($note->note_id + 1);;
    $this->files=[];
    $this->dispatch('noteCreated', $note->id);

};


?>

<div>
    <form wire:submit="store" data-test="{{$edit_note}}">
        <input type="text" wire:model="title"
               placeholder="{{__('Note Title')}}"
               class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        <textarea rows="6"
                  wire:model="content"
                  placeholder="{{ __('What\'s on your mind?') }}"
                  class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-4"
        ></textarea>
        <x-input-error :messages="$errors->get('content')" class="mt-2"/>
        <textarea
            wire:model="relevant_links"
            placeholder="{{ __('Other Details') }}"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-4 mb-4"
        ></textarea>
        {{--        <input type="file" wire:model="files" class="block w-full mt-4" multiple>--}}
        <livewire:dropzone
            wire:model="files"
            :multiple="true" class="block w-full mt-4"/>
        <x-input-error :messages="$errors->get('files')" class="mt-2"/>
        <x-primary-button class="mt-4">{{ __('Save Note') }}</x-primary-button>
{{--        {{print_r($errors,true)}}--}}
    </form>
</div>
