<tr>
    <td class="align-middle">
        <div class="form-group mb-0">
            {{ $form['data_attribute_id'] }}
            <select wire:model.defer="form.data_attribute_id" class="form-control">
                @foreach ($attribute_groups as $attribute_group)
                    <optgroup label="{{ $attribute_group->name }}">
                        @foreach ($attribute_group->attributes as $attribute)
                            <option value="{{ $attribute->id }}">{{ $attribute->id }} {{ $attribute->name }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </td>
    <td class="align-middle">
        <div class="form-group mb-0">
            <input wire:model.defer="form.start_formatted" class="form-control" type="text">
        </div>
    </td>
    <td class="align-middle">
        <div class="form-group mb-0">
            <input wire:model.defer="form.end_formatted" class="form-control" type="text">
        </div>
    </td>
    <td class="align-middle text-right">
        {{ $model->id }}
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-secondary" title="Speichern" wire:click="update"><i class="fas fa-fw fa-save"></i></button>
            <button type="button" class="btn btn-secondary" title="Löschen" wire:click="delete"><i class="fas fa-fw fa-trash"></i></button>
        </div>
    </td>
</tr>
