<div wire:init="setExerciseHistories">
    @isset($next_set)
        {{ $next_set->exercise->name }}
        {{ $next_set->exercise->reps_count }}
        <button wire:click="nextSet">Weiter</button>
    @endisset
    @foreach ($exercise_histories as $exercise_history)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h1 class="flex-grow-1">{{ $exercise_history->exercise->name }}</h1>
                    <div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-secondary btn-sm" title="Löschen" wire:click="destroy({{ $exercise_history->exercise->id }})"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <h2>{{ $exercise_history->goal_type }}: {{ $exercise_history->goal_target }}</h2>
                @livewire('workouts.sets.histories.index', ['exercise_history' => $exercise_history, 'workout_history' => $workout_history])
            </div>
        </div>
    @endforeach

</div>
