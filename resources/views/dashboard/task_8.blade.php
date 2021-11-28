<ol class="list-group list-group-numbered overflow-auto">
    @foreach ($data as $tag)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">{{ $tag->localization_names->{'en-us'} }}</div>
                {{ $tag->localization_descriptions->{'en-us'} }}
            </div>
        </li>
    @endforeach
</ol>
