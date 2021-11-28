<div class="p-3">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Viewers needed for the followed stream with the lowest viewer count to get to the top 1000 streams:</h5>
            <p class="card-text">{{ $data }}</p>
        </div>
    </div>

    <div class="alert alert-warning mt-3" role="alert">
        <strong>Disclaimer:</strong> The number can be negative if the stream with the lowest viewer count you follow is already in top, but its viewer count within the top streams is not the lowest. The issue might also apear because of discrapancy in top streams and following list data, because top streams data is getting out of date during 15 min, while followed streams data is always fresh.
    </div>
</div>
