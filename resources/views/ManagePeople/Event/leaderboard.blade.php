<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Golfer</th>
                            <th>Gross</th>
                            <th>To Par</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaderboard as $key => $leader)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @foreach ($leader as $l)
                                <td>{{ $l }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>