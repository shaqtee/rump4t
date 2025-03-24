<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start">
                <div class="col-auto">
                    <a href="{{ route('community.manage.album.photo.viewtambah', ['album_id' => $albums->id]) }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Album</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($photos as $key => $p)
                            <tr>
                                <th scope="row">{{ $photos->firstItem() + $key }}</th>
                                <td>{{ $p->photoCommonity->name }}</td>
                                <td><img class="img-thumbnail" style="width: 100px; height: 100px; object-fit: fill;" src="{{ $p->image }}" alt=""></td>
                                <td>{{ $p->name }}</td>
                                <td>{{ ($p->active == '1') ? 'Active' : 'Deactivate' }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a class="btn btn-info " href="{{ route('community.manage.album.photo.ubah', ['id' => $p->id]) }}">EDIT</a>
                                        <form action="{{ route('community.manage.album.photo.hapus', ['id' => $p->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">DELETE</button>
                                        </form>
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
                        @if ($photos->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $photos->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $photos->lastPage(); $i++)
                            <li class="page-item {{ ($photos->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $photos->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($photos->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $photos->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>