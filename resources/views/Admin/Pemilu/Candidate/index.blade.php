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
                                    <h6 class="modal-title">Add Candidate</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-body pt-0">
                                        <form action="{{ route('pemilu.candidate.add', $pemilu_id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" id="t_pemilu_id" name="t_pemilu_id" value="{{ $pemilu_id }}">
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
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                                <label class="mt-0" for="is_active">Activate</label>
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
                    <form action="#" method="GET" class="d-flex">
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
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @php
                            dump($users);
                        @endphp --}}
                        @php $no = $candidates->firstItem(); @endphp

                        @foreach($candidates as $key => $c)
                            @php
                                foreach($c->candidates as $key => $usg){
                                    if($usg->pivot->t_pemilu_id == $pemilu_id){
                                        $i = $key;
                                    }
                                }
                                $usr = $c->candidates[$i];
                            @endphp
                            <tr>
                                <th scope="row">{{ $no++ }}</th>
                                <td>{{ $c->name }}</td>
                                <td><img class="img-thumbnail" src="{{ $c->image }}"  onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" style="width: 100px; height: 100px; object-fit: cover;" alt="Profil User"></td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->phone }}</td>
                                <td>{{ date('d/m/Y', strtotime($usr->pivot?->created_at)) }}</td>
                                <td>
                                    <div class="custom-control custom-switch w-100 d-flex justify-content-center">
                                        <input type="checkbox" class="custom-control-input" onchange="change_status_active(this)" id="active_{{ $usr->pivot?->id }}" {{ $usr->pivot?->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label bg-primary" for="active_{{ $usr->pivot?->id }}"></label>
                                    </div>
                                    <div id="loader-{{ $usr->pivot?->id }}" class="d-none"> loading..</div>
                                </td>
                                <td>
                                    <form action="{{ route('pemilu.candidate.left', $usr->pivot?->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Left Candidate</button>
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
                        @if ($candidates->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $candidates->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($candidates->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $candidates->url(1) }}">1</a>
                            </li>
                            @if ($candidates->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $candidates->lastPage()) as $i)
                            @if ($i >= $candidates->currentPage() - 2 && $i <= $candidates->currentPage() + 2)
                                @if ($i == $candidates->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $candidates->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($candidates->currentPage() < $candidates->lastPage() - 2)
                            @if ($candidates->currentPage() < $candidates->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $candidates->url($candidates->lastPage()) }}">{{ $candidates->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($candidates->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $candidates->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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

<script>
    function change_status_active(v){
        let id = v.id.split('_')[1]
        console.log(id);
        $('#loader-'+id).removeClass('d-none');
        $.post(
            "{{ route('pemilu.candidate.activate') }}",
            {_token:"{{ csrf_token() }}", id},
            function(data){
                $('#loader-'+id).addClass('d-none');
                console.log(data);
            }
        )
    }
</script>