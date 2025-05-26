<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            {{-- <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a href="{{ route('event.winners.tambah') }}" class="btn btn-success "><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            {{-- <th>Events</th> --}}
                            <th>Winner Categories</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($eventWinner as $evWin) --}}
                            <tr>
                                {{-- <td>{{ $eventWinner->title }}</td> --}}
                                <td>
                                    <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Winner Category</th>
                                                    <th>User Winner</th>
                                                </tr>
                                            </thead>
                                        @foreach ($eventWinner->winnerCategory as $winnerCategory)
                                            <tbody>
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $winnerCategory->masterWinnerCategory->name ?? '???'}}</td>
                                                    <td>{{ !empty($winnerCategory->usersWinner->name) ? $winnerCategory->usersWinner->name ?? '???' : $winnerCategory->name ?? '???' }}</td>
                                                </tr>
                                            </tbody>
                                        @endforeach
                                    </table>
                                </td>
                                <td>
                                    {{-- <div class="d-flex"> --}}
                                        <a class="btn btn-info " href="{{ route('event.winners.ubah', ['id' => $eventWinner->id]) }}">EDIT</a>
                                        {{-- <form action="{{ route('event.winners.hapus', ['id' => $eventWinner->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">DELETE</button>
                                        </form> --}}
                                    {{-- </div> --}}
                                </td>
                            </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
                
            </div>
            {{-- <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($eventWinner->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $eventWinner->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $eventWinner->lastPage(); $i++)
                            <li class="page-item {{ ($eventWinner->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $eventWinner->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($eventWinner->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $eventWinner->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>