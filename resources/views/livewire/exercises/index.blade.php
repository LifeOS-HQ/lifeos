<div>
    <div>
        <div class="form-inline mb-3 align-items-start">
            <div class="form-group">
                <label class="sr-only" for="name">Name</label>
                <input wire:model.defer="form.name" type="text" class="form-control @error('form.name') is-invalid @enderror" id="name" placeholder="Name">
                @error('form.name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div>
                <button wire:click="create" type="button" class="btn btn-primary ml-1">Anlegen</button>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
