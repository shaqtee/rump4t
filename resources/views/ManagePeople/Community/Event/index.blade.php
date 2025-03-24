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
                            <th>No</th>
                            <th>Community</th>
                            <th>Name</th>
                            <th>Golf Course</th>
                            <th>Address</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Close Registration</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $key => $evt)
                            <tr>
                                <th scope="row">{{ $events->firstItem() + $key }}</th>
                                <td>{{ $evt->eventCommonity->title}}</td>
                                <td>{{ $evt->title }}</td>
                                <td>{{ $evt->golfCourseEvent->name }}</td>
                                <td>{{ $evt->golfCourseEvent->address }}</td>
                                <td>{{ $evt->play_date_start }}</td>
                                <td>{{ $evt->play_date_end }}</td>
                                <td>{{ $evt->close_registration }}</td>
                                <td>{{ ($evt->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <a class="btn btn-info " href="{{ route('community.manage.event.lihat', ['event_id' => $evt->id]) }}">SHOW</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($events->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $events->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $events->lastPage(); $i++)
                            <li class="page-item {{ ($events->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $events->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($events->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $events->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>