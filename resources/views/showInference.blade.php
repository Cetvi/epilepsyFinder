
<?php
    $extraData = '';
    if (!empty($data)){
        $extraData = '_'.$data['project_id'].'_'.$data['user_id'];
    }
    $flagUrl = true;
    $path = public_path('images/resultImages/t1_mask_overlay_skull_stripped' . $extraData . '.png');
    if (!file_exists($path)) {
        $flagUrl = false;
    }
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Process results') }}
        </h2>
    </x-slot>
    @if($flagUrl)
        <input type="text" id="extraData" value="{{ $extraData }}" class="hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col lg:flex-row gap-6">   
            <aside
                class="w-full lg:w-1/4 sticky top-24 self-start bg-white border border-gray-200 rounded-xl shadow-sm p-4 h-fit">
                <h3 class="text-md font-semibold text-gray-700 mb-4">Opciones</h3>
                <form id="optionForm" class="space-y-3 text-sm text-gray-600">
                    @foreach ([
    'no-skull' => "Don't show skull",
    'skull' => 'Images with skull',
    'segmentation' => 'FastSurfer segmentation',
    ] as $value => $label)
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="option" {{ $value == 'no-skull' ? 'checked' : '' }}
                                value="{{ $value }}"
                                class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </form>
            </aside>

            <!-- Contenido desplazable -->
            <main class="w-full lg:w-3/4 space-y-8">
                <div class="no-skull diffOptions">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">FLAIR with mask</h2>
                    <img src="{{ asset('images/resultImages/flair_mask_overlay_skull_stripped'.$extraData.'.png') }}"
                        alt="FLAIR con máscara" class="rounded-lg shadow-md w-full">
                </div>

                <div class="no-skull diffOptions">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">T1 with mask</h2>
                    <img src="{{ asset('images/resultImages/t1_mask_overlay_skull_stripped'.$extraData.'.png') }}"
                        alt="T1 con máscara" class="rounded-lg shadow-md w-full">
                </div>

                <div class="skull diffOptions hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">FLAIR with mask</h2>
                    <img src="{{ asset('images/resultImages/flair_mask_overlay_with_skull'.$extraData.'.png') }}"
                        alt="FLAIR con máscara" class="rounded-lg shadow-md w-full">
                </div>

                <div class="skull diffOptions hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">T1 with mask</h2>
                    <img src="{{ asset('images/resultImages/t1_mask_overlay_with_skull'.$extraData.'.png') }}"
                        alt="T1 con máscara" class="rounded-lg shadow-md w-full">
                </div>

                <div class="segmentation diffOptions hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Segmentation</h2>
                    <img src="{{ asset('images/resultImages/segmentation_overlay'.$extraData.'.png') }}" alt="Segmentation"
                        class="rounded-lg shadow-md w-full">
                </div>

                <div id="vtk-container" class="w-full h-[600px] bg-gray-900 rounded-lg shadow-lg hidden"></div>

            </main>
        </div>
        <a href="{{ route('show-more-info', ['project_id' => $data['project_id'], 'user_id' => $data['user_id']]) }}"
            class="fixed bottom-6 more-info right-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-full shadow-lg transition duration-200">
            More info
        </a>
    @else
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Results not available</h2>
                <p class="text-gray-600">The results for this project are still in progress</p>
            </div>
        </div>
    @endif
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/vtk.js/dist/vtk.js"></script>

<script>
    const colorLut = {!! $colorLut !!};
</script>