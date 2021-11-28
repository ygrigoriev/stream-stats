<ol class="list-group list-group-numbered overflow-auto">
    @foreach ($data as $datum)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <span class="ms-3 me-3" style="width: 100%">{{ $datum->game_name }}</span>
            <span class="badge bg-primary rounded-pill">{{ $datum->streams_count }}</span>
        </li>
    @endforeach
</ol>
