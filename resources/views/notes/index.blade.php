<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quick Notes') }}
        </h2>
    </x-slot>
    @php $edit_note='xyz'; @endphp
    <div class="max-w-full row-auto mx-auto p-4 sm:p-6 lg:p-8">
        <div class="grid grid-cols-2 gap-4">
        <div class="col-auto">
            <livewire:notes.create :edit_note="$edit_note" />
        </div>
        <div class="max-h-screen scroll-smooth overflow-y-auto [&::-webkit-scrollbar]:[width:2px]
            [&::-webkit-scrollbar-thumb]:bg-red-500">
        <livewire:notes.list :edit_note="$edit_note" />
        </div>
        </div>
    </div>
</x-app-layout>
