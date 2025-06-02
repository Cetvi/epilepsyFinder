<link rel="stylesheet" href="https://unpkg.com/intro.js/minified/introjs.min.css" />
<script src="https://unpkg.com/intro.js/minified/intro.min.js"></script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" data-intro="Here you can create a new project"
            data-step="1">
            {{ __('New project') }}
        </h2>
    </x-slot>
    <div class="relative isolate px-6 lg:px-8">
        <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
            <div class="text-center">
                <form method="POST" enctype="multipart/form-data"
                    class="flex flex-col items-center justify-center gap-y-4">
                    @csrf

                    <!-- Input de texto para Name -->
                    <label for="name" class="block w-full max-w-md mx-auto text-left">
                        <input type="text" id="project-name" name="name" placeholder="Enter project name" required
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring-1"
                            data-intro="You can enter the name of your new project!" data-step="2" />
                    </label>


                    <label class="block w-full max-w-md mx-auto">
                        <span class="sr-only" id="chooseFiles">Choose files</span>
                        <input id="dataNifty" type="file" multiple name="files[]" accept=".gz"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                   file:rounded-md file:border-0
                                   file:bg-indigo-50 file:text-indigo-700
                                   hover:file:bg-indigo-100"
                            data-intro="And then upload one FLAIR file ended as _0000.nii.gz and one T1 file ended as _0001.nii.gz"
                            data-step="3" />
                    </label>

                    <div class="inline-flex items-center justify-center w-full max-w-md gap-x-2 mx-auto">
                        <button id="uploadFiles" type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                            data-intro="Finally, click here to upload the files and start the process!!" data-step="4">
                            Upload only two .nii.gz files
                        </button>
                        <div class="relative group inline-block cursor-pointer">
                            <span class="bg-blue-500 text-white rounded-full px-2 py-1 text-sm font-bold">i</span>
                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2
                                       hidden group-hover:block w-48 bg-gray-800 text-white text-sm rounded px-3 py-2 z-10">
                                You must upload two .nii.gz files. One Flair ended by '_0000' and one T1 '_0001'. The
                                order of the files does not matter.
                            </div>
                        </div>
                    </div>
                </form>

                <div id="loadingSpinner" style="display:none; text-align:center; margin-top: 10px;">
                    <svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <p>Uploading, please wait...</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
