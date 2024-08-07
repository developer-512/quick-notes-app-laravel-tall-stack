<?php

use function Livewire\Volt\{state,on,computed};
use App\Models\Notes;

state(['images_extensions'=>fn()=>['png','jpg','gif','webp']]);
state(['search'=>''])->url();
$get_notes = fn () => $this->notes = auth()->user()->notes()->where('content', 'like', '%'.$this->search.'%')->with('files')->orderBy('note_id','desc')->get();
state(['notes'=>$get_notes,'editing' => null]);

$edit=function (Notes $note){
    $this->editing = null;
    $this->dispatch('edit-this-note',$note->note_id);
    $this->get_notes();
};
$delete = function (Notes $note) {
    $this->authorize('delete', $note);

    $note->delete();

    $this->get_notes();
};
$disableEditing = function () {
    $this->editing = null;

    return $this->get_notes();
};
$updatedSearch=function (){
   return $this->get_notes();
};

on([
    'noteCreated' => $get_notes,
    'note-updated' => $disableEditing,
    'note-edit-canceled' => $disableEditing,
]);
?>

<div class=" bg-white shadow-sm rounded-lg divide-y" >
    <input wire:model.live="search" type="search"
           class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
           placeholder="Search ...">

@foreach ($notes as $note)
        <div class="p-6 flex space-x-2" wire:key="{{ $note->note_id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-800">{{ $note->user->name }}</span>
                        <small class="ml-2 text-sm text-gray-600">{{ $note->created_at->format('j M Y, g:i a') }} </small>
                        <small class="ml-2 text-sm text-gray-600" >{{ $note->title }} </small>
                        @unless ($note->created_at->eq($note->updated_at))
                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                        @endunless
                    </div>
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link wire:click="edit({{ $note->note_id }})">
                                {{ __('Edit') }}
                            </x-dropdown-link>
                            <x-dropdown-link wire:click="delete({{ $note->note_id }})" wire:confirm="Are you sure to delete this Note?">
                                {{ __('Delete') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                    <p class="mt-4 text-lg text-gray-900">{!! $note->content !!}</p>
                <hr>
                    <p class="mt-4 text-md text-gray-900">{{ $note->relevant_links }}</p>

                @if(count($note->files)>0)
                    <hr>
                        <h3>Files:</h3>
                    <div class="files flex justify-between items-center">

                            @foreach($note->files as $file)
                                <a href="{{ $file->download_url }}" title="{{$file->file_name}}" download>
                                    @if(in_array(\Illuminate\Support\Str::lower($file->extension),$images_extensions))
                                        <img src="{{ $file->download_url }}" alt="{{$file->file_name}}" height="100">
                                    @else
                                            {{\Illuminate\Support\Str::limit($file->file_name,50)}}
                                    @endif

                                </a>
                            @endforeach

                    </div>
                    @endif


            </div>
        </div>
    @endforeach
</div>
