<div>
    <h3>Sets</h3>
    @if ($items->count())
        <table class="table table-striped table-hover table-fixed">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="50%">Reps</th>
                    <th width="50%">kg</th>
                    <th class="text-right" width="100">Aktion</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr class="{{ $item->is_completed ? 'table-success' : '' }}">
                        <td class="align-middle">{{ $item->order }}</td>
                        <td class="align-middle">
                            <input class="form-control" type="number" step="1" min="0" wire:model.defer="form.{{ $item->id }}.reps_count">
                        </td>
                        <td class="align-middle">
                            <input class="form-control" type="text" wire:model.defer="form.{{ $item->id }}.weight_in_g">
                        </td>
                        <td class="align-middle text-right">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-secondary btn-sm" title="Speichern" wire:click="update({{ $item->id }}, {{ $loop->index }})"><i class="fas fa-fw fa-save"></i></button>
                                <button class="btn btn-secondary btn-sm" title="Löschen" wire:click="destroy({{ $item->id }})"><i class="fas fa-fw fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <div>
        <button class="btn btn-secondary btn-sm btn-block" title="Speichern" wire:click="create"><i class="fas fa-fw fa-plus"></i></button>
    </div>
</div>
