<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('event.tambah') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3 justify-content-between align-items-center">
                <div class="col-auto">
                    <label for="perPage">Show</label>
                    <select id="perPage" class="form-control" style="width: auto;" onchange="changePage(this.value)">
                        <option value="10" {{ request('size') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('size') == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ request('size') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </div>
                <div class="col-auto">
                    <form action="{{ route('event.semua') }}" method="GET" class="d-flex">
                            <select id="searchIndex" class="form-control" style="margin-right: 10px;">
                                @foreach ($columns as $items => $values)
                                    @foreach ($values as $item => $value)
                                        <option value="{{ $item }}" data-placeholder="{{ $value['Label'] }}"> {{ $value['Label'] }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <input class="form-control" type="text" id="dynamicInput" name="" placeholder="">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            {{-- <th>Community</th> --}}
                            <th>Name</th>
                            {{-- <th>Image</th> --}}
                            {{-- <th>Description</th> --}}
                            <th>Golf Course</th>
                            <th>Address</th>
                            {{-- <th>City</th> --}}
                            {{-- <th>Location</th> --}}
                            {{-- <th>Type Scoring</th> --}}
                            {{-- <th>Price</th> --}}
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Close Registration</th>
                            {{-- <th>Period</th> --}}
                            <th>Active</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event as $key => $evt)
                            <tr>
                                <th scope="row">{{ $event->firstItem() + $key }}</th>
                                {{-- <td>{{ $evt->eventCommonity->title ?? '-' }}</td> --}}
                                <td>{{ $evt->title ?? '-'}}</td>
                                {{-- <td> <img class="img-thumbnail" src="{{ $evt->image }}" alt=""></td> --}}
                                {{-- <td>{{ $evt->description }}</td> --}}
                                <td>{{ $evt->golfCourseEvent->name ?? '-' }}</td>
                                <td>{{ $evt->golfCourseEvent->address ?? '-' }}</td>
                                {{-- <td>{{ $evt->city->name ?? '-'}}</td> --}}
                                {{-- <td>{{ $evt->location }}</td> --}}
                                {{-- <td>{{ $evt->type_scor }}</td> --}}
                                {{-- <td>{{ $evt->price }}</td> --}}
                                <td>{{ $evt->play_date_start ?? '-' }}</td>
                                <td>{{ $evt->play_date_end ?? '-'}}</td>
                                <td>{{ $evt->close_registration ?? '-'}}</td>
                                {{-- <td>{{ $evt->periode }}</td> --}}
                                <td>{{ ($evt->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <a class="btn btn-info mb-1" href="{{ route('event.ubah', ['id' => $evt->id]) }}">EDIT</a>
                                    {{-- <a class="btn btn-info mb-1" href="{{ route('event.winner-category', ['id' => $evt->id]) }}">Add Category</a> --}}
                                    <form action="{{ route('event.hapus', ['id' => $evt->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mb-1">DELETE</button>
                                    </form>
                                </td>
                                <td>
                                    <a class="btn btn-info mb-1" href="{{ route('event.registrant.semua', ['event_id' => $evt->id]) }}">List Registrant</a>
                                    {{-- <a class="btn btn-info mb-1" href="{{ route('event.winners.semua', ['id' => $evt->id]) }}">Winner's</a> --}}
                                    <a class="btn btn-info mb-1" href="{{ route('event.leaderboard', ['id' => $evt->id]) }}">Leaderboard</a>
                                    <a class="btn btn-info mb-1" href="{{ route('event.viewInputScore', ['id' => $evt->id]) }}">Input Score</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($event->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $event->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($event->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $event->url(1) }}">1</a>
                            </li>
                            @if ($event->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $event->lastPage()) as $i)
                            @if ($i >= $event->currentPage() - 2 && $i <= $event->currentPage() + 2)
                                @if ($i == $event->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $event->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($event->currentPage() < $event->lastPage() - 2)
                            @if ($event->currentPage() < $event->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $event->url($event->lastPage()) }}">{{ $event->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($event->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $event->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-forward"></i></span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>