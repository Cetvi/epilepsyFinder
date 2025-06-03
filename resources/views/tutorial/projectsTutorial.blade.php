@include('tutorial.importIntro')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" data-intro="Here you can see your projects"
            data-step="1">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="px-6 py-8 ml-12">
        <h3 class="text-lg font-semibold mb-4">Your Projects</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-300 rounded-md p-4 shadow-sm bg-white flex flex-col justify-between">
                <div class="mb-2">
                    <h4 class="text-md font-medium" data-intro="This is the name of one of your projects" data-step="2">Tutorial project</h4>
                    <h4 class="text-sm text-gray-500">
                        Created on XX/XX/XXXX
                    </h4>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="" class="text-blue-600 hover:text-blue-800" data-intro="Here you can see the prediction and the FastSurfer segmentation of your files"  data-step="3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>

                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="" data-intro="And you can click here to delete the project" data-step="4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z" />
                        </svg>
                    </button>
                </div>
            </div>
            @php
                $projects = [
                    (object) ['name' => 'Project 1'],
                    (object) ['name' => 'Project 2'],
                    (object) ['name' => 'Project 3'],
                    (object) ['name' => 'Project 4'],
                ];
            @endphp
            @foreach ($projects as $project)
                <div class="border border-gray-300 rounded-md p-4 shadow-sm bg-white flex flex-col justify-between">
                    <div class="mb-2">
                        <h4 class="text-md font-medium">{{ $project->name }}</h4>
                        <h4 class="text-sm text-gray-500">
                            Created on XX/XX/XXXX
                        </h4>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="" class="text-blue-600 hover:text-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>

                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
