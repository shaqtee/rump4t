<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a class="btn btn-success" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8"><i class="fa fa-plus"></i> ADD</a>
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
                    <form action="{{ route('event.registrant.semua', ['event_id' => $event_id]) }}" method="GET" class="d-flex">
                            <select id="searchIndex" class="form-control">
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
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Event Title</th>
                            <th>User Name</th>
                            <th>Status Approve</th>
                            <th>Proof Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $key => $mbr)
                            <tr>
                                <th scope="row">{{ $members->firstItem() + $key }}</th>
                                <td>{{ $mbr->event->title }}</td>
                                <td>{{ $mbr->user->name }}</td>
                                <td>
                                    <div class="d-flex">
                                        <form action="{{ route('event.registrant.ubah', ['id' => $mbr->id]) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                                <select name="approve" class="form-control select2" {{-- (old('approve',isset($members)?$mbr->approve:''))=='PAID'||(old('approve',isset($members)?$mbr->approve:''))=='CANCEL'?'disabled':'' --}}>
                                                    <option label="Choose one" disabled>Select Status Approve</option>
                                                    <option value="WAITING_FOR_PAYMENT" {{ (old('approve', isset($members) ? $mbr->approve : '')) == 'WAITING_FOR_PAYMENT' ? 'selected' : ''}}>WAITING FOR PAYMENT</option>
                                                    <option value="PAID" {{ (old('approve', isset($members) ? $mbr->approve : '')) == 'PAID' ? 'selected' : ''}}>PAID</option>
                                                    <option value="CANCEL" {{ (old('approve', isset($members) ? $mbr->approve : '')) == 'CANCEL' ? 'selected' : ''}}>CANCEL</option>
                                                </select>
                                                <button type="submit" class="btn btn-success" {{-- (old('approve',isset($members)?$mbr->approve:''))=='PAID'||(old('approve',isset($members)?$mbr->approve:''))=='CANCEL'?'disabled':'' --}}>Save</button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    @if (!empty($mbr->image))
                                        <img class="img-thumbn ail" src="{{ $mbr->image }}" style="width: 100px; height: 100px; object-fit: fill;" alt="Profil User">
                                    @else
                                        <span class="badge badge-success">Belum Dibayar</span>
                                    @endif

                                    {{-- <div class="accordion" id="accordionExample">
                                        <div class="card">
                                            <div class="card-header bg-success" id="heading{{ $members->firstItem() + $key }}">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapse{{ $members->firstItem() + $key }}" aria-expanded="false" aria-controls="collapse{{ $members->firstItem() + $key }}">
                                                        <i class="si si-cursor-move mr-2"></i> Show Proof Payment
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse{{ $members->firstItem() + $key }}" class="collapse" aria-labelledby="heading{{ $members->firstItem() + $key }}" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-6">
                                                            @if (!empty($mbr->image))
                                                                <img class="img-fluid rounded" src="{{ $mbr->image }}" alt="banner image">
                                                            @else
                                                                <p>Belum Di Bayar</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </td>
                                <td>
                                    <form action="{{ route('registrant.hapus', ['id' => $mbr->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">DELETE</button>
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
                        @if ($members->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $members->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($members->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $members->url(1) }}">1</a>
                            </li>
                            @if ($members->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $members->lastPage()) as $i)
                            @if ($i >= $members->currentPage() - 2 && $i <= $members->currentPage() + 2)
                                @if ($i == $members->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $members->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($members->currentPage() < $members->lastPage() - 2)
                            @if ($members->currentPage() < $members->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $members->url($members->lastPage()) }}">{{ $members->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($members->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $members->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-forward"></i></span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="modal" id="modaldemo8">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">Add Player</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body pt-0">
                                <form action="{{ route('event.registrant.tambah') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="t_user_id">User</label>
                                        @error('t_user_id')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                        <select name="t_user_id" class="form-control select2" style="width: 100%" required autofocus>
                                            <option label="Choose Users"></option>
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
                                        <input type="hidden" name="t_event_id" value="{{ $event_id }}">
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
        </div>
    </div>
</div>