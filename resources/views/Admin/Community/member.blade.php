{{-- <div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
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
                    <form action="" method="post" class="d-flex">
                        @csrf

                        <input type="text" class="form-control me-2" name="search" placeholder="Search...">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Nickname</th>
                            <th>Manage People</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $user)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $user->members->name }}</td>
                                <td>{{ $user->members->group->name }}
                                    
                                </td>
                                <td>
                                    <form action="{{ route('community.addmanagepeople', ['id' => $user->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="d-flex align-items-center">
                                            <select name="flag_manage" id="approve" class="form-control select2 mr-2" >
                                                <option label="Choose one" disabled>Select Status Approve</option>
                                                <option value="WAITING_FOR_PAYMENT" {{ (old('approve', isset($community) ? $user->approve : '')) == 'WAITING_FOR_PAYMENT' ? 'selected' : ''}}>WAITING FOR PAYMENT</option>
                                            </select>
                                            <button type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($members->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $members->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $members->lastPage(); $i++)
                            <li class="page-item {{ ($members->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $members->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($members->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $members->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            {{-- <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a href="{{ route('users.tambah') }}" class="btn btn-success "><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div> --}}
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
                    <form action="{{ route('community.member', ['community_id' => $community_id]) }}" method="GET" class="d-flex">
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
                            {{-- <th>Gender</th> --}}
                            {{-- <th>Birth</th> --}}
                            {{-- <th>Faculty</th> --}}
                            {{-- <th>Batch</th> --}}
                            {{-- <th>Office Name</th> --}}
                            {{-- <th>Address</th> --}}
                            {{-- <th>City</th> --}}
                            {{-- <th>Business Sector</th> --}}
                            {{-- <th>Position</th> --}}
                            {{-- <th>Manage People</th> --}}
                            <th>Community</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $usr)
                            <tr>
                                <th scope="row">{{ $users->firstItem() + $key }}</th>
                                <td>{{ $usr->name }}</td>
                                <td><img class="img-thumbnail" src="{{ $usr->image }}" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" style="width: 100px; height: 100px; object-fit: cover;" alt="Profil User"></td>
                                <td>{{ $usr->email }}</td>
                                <td>{{ $usr->phone }}</td>
                                {{-- <td>{{ ($usr->gender == 'L') ? 'Laki-Laki' : 'Perempuan' }}</td> --}}
                                {{-- <td>{{ \Carbon\Carbon::parse($usr->birth_date)->format('d-M-Y') }}</td> --}}
                                {{-- <td>{{ $usr->faculty }}</td> --}}
                                {{-- <td>{{ \Carbon\Carbon::parse($usr->batch)->format('Y') }}</td> --}}
                                {{-- <td>{{ $usr->office_name }}</td> --}}
                                {{-- <td>{{ $usr->address }}</td> --}}
                                {{-- <td>{{ $usr->city->name ?? '-' }}</td> --}}
                                {{-- <td>{{ $usr->business_sector }}</td> --}}
                                {{-- <td>{{ $usr->position }}</td> --}}
                                {{-- <td>{{ (!$usr->t_group_id) ? 'NO' : 'YES' }}</td> --}}
                                <td>{{ $usr->community->title }}</td>
                                <td>{{ ($usr->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <a class="{{ (!$usr->t_group_id) ? 'modal-effect btn btn-outline-primary ' : 'btn btn-primary  disabled' }}" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8{{ $usr->id }}" >Add To Manage People</a>
                                    {{-- <form action="{{ route('community.addmanagepeople') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="t_user_id" value="{{ $usr->id }}">
                                        <input type="hidden" name="type" value="remove">
                                        <button type="submit" class="btn btn-danger ">REMOVE MANAGE</button>
                                    </form> --}}
                                    {{-- modal Manage Poeple --}}
                                    <div class="modal" id="modaldemo8{{ $usr->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Add Manage People</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card-body pt-0">
                                                        <form action="{{ route('community.addmanagepeople') }}" method="POST">
                                                            @csrf
                                                            <div class="">
                                                                <input type="hidden" name="t_user_id" value="{{ $usr->id }}">
                                                                {{-- <div class="form-group">
                                                                    <label for="t_community_id">Community</label>
                                                                    @error('t_community_id')
                                                                        <small style="color: red">{{ $message }}</small>
                                                                    @enderror
                                                                    <select name="t_community_id" id="t_community_id" class="form-control select2" required autofocus>
                                                                            <option label="Choose one"></option>
                                                                        @foreach ($community as $com)
                                                                            <option value="{{ $com->id }}"
                                                                                @if(old('t_community_id', isset($community) ? $com->t_community_id : '') == $com->t_community_id)
                                                                                    selected
                                                                                @endif
                                                                            >
                                                                                {{  $com->title }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div> --}}
                                                                <div class="form-group">
                                                                    <label for="t_group_id">Position</label>
                                                                    @error('t_group_id')
                                                                        <small style="color: red">{{ $message }}</small>
                                                                    @enderror
                                                                    <select name="t_group_id" id="t_group_id" class="form-control select2" required autofocus>
                                                                            <option label="Choose one"></option>
                                                                        @foreach ($groups as $g)
                                                                            <option value="{{ $g->id }}"
                                                                                @if(old('t_group_id', isset($groups) ? $g->id : '') == $g->id)
                                                                                    selected
                                                                                @endif
                                                                            >
                                                                                {{  $g->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
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
                                    {{-- end modal Manage Poeple --}}
                                </td>
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

<div class="modal" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Modal Header</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body pt-0">
                    <form action="{{ route('community.addmanagepeople') }}" method="POST">
                        @csrf
                        <div class="">
                            <div class="form-group">
                                <label for="t_community_id">Community</label>
                                @error('t_community_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <select name="t_community_id" id="t_community_id" class="form-control select2" required autofocus>
                                        <option label="Choose one"></option>
                                    @foreach ($community as $com)
                                        <option value="{{ $com->id }}"
                                            @if(old('t_community_id', isset($community) ? $com->t_community_id : '') == $com->t_community_id)
                                                selected
                                            @endif
                                        >
                                            {{  $com->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="t_group_id">Group</label>
                                @error('t_group_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <select name="t_group_id" id="t_group_id" class="form-control select2" required autofocus>
                                        <option label="Choose one"></option>
                                    @foreach ($groups as $g)
                                        <option value="{{ $g->id }}"
                                            @if(old('t_group_id', isset($groups) ? $g->id : '') == $g->id)
                                                selected
                                            @endif
                                        >
                                            {{  $g->name }}
                                        </option>
                                    @endforeach
                                </select>
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