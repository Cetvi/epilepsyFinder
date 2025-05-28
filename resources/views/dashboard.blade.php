<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 flex items-center space-x-2">
                <button
                    class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-blue-600 new-project-button"
                    onclick="window.location.href='{{ route('projects.create') }}'">
                    +
                </button>
                <span>{{ __("Let's create a new project") }}</span>
            </div>

        </div>
    </div>
</x-app-layout>
