<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('users.tambah') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
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
                    <form action="{{ route('users.semua') }}" method="GET" class="d-flex">
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
                            <th>Name</th>
                            <th>Image</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Community</th>
                            <th>Active</th>
                            <th>Meminta mengganti password?</th>
                            <th colspan="4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $usr)
                            <tr>
                                <td>{{ $users->firstItem() + $key }}</td>
                                <td>{{ $usr->name }}</td>
                                <td><img class="img-thumbnail" src="{{ $usr->image }}" style="width: 100px; height: 100px; object-fit: cover;" alt="Profile"></td>
                                <td>{{ $usr->email }}</td>
                                <td>{{ $usr->phone }}</td>
                                <td>{{ $usr->community->title ?? '-' }}</td>
                                <td>{{ $usr->active == '1' ? 'Active' : 'Deactivate' }}</td>
                                <td>{{ $usr->reset_request == '1' ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('users.ubah', ['id' => $usr->id]) }}">EDIT</a>
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('users.lihat', ['id' => $usr->id]) }}">SHOW</a>
                                </td>
                                {{-- <td>
                                    <a class="btn btn-info" href="{{ route('users.gamescore', ['id' => $usr->id]) }}">Game Score</a>
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('users.hcpindex', ['id' => $usr->id]) }}">Handicap</a>
                                </td> --}}                                
                                <td>
                                    <a class="btn btn-danger" href="{{ route('users.resetpass', ['id' => $usr->id]) }}">Atur Ulang Password Akun</a>
                                </td> 
                                {{-- <td>
                                    <form action="{{ route('users.hapus', ['id' => $usr->id]) }}" method="POST">
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
                        @if ($users->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($users->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url(1) }}">1</a>
                            </li>
                            @if ($users->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $users->lastPage()) as $i)
                            @if ($i >= $users->currentPage() - 2 && $i <= $users->currentPage() + 2)
                                @if ($i == $users->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($users->currentPage() < $users->lastPage() - 2)
                            @if ($users->currentPage() < $users->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($users->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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