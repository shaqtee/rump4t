<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start">
                <div class="col-auto">
                    <a href="{{ route('users.manage.tambah') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
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
                            {{-- <th>Gender</th> --}}
                            {{-- <th>Birth</th> --}}
                            {{-- <th>HCP Index</th> --}}
                            {{-- <th>Faculty</th> --}}
                            {{-- <th>Batch</th> --}}
                            {{-- <th>Office Name</th> --}}
                            {{-- <th>Address</th> --}}
                            {{-- <th>City</th> --}}
                            {{-- <th>Business Sector</th> --}}
                            {{-- <th>Position</th> --}}
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
                                {{-- <td>{{ ($usr->gender == 'L') ? 'Laki-Laki' : 'Perempuan' }}</td> --}}
                                {{-- <td>{{ \Carbon\Carbon::parse($usr->birth_date)->format('d-M-Y') }}</td> --}}
                                {{-- <td>{{ $usr->hcp_index }}</td> --}}
                                {{-- <td>{{ $usr->faculty }}</td> --}}
                                {{-- <td>{{ \Carbon\Carbon::parse($usr->batch)->format('Y') }}</td> --}}
                                {{-- <td>{{ $usr->office_name }}</td> --}}
                                {{-- <td>{{ $usr->address }}</td> --}}
                                {{-- <td>{{ $usr->city->name ?? '-' }}</td> --}}
                                {{-- <td>{{ $usr->business_sector }}</td> --}}
                                {{-- <td>{{ $usr->position }}</td> --}}
                                <td>{{ $usr->community->title ?? '-'}}</td>
                                <td>{{ ($usr->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <a class="btn btn-info " href="{{ route('users.manage.ubah', ['id' => $usr->id]) }}">EDIT</a>
                                            <a class="btn btn-info " href="{{ route('users.manage.lihat', ['id' => $usr->id]) }}">SHOW</a>
                                            <a class="btn btn-info " href="{{ route('users.manage.gamescore', ['id' => $usr->id]) }}">Game Score</a>
                                            <a class="btn btn-info " href="{{ route('users.manage.hcpindex', ['id' => $usr->id]) }}">Handicap</a>
                                            {{-- <form action="{{ route('users.manage.hapus', ['id' => $usr->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger ">DELETE</button>
                                            </form> --}}
                                        </div>
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