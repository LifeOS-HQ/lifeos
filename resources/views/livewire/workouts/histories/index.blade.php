<div>
    <table>
        <thead>
            <tr>
                <th>Datum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        <a href="{{ $item->path }}">{{ $item->start_at->format('d.m.Y') }}</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $items->links() }}
</div>
