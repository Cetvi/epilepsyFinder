@include('tutorial.importIntro')


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process results') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col lg:flex-row gap-6">
        <aside
            class="w-full lg:w-1/4 sticky top-24 self-start bg-white border border-gray-200 rounded-xl shadow-sm p-4 h-fit">
            <h3 class="text-md font-semibold text-gray-700 mb-4">Opciones</h3>
            <form id="optionForm" class="space-y-3 text-sm text-gray-600" data-intro="There are three options to explore"
                data-step="2">
                <div data-intro="This two about the prediction"
                data-step="3">
                    <label class="flex items-center space-x-2 mb-3">
                        <input type="radio" name="option" value="no-skull" checked
                            class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span>Don't show skull</span>
                    </label>

                    <label class="flex items-center space-x-2">
                        <input type="radio" name="option" value="skull"
                            class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        <span>Images with skull</span>
                    </label>
                </div>
                <label class="flex items-center space-x-2" data-intro="Also, if you select this option you will be able to see the FastSurfer segmentation in 2D and 3D" data-step="5">
                    <input type="radio" name="option" value="segmentation"
                        class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    <span>FastSurfer segmentation</span>
                </label>
            </form>

        </aside>


        <main class="w-full lg:w-3/4 space-y-8" data-intro="Here you can see the results of the prediction"
            data-step=1>
            <h1 class="text-2xl font-bold text-gray-900 mb-">Project: </h1>
            <div class="no-skull diffOptions">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">FLAIR with mask</h2>
                <img src="{{ asset('images/resultImages/flair_mask_overlay_skull_stripped_42_1.png') }}"
                    alt="FLAIR con máscara" class="rounded-lg shadow-md w-full" data-intro="As you can see, the predicted epileptic lesion is indicated in red" data-step="4">
            </div>

            <div class="no-skull diffOptions">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">T1 with mask</h2>
                <img src="{{ asset('images/resultImages/t1_mask_overlay_skull_stripped_42_1.png') }}"
                    alt="T1 con máscara" class="rounded-lg shadow-md w-full">
            </div>

            <div class="skull diffOptions hidden">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">FLAIR with mask</h2>
                <img src="{{ asset('images/resultImages/flair_mask_overlay_with_skull_42_1.png') }}"
                    alt="FLAIR con máscara" class="rounded-lg shadow-md w-full">
            </div>

            <div class="skull diffOptions hidden">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">T1 with mask</h2>
                <img src="{{ asset('images/resultImages/t1_mask_overlay_with_skull_42_1.png') }}" alt="T1 con máscara"
                    class="rounded-lg shadow-md w-full">
            </div>

            <div class="segmentation diffOptions hidden">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Segmentation</h2>
                <img src="{{ asset('images/resultImages/segmentation_overlay_42_1.png') }}" alt="Segmentation"
                    class="rounded-lg shadow-md w-full">
            </div>

            <div id="vtk-container" class="w-full h-[600px] bg-gray-900 rounded-lg shadow-lg hidden"></div>

        </main>
    </div>
    <a href="{{ route('moreInfo-tutorial') }}"
        class="fixed bottom-6 more-info right-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-full shadow-lg transition duration-200"
        data-intro="Finally, if you press this button you will see more specific information about the prediction" data-step="6">
        More info
    </a>
</x-app-layout>
