<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start">
                <div class="col-auto">
                    <a href="{{ route('event.manage-event.album.viewtambah', ['event_id' => $event->id]) }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Events</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($albums as $key => $albm)
                            <tr>
                                <th scope="row">{{ $albums->firstItem() + $key }}</th>
                                <td>{{ $albm->albumEvent->title ?? '-' }}</td>
                                <td><img class="img-thumbnail" style="width: 100px; height: 100px; object-fit: fill;" src="{{ $albm->cover }}" alt=""></td>
                                <td>{{ $albm->name }}</td>
                                <td>{{ $albm->description }}</td>
                                <td>{{ ($albm->active == '1') ? 'Active' : 'Deactivate' }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-info " href="{{ route('event.manage-event.album.ubah', ['id' => $albm->id]) }}">EDIT</a>
                                        {{-- <form action="{{ route('event.manage-event.album.hapus', ['id' => $albm->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">DELETE</button>
                                        </form> --}}
                                        <a class="btn btn-info " href="{{ route('event.manage-event.album.photo.semua', ['album_id' => $albm->id]) }}">PHOTO</a>
                                    </td>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($albums->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $albums->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $albums->lastPage(); $i++)
                            <li class="page-item {{ ($albums->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $albums->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($albums->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $albums->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>