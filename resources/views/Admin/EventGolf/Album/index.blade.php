<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('event.album.tambah') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
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
                    <form action="{{ route('event.album.semua') }}" method="GET" class="d-flex">
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
                            <th>Events</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($albums as $key => $albm)
                            <tr>
                                <th scope="row">{{ $albums->firstItem() + $key }}</th>
                                <td>{{ $albm->albumEvent->title }}</td>
                                <td><img class="img-thumbnail" style="width: 100px; height: 100px; object-fit: fill;" src="{{ $albm->cover }}" alt=""></td>
                                <td>{{ $albm->name }}</td>
                                <td>{{ $albm->description }}</td>
                                <td>{{ $albm->active == '1' ? 'Active' : 'Deactivate' }}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('event.album.ubah', ['id' => $albm->id]) }}">EDIT</a>
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('event.album.photo.semua', ['album_id' => $albm->id]) }}">PHOTO</a>
                                </td>
                                {{-- <td>
                                    <form action="{{ route('event.album.hapus', ['id' => $albm->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">DELETE</button>
                                    </form>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($albums->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $albums->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($albums->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $albums->url(1) }}">1</a>
                            </li>
                            @if ($albums->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $albums->lastPage()) as $i)
                            @if ($i >= $albums->currentPage() - 2 && $i <= $albums->currentPage() + 2)
                                @if ($i == $albums->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $albums->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($albums->currentPage() < $albums->lastPage() - 2)
                            @if ($albums->currentPage() < $albums->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $albums->url($albums->lastPage()) }}">{{ $albums->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($albums->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $albums->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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