<div>
    <div>
        <div class="form-inline mb-3 align-items-start">
            <div class="form-group">
                <label class="sr-only" for="name">Übungen</label>
                <select wire:model.defer="form.exercise_id" class="form-control @error('form.name') is-invalid @enderror" id="name" placeholder="Übung">
                    <option value="0">Übung wählen</option>
                    @foreach ($exercises as $exercise)
                        <option value="{{ $exercise->id }}">{{ $exercise->name }}</option>
                    @endforeach
                </select>
                @error('form.name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div>
                <button wire:click="create" type="button" class="btn btn-primary ml-1">Hinzufügen</button>
            </div>
        </div>
    </div>
        @foreach($items as $item)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h1 class="flex-grow-1">{{ $item->exercise->name }}</h1>
                        <div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-secondary btn-sm" title="Löschen" wire:click="destroy({{ $item->id }})"><i class="fas fa-fw fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <h2>{{ $item->goal_type }}: {{ $item->goal_target }}</h2>
                    @livewire('workouts.sets.index', ['exercise' => $item, 'workout' => $model])
                </div>
            </div>
        @endforeach
    </ul>
</div>