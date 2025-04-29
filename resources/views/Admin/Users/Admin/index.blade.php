<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-3">
                    <a class="btn btn-success " data-effect="effect-scale" data-toggle="modal" href="#modaldemo8"><i class="fa fa-plus"></i> ADD</a>
                    {{-- modal Admin --}}
                    <div class="modal" id="modaldemo8">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Add Admin</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-body pt-0">
                                        <form action="{{ route('users.admin.tambah') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="is_admin" value="1">
                                            <div class="form-group">
                                                <label for="id">User</label>
                                                @error('id')
                                                    <small style="color: red">{{ $message }}</small>
                                                @enderror
                                                <select name="id" class="form-control select2" style="width: 100%" required autofocus>
                                                    <option label="Choose one"></option>
                                                    @foreach ($users as $usr)
                                                        <option value="{{ $usr->id }}"
                                                            @if(old('id', isset($users) ? $usr->id : '') == $usr->id)
                                                                selected
                                                            @endif
                                                        >
                                                            {{  $usr->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn ripple btn-success" type="submit">Save</button>
                                                <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end modal Admin --}}
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
                    <form action="{{ route('users.admin.semua') }}" method="GET" class="d-flex">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admin as $key => $adm)
                            <tr>
                                <th scope="row">{{ $admin->firstItem() + $key }}</th>
                                <td>{{ $adm->name }}</td>
                                <td><img class="img-thumbnail" src="{{ $adm->image }}" style="width: 100px; height: 100px; object-fit: cover;" alt="Profil User"></td>
                                <td>{{ $adm->email }}</td>
                                <td>{{ $adm->phone }}</td>
                                <td>{{ $adm->community->title ?? '-'}}</td>
                                <td>{{ ($adm->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <form action="{{ route('users.admin.edit', ['id' => $adm->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_admin" value="0">
                                        <button type="submit" class="btn btn-danger">Delete Admin</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($admin->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $admin->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($admin->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $admin->url(1) }}">1</a>
                            </li>
                            @if ($admin->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $admin->lastPage()) as $i)
                            @if ($i >= $admin->currentPage() - 2 && $i <= $admin->currentPage() + 2)
                                @if ($i == $admin->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $admin->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($admin->currentPage() < $admin->lastPage() - 2)
                            @if ($admin->currentPage() < $admin->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $admin->url($admin->lastPage()) }}">{{ $admin->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($admin->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $admin->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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