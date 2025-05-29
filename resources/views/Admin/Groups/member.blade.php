<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a class="btn btn-success " data-effect="effect-scale" data-toggle="modal" href="#modalAddMember">
                        <i class="fa fa-plus"></i> ADD
                    </a>
                    {{-- modal Admin --}}
                    <div class="modal" id="modalAddMember">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title">Add Member</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-body pt-0">
                                        <form action="{{ route('groups.addmember',['groups_id' => $groups_id]) }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="t_small_groups_id" name="t_small_groups_id" value="{{ $groups_id }}">
                                            <div class="form-group">
                                                <label for="id">User</label>
                                                @error('id')
                                                    <small style="color: red">{{ $message }}</small>
                                                @enderror
                                                <select name="user_id" class="form-control select2" style="width: 100%" required autofocus>
                                                    <option label="Choose one"></option>
                                                    @foreach ($users as $usr)
                                                        <option value="{{ $usr->id }}">
                                                            {{  $usr->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin">
                                                <label class="mt-0" for="is_admin">Admin</label>
                                                </div>
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
                    <form action="{{ route('groups.member', ['groups_id' => $groups_id]) }}" method="GET" class="d-flex">
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
                            <th>Joined</th>
                            <th>Admin</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = $group->firstItem(); @endphp

                        @foreach($group as $key => $user)
                            @php
                                foreach($user->small_groups as $key => $usg){
                                    if($usg->pivot->t_small_groups_id == $groups_id){
                                        $i = $key;
                                    }
                                }
                                $usr = $user->small_groups[$i];
                            @endphp
                            <tr>
                                <th scope="row">{{ $no++ }}</th>
                                <td>{{ $user->name }}</td>
                                <td><img class="img-thumbnail" src="{{ $user->image }}"  onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" style="width: 100px; height: 100px; object-fit: cover;" alt="Profil User"></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ date('d/m/Y', strtotime($usr->pivot?->created_at)) }}</td>
                                <td>
                                    <div class="custom-control custom-switch w-100 d-flex justify-content-center">
                                        <input type="checkbox" class="custom-control-input" onchange="change_status_admin(this)" id="admin_{{ $usr->pivot?->id }}" {{ $usr->pivot?->is_admin ? 'checked' : '' }}>
                                        <label class="custom-control-label bg-primary" for="admin_{{ $usr->pivot?->id }}"></label>
                                    </div>
                                    <div id="loader-{{ $usr->pivot?->id }}" class="d-none"> loading..</div>
                                </td>
                                <td>
                                    <form action="{{ route('groups.leftmember', ['id' => $usr->pivot?->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Left Member</button>
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
                        @if ($group->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $group->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($group->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url(1) }}">1</a>
                            </li>
                            @if ($group->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $group->lastPage()) as $i)
                            {{-- {{ dump($group->currentPage()) }} --}}
                            @if ($i >= $group->currentPage() - 2 && $i <= $group->currentPage() + 2)
                                @if ($i == $group->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $group->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($group->currentPage() < $group->lastPage() - 2)
                            @if ($group->currentPage() < $group->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $group->url($group->lastPage()) }}">{{ $group->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($group->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $group->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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