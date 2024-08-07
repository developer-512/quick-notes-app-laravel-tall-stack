<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use function Livewire\Volt\{rules, state, usesFileUploads, placeholder,on};

placeholder('<div>Loading...</div>');
usesFileUploads();
$lastNote = \App\Models\Notes::latest()->first();
$setTitle =fn()=> 'Quick Note # ' . (($lastNote->note_id??0) + 1);
state(['title' => $setTitle, 'content', 'relevant_links', 'note', 'files','editing'=>false]);

rules(['title' => 'nullable',
    'content' => 'required|string',
    'relevant_links' => 'nullable',
    'files.*' => 'nullable'
]);

//$updatedContent=function($value)
//{
//    $this->dispatch('contentUpdated', $value);
//};

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
//    $this->note = $note;
    $this->editing=false;
    $this->content='';
    $this->relevant_links='';
    $this->title=  $this->setTitle();
    $this->files=[];
    $this->dispatch('noteCreated', $note->id);
    $this->dispatch('contentUpdated', '');

};
$update = function () {
    $this->authorize('update', $this->note);
    $validated = $this->validate();
    $note = $this->note->update($validated);
    if (is_array($this->files) && count($this->files) > 0) {
        $files_all=[];
        // Log::debug(print_r($this->files, true));
        foreach ($this->files as $file) {
            $tmpFilePath = $file['path'];
            // Define a new file name and directory
            $newFileName = uniqid() . '-' . $file['name'];
            $directory = 'public/notes/files';
            // Store the file
            $file_name = $file['name'];
            $files_all[]=$file_name;
            if($this->note->files()->where('file_name',$file_name)->count()<=0){
                $storedFilePath = Storage::putFileAs($directory, $tmpFilePath, $newFileName);
                $storedFilePath=str_replace('public/','',$storedFilePath);
                // $path = $file->store('files', 'public');

                $this->note->files()->create([
                    'file_path' => $storedFilePath,
                    'file_name' => $file_name,
                    'file_raw_data'=>json_encode($file)
                ]);
            }

        }
        $this->note->files()->whereNotIn('file_name',$files_all)->delete();
    }
    $this->editing=false;
    $this->content='';
    $this->relevant_links='';
    $this->title=  $this->setTitle();;
    $this->files=[];
    $this->dispatch('contentUpdated', '');
    $this->dispatch('note-updated');

};
on(['edit-this-note' => function (\App\Models\Notes $note) {
    $this->editing=true;
    $this->note=$note;
    $this->loadNote($note);
}
]);

$loadNote=function ($note){
    $this->content=$note->content??'';
    $this->dispatch('contentUpdated', $this->content);
    $this->relevant_links=$note->relevant_links??'';
    $this->title= $note->title??'';
    foreach ($note->files as $file){
        /**
        file_raw_data={"tmpFilename":"2tSOeGDHuFlSk97frzffVM2oUGtRCm-metaU2NyZWVuc2hvdCAyMDI0LTA3LTA0IDAwNDAyOS5wbmc=-.png",
         * "name":"Screenshot 2024-07-04 004029.png",
         * "extension":"png",
         * "path":"C:\\Users\\HP\\Herd\\note-app\\storage\\app\\livewire-tmp\/2tSOeGDHuFlSk97frzffVM2oUGtRCm-metaU2NyZWVuc2hvdCAyMDI0LTA3LTA0IDAwNDAyOS5wbmc=-.png",
         * "temporaryUrl":"https:\/\/note-app.test\/livewire\/preview-file\/2tSOeGDHuFlSk97frzffVM2oUGtRCm-metaU2NyZWVuc2hvdCAyMDI0LTA3LTA0IDAwNDAyOS5wbmc=-.png?expires=1722794399&signature=cd36e966d2d715f5b61b9004a066718baff150e29641a420af38e9162455ef8b","size":62777}
         */
        $output_file=json_decode($file->file_raw_data,true);
        $output_file['tmpFilename']=$file->file_name;
        $output_file['name']=$file->file_name;
        $output_file['path']=$file->file_path;
        $output_file['temporaryUrl']=$file->download_url;
        $this->files[]=$output_file;
    }
};
$cancel = function (){
    $this->editing=false;
    $this->dispatch('note-edit-canceled');
};
?>

<div class="bg-white">
    <form wire:submit="{{($editing?'update':'store')}}" >
        <input type="text" wire:model="title"
               placeholder="{{__('Note Title')}}"
               class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mb-4">
       <div wire:ignore >
           <textarea rows="6" style="height: 250px" id="editor" wire:model.lazy="content"
                     {{--                  wire:model="content"--}}
                     placeholder="{{ __('What\'s on your mind?') }}"
                     class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-4"
           ></textarea>
       </div>
{{--        <livewire:trix :value="$content" wire:model="content"/>--}}
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
        @if($editing)
            <x-primary-button class="mt-4">{{ __('Update Note') }}</x-primary-button>
            <button class="mt-4" wire:click.prevent="cancel">Cancel</button>
        @else

        <x-primary-button class="mt-4">{{ __('Save Note') }}</x-primary-button>
        @endif
{{--        {{print_r($errors,true)}}--}}
    </form>
</div>

<script type="module">
    // console.log(window.ckeditor);
    window.ckeditor.model.document.on( 'change:data', () => {
        @this.set('content', window.ckeditor.getData());

    });
    window.Livewire.on('noteCreated', () => {
         window.ckeditor.setData('');
    });
    window.Livewire.on('contentUpdated', content => {
        window.ckeditor.setData(content[0]);
    });
</script>
