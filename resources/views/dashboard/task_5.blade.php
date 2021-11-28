<ol class="list-group list-group-numbered overflow-auto">
    @foreach ($data as $roundedTime => $items)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="me-3">{{ \Illuminate\Support\Carbon::createFromFormat(\DateTimeInterface::W3C, $roundedTime)->toRfc2822String() }}</div>
            <span class="badge bg-primary rounded-pill">{{ $items->count() }}</span>
        </li>
    @endforeach
</ol>
