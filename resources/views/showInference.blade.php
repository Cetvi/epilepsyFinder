@extends('layouts.app')

@section('title', 'Bienvenido a Epilepsy Finder')

@section('content')
<div class="relative isolate px-6 pt-14 lg:px-8">

    <!-- Fondo decorativo -->
    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-1155/678 w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
            style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
        </div>
    </div>

    <!-- Panel lateral fijo -->
    <aside class="fixed top-32 left-30 w-60 bg-white shadow-md rounded-lg p-4 border z-50 h-fit">
        <h3 class="text-md font-semibold mb-3">Opciones</h3>
        <form id="optionForm" class="space-y-2 text-sm">
            @foreach ([
                'no-skull' => "Don't show skull",
                'skull' => 'Images with skull',
                'no-lesion' => "Don't show lesion",
                'segmentation' => 'FastSurfer segmentation',
                'fs-lesion' => 'FastSurfer segmentation with lesion',
                'hide-extra' => 'Hide extra info'
            ] as $value => $label)
                <label class="flex items-center space-x-2">
                    <input type="radio" name="option" <?= $value == 'no-skull' ? 'checked' : ''?> value="{{ $value }}" class="option-checkbox">
                    <span>{{ $label }}</span>
                </label>
            @endforeach
        </form>
    </aside>

    <!-- Contenido principal centrado -->
    <div class="relative mx-auto max-w-5xl mt-16 px-4">
        <main class="mx-auto text-center max-w-2xl w-full">
            <div class="mb-6 no-skull diffOptions">
                <h2 class="text-xl font-semibold mb-2">FLAIR with mask</h2>
                <img src="{{ asset('images/resultImages/flair_mask_overlay_skull_stripped_' . Auth::id() . '.png') }}" alt="FLAIR con máscara" class="rounded-lg shadow-lg w-full">
            </div>

            <div class="no-skull mb-6 diffOptions">
                <h2 class="text-xl font-semibold mb-2">T1 with mask</h2>
                <img src="{{ asset('images/resultImages/t1_mask_overlay_skull_stripped_' . Auth::id() . '.png') }}" alt="T1 con máscara" class="rounded-lg shadow-lg w-full">
            </div>

            <div class="mb-6 skull diffOptions" hidden>
                <h2 class="text-xl font-semibold mb-2">FLAIR with mask</h2>
                <img src="{{ asset('images/resultImages/flair_mask_overlay_with_skull_' . Auth::id() . '.png') }}" alt="FLAIR con máscara" class="rounded-lg shadow-lg w-full">
            </div>

            <div class="mb-6 skull diffOptions" hidden>
                <h2 class="text-xl font-semibold mb-2">T1 with mask</h2>
                <img src="{{ asset('images/resultImages/t1_mask_overlay_with_skull_' . Auth::id() . '.png') }}" alt="T1 con máscara" class="rounded-lg shadow-lg w-full">
            </div>

            <div class="mb-6 segmentation diffOptions" hidden>
                <h2 class="text-xl font-semibold mb-2">Segmentation</h2>
                <img src="{{ asset('images/resultImages/segmentation_overlay_' . Auth::id() . '.png') }}" alt="" class="rounded-lg shadow-lg w-full">
            </div>
        </main>
    </div>
</div>

@endsection
