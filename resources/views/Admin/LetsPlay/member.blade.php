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
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($members->memberLetsPlay as $key => $lp)
                            <tr>
                                <th scope="row">{{ $members->memberLetsPlay->firstItem() + $key }}</th>
                                <td>{{ $lp->name }}</td>
                                <td><img src="{{ $lp->image }}" width="300" alt=""></td>
                                <td>{{ $lp->email }}</td>
                                <td>{{ $lp->phone }}</td>
                            </tr>
                        @endforeach --}}
                        @foreach ($members as $key => $lp)
                            <tr>
                                <th scope="row">{{ $members->firstItem() + $key }}</th>
                                <td>{{ $lp->name }}</td>
                                <td><img class="img-thumbnail" src="{{ $lp->image }}" style="width: 100px; height: 100px; object-fit: fill;" alt=""></td>
                                <td>{{ $lp->email }}</td>
                                <td>{{ $lp->phone }}</td>
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

            <div class="modal" id="modaldemo8">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">Add Player</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body pt-0">
                                <form action="{{ route('letsplay.tambahPemain') }}" method="POST">
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
                                        <input type="hidden" name="t_lets_play_id" value="{{ $lets_play_id }}">
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