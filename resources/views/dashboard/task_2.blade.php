<ol class="list-group list-group-numbered overflow-auto">
    @foreach ($data as $datum)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">{{ $datum->game_name }}</div>
            <span class="badge bg-primary rounded-pill">{{ $datum->viewer_count }}</span>
        </li>
    @endforeach
</ol>
