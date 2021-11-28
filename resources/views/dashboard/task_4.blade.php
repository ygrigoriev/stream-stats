<?php
$routeName = request()->route()->getName();
$pathChunks = explode('/', request()->path());
$lastChunk = array_pop($pathChunks);
?>
@section('styles')
    <style>
        ol {
            height: calc(100vh - 72px);
            overflow-y: auto;
        }

        ol.reversed {
            counter-reset: section 101;
        }

        ol.reversed li::before {
            counter-increment: section -1;
        }
    </style>
@endsection

<div>
    <div class="card">
        <div class="card-body">Order by viewer count:
            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off"<?= $lastChunk == 'asc' ? ' checked' : ''?>>
                <label class="btn btn-outline-primary" for="btnradio1" onclick="(function(){location.href='{{ route($routeName, 'asc') }}'})()">ASC</label>
                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"<?= $lastChunk == 'desc' ? ' checked' : ''?>>
                <label class="btn btn-outline-primary" for="btnradio2" onclick="(function(){location.href='{{ route($routeName, 'desc') }}'})()">DESC</label>
            </div>
        </div>
    </div>
    <ol class="list-group list-group-numbered {{ $lastChunk === 'asc' ? 'reversed' : '' }}">
        @foreach ($data as $datum)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">{{ $datum->game_name }}</div>
                    {{ $datum->title }}
                </div>
                <span class="badge bg-primary rounded-pill">{{ $datum->viewer_count }}</span>
            </li>
        @endforeach
    </ol>
</div>
