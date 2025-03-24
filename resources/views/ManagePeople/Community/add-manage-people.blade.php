<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
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
                                <td><img class="img-thumbnail" src="{{ $usr->image }}" style="width: 100px; height: 100px; object-fit: fill;" alt="Profil User"></td>
                                <td>{{ $usr->email }}</td>
                                <td>{{ $usr->phone }}</td>
                                <td>{{ $usr->community->title }}</td>
                                <td>{{ ($usr->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <div class="d-flex">
                                        {{-- <a class="{{ (!$usr->t_group_id) ? 'modal-effect btn btn-outline-primary ' : 'btn btn-primary disabled' }}" data-effect="effect-scale" data-toggle="modal" href="#modaldemo8{{ $usr->id }}" >Manage Poeple</a> --}}
                                        <form action="{{ route('community.manage.addmanagepeople') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="t_user_id" value="{{ $usr->id }}">
                                            <input type="hidden" name="type" value="remove">
                                            <button type="submit" class="btn btn-danger ">REMOVE MANAGE</button>
                                        </form>
                                    </div>
                                    {{-- modal Manage Poeple --}}
                                    <div class="modal" id="modaldemo8{{ $usr->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Add Manage People</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card-body pt-0">
                                                        <form action="{{ route('community.manage.addmanagepeople') }}" method="POST">
                                                            @csrf
                                                            <div class="">
                                                                <input type="hidden" name="t_user_id" value="{{ $usr->id }}">
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
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($users->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $users->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            <li class="page-item {{ ($users->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($users->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $users->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
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
                    <form action="{{ route('community.manage.addmanagepeople') }}" method="POST">
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