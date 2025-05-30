<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="px-6 py-8 ml-12">
        @if (count($projects) > 0)
            <h3 class="text-lg font-semibold mb-4">Your Projects</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($projects as $project)
                    <div class="border border-gray-300 rounded-md p-4 shadow-sm bg-white flex flex-col justify-between">
                        <div class="mb-2">
                            <h4 class="text-md font-medium">{{ $project->name }}</h4>
                            <h4 class="text-sm text-gray-500">
                                {{ !empty($project->create_date) ? 'Created on ' . \Carbon\Carbon::parse($project->create_date)->format('d/m/Y') : '' }}
                            </h4>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('show-inference.get', parameters: ['project_id' => $project->id, 'user_id' => $project->user_id]) }}" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="deleteProject({{ $project->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-16">
                <h3 class="text-lg font-semibold mb-4">No Projects Found</h3>
                <p class="text-gray-600">You have not created any projects yet.</p>
                <a href="{{ route('projects.create') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500">
                    Create New Project
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
