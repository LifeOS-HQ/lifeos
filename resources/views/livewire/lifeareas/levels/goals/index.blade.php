<div class="card mt-3">
    <div class="card-header">Ziele</div>
    <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Attribute</th>
                        <th>Start</th>
                        <th>Ende</th>
                        <th class="text-right">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        @livewire('lifeareas.levels.goals.tr', ['model' => $item, 'attribute_groups' => $attribute_groups], key($loop->index))
                    @empty
                        <tr><td>Nichts vorhanden</td></tr>
                    @endforelse
                </tbody>
            </table>
    </div>
    <div class="card-footer">
        <div class="form-row">
            <div class="form-group mb-0">
                <select wire:model.defer="form.attribute_id" class="form-control">
                    <option value="0">Attribut wählen</option>
                    @foreach ($attribute_groups as $attribute_group)
                        <optgroup label="{{ $attribute_group->name }}">
                            @foreach ($attribute_group->attributes as $attribute)
                                <option value="{{ $attribute->id }}">{{ $attribute->id }} {{ $attribute->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary ml-1" wire:click="create">Anlegen</button>
        </div>
    </div>
</div>
