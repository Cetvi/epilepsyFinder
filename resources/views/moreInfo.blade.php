<?php
$extraData = '';
if (!empty($projectId) && !empty($userId)) {
    $extraData = '_' . $projectId . '_' . $userId;
}
$jsonPath = public_path('json/extraInfo' . $extraData . '.json');
$jsonInfo = json_decode(file_get_contents($jsonPath), true);
$firstLabel = array_key_first($jsonInfo);
?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('More information') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col items-center gap-6">
        <main class="w-full lg:w-3/4 space-y-8">
            <section>
                <div class="panel bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Lesion Labels and Percentages</h2>
                    <table class="min-w-full table-auto border-collapse border border-gray-300 mb-10">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Number</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Zone</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Tissue</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Side</th>
                                <th class="border border-gray-300 px-4 py-2 text-right">Percentage (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jsonInfo as $key => $label)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $key }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $label['name'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $label['zone'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $label['tissue'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $label['side'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-right">
                                        {{ number_format($label['percentage'] * 100, 2) }} %</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-sm/20 mb-5">
                        Notice that from all the labels, the one which has the highest percentage is the label
                        <strong>{{ $firstLabel }}</strong>.
                    </p>
                    <p class="text-sm/20 mb-5">
                        This structure corresponds to the <strong>{{$jsonInfo[$firstLabel]['side']}}</strong> side of the brain and is located in the
                        <strong>{{$jsonInfo[$firstLabel]['tissue']}} {{$jsonInfo[$firstLabel]['zone']}}</strong>.
                        A percentage of <strong>{{ number_format($jsonInfo[$firstLabel]['percentage'] * 100, 2)}}%</strong> indicates that this region is entirely affected within the
                        analyzed lesion area, making it the most significant in the current context.
                    </p>
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Label {{$firstLabel}} in the brain:</h2>
                    <div class="relative mb-8 max-w-xl mx-auto"> 
                        <button
                            class="toggle-btn absolute right-2 top-2 bg-gray-300 hover:bg-gray-400 rounded-full w-6 h-6 flex items-center justify-center font-bold text-lg select-none cursor-pointer"
                            data-target="main-label-img">-</button>
                        <img id="main-label-img"
                            src="{{ asset('images/resultImages/main_label' . $extraData . '.png') }}"
                            class="rounded-lg shadow-md w-full">
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Visualization of each classification</h2>
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Zone: {{ucfirst($jsonInfo[$firstLabel]['zone'])}}</h2>
                    <div class="relative mb-8 max-w-xl mx-auto"> 
                        <button
                            class="toggle-btn absolute right-2 top-2 bg-gray-300 hover:bg-gray-400 rounded-full w-6 h-6 flex items-center justify-center font-bold text-lg select-none cursor-pointer"
                            data-target="same-zone-img">-</button>
                        <img id="same-zone-img"
                            src="{{ asset('images/resultImages/same_zone' . $extraData . '.png') }}"
                            class="rounded-lg shadow-md w-full">
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Side of the brain: {{ucfirst($jsonInfo[$firstLabel]['side'])}}</h2>
                    <div class="relative mb-8 max-w-xl mx-auto"> 
                        <button
                            class="toggle-btn absolute right-2 top-2 bg-gray-300 hover:bg-gray-400 rounded-full w-6 h-6 flex items-center justify-center font-bold text-lg select-none cursor-pointer"
                            data-target="same-side-img">-</button>
                        <img id="same-side-img"
                            src="{{ asset('images/resultImages/same_side' . $extraData . '.png') }}"
                            class="rounded-lg shadow-md w-full">
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Tissue: {{ucfirst($jsonInfo[$firstLabel]['tissue'])}}</h2>
                    <div class="relative mb-8 max-w-xl mx-auto"> 
                        <button
                            class="toggle-btn absolute right-2 top-2 bg-gray-300 hover:bg-gray-400 rounded-full w-6 h-6 flex items-center justify-center font-bold text-lg select-none cursor-pointer"
                            data-target="same-tissue-img">-</button>
                        <img id="same-tissue-img"
                            src="{{ asset('images/resultImages/same_tissue' . $extraData . '.png') }}"
                            class="rounded-lg shadow-md w-full">
                    </div>
                    <div class="mt-10">
                        <h3 class="text-md font-semibold text-gray-700 mb-2">Additional Information</h3>
                        <p class="text-sm text-gray-600">This section provides more details about the lesion labels and
                            their significance in the context of epilepsy diagnosis and treatment.</p>
                        <ul class="list-disc pl-5 mt-2 text-sm text-gray-600">
                            <li>Lesion labels are used to identify specific areas of interest in the brain.</li>
                            <li>Each label corresponds to a different anatomical part of the brain, which can vary in
                                size and location.</li>
                            <li>The percentage indicates the proportion of the lesion relative to the total brain
                                volume.</li>
                            <li>Understanding these labels helps in planning treatment strategies for epilepsy patients.
                            </li>
                        </ul>
                    </div>

                </div>
            </section>
        </main>
    </div>
</x-app-layout>
