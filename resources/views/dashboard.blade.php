<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Last Project: ocupa todo el ancho -->
            <div class="w-full bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                <div id="cardLastProject" class="flex flex-col justify-between h-full">
                </div>
            </div>

            <!-- Fila con dos paneles: Create new project y Tutorial -->
            <div class="flex flex-col lg:flex-row gap-6">
                <button onclick="window.location.href='{{ route('projects.create') }}'"
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex justify-between items-center w-full lg:w-1/2 text-left hover:bg-gray-50 transition">
                    <span class="text-gray-900 text-lg font-medium">
                        {{ __("Let's create a new project") }}
                    </span>
                    <span class="text-blue-500 hover:text-blue-700 text-xl">
                        →
                    </span>
                </button>
                <button id = "tutorialButton"
                    class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex justify-between items-center w-full lg:w-1/2 text-left hover:bg-gray-50 transition">
                    <span class="text-gray-900 text-lg font-medium">
                        {{ __('Tutorial') }}
                    </span>
                    <span class="text-blue-500 hover:text-blue-700 text-xl">
                        →
                    </span>
                </button>
            </div>

        </div>
    </div>
</x-app-layout>
