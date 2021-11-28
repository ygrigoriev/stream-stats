<ol class="list-group list-group-numbered overflow-auto">
    @foreach ($data as $datum)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">{{ $datum->game_name }}</div>
                {{ $datum->title }}
            </div>
        </li>
    @endforeach
</ol>
