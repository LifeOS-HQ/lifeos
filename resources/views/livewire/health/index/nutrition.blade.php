<div wire:init="loadItems" class="card mb-3">
    <div class="card-header">Kalorien</div>
    <div class="card-body">
        <div class="row">
            <div class="col-label"><b>Name</b></div>
            <div class="col-value">Ø letzte 7 Tage</div>
            <div class="col-info">Ziel?</div>
        </div>
        @forelse ($items as $item)
            <div class="row">
                <div class="col-label"><b>{{ $item->name }}</b></div>
                <div class="col-value">Ø {{ number_format($item->value($item->values_avg_raw), 2, ',', '.') }}</div>
                <div class="col-info">Ziel?</div>
            </div>
        @empty

        @endforelse
    </div>
</div>
