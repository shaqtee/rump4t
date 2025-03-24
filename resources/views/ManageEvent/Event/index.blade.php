<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start">
                <div class="col-auto">
                    <a href="{{ route('event.manage-event.tambah') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Community</th>
                            <th>Name</th>
                            <th>Golf Course</th>
                            <th>Address</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Close Registration</th>
                            <th>Active</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event as $key => $evt)
                            <tr>
                                <th scope="row">{{ $event->firstItem() + $key }}</th>
                                <td>{{ $evt->eventCommonity->title }}</td>
                                <td>{{ $evt->title }}</td>
                                <td>{{ $evt->golfCourseEvent->name }}</td>
                                <td>{{ $evt->golfCourseEvent->address }}</td>
                                <td>{{ $evt->play_date_start }}</td>
                                <td>{{ $evt->play_date_end }}</td>
                                <td>{{ $evt->close_registration }}</td>
                                <td>{{ ($evt->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <a class="btn btn-info " href="{{ route('event.manage-event.ubah', ['id' => $evt->id]) }}">EDIT</a>
                                            <a class="btn btn-info " href="{{ route('event.manage-event.registrant.semua', ['event_id' => $evt->id]) }}">List Registrant</a>
                                            <a class="btn btn-info " href="{{ route('event.manage-event.winners.semua', ['id' => $evt->id]) }}">Winner Category</a>
                                            <a class="btn btn-info " href="{{ route('event.manage-event.leaderboard', ['id' => $evt->id]) }}">Leaderboard</a>
                                            <br>
                                            <form action="{{ route('event.manage-event.hapus', ['id' => $evt->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger ">DELETE</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-info " href="{{ route('event.manage-event.sponsor.semua', ['event_id' => $evt->id]) }}">Sponsor</a>
                                    <a class="btn btn-info " href="{{ route('event.manage-event.album.semua', ['event_id' => $evt->id]) }}">Gallery</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($event->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $event->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $event->lastPage(); $i++)
                            <li class="page-item {{ ($event->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $event->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($event->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $event->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>