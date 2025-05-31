@if (!empty($project))
    @php
        $extraData = '_' . $project->id . '_' . $project->user_id;
    @endphp

    <span class="text-gray-900 text-lg font-medium">
        {{ __('Last project created: ') }} <strong>{{ $project->name }}</strong>
    </span>

    <div class="mt-4 space-y-4">
        <div class="flex justify-between items-center gap-4">
            <div class="no-skull diffOptions">
                <img src="{{ asset('images/resultImages/flair_mask_overlay_skull_stripped' . $extraData . '.png') }}"
                    alt="FLAIR con máscara" class="rounded-lg shadow-md w-40 h-auto object-contain">
            </div>
            <div class="skull diffOptions">
                <img src="{{ asset('images/resultImages/flair_mask_overlay_with_skull' . $extraData . '.png') }}"
                    alt="FLAIR con máscara con cráneo" class="rounded-lg shadow-md w-40 h-auto object-contain">
            </div>
            <div class="segmentation diffOptions">
                <img src="{{ asset('images/resultImages/segmentation_overlay' . $extraData . '.png') }}"
                    alt="Segmentación" class="rounded-lg shadow-md w-40 h-auto object-contain">
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('show-inference.get', parameters: ['project_id' => $project->id, 'user_id' => $project->user_id]) }}" class="text-blue-500 hover:text-blue-700 text-l">
                Go to project →
            </a>
        </div>
    </div>
@else
    <span class="text-gray-900 text-lg font-medium">
        {{ __("You haven't created any project yet") }}
    </span>
@endif
