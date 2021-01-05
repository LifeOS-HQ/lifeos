<div wire:init="loadItems" class="card mt-3">
    <div class="card-header">Gewicht</div>
    <div class="card-body">
        @isset($items)
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        @foreach($items->first()->values as $value)
                            <th class="text-right">
                                {{ $value->at->format('d.m.Y') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            @foreach($item->values as $value)
                                <td class="text-right">{{ $value->raw }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endisset
    </div>
</div>
