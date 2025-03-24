<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            {{-- <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a href="{{ route('community.manage.tambah') }}" class="btn btn-success "><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            {{-- <th>Image</th> --}}
                            <th>Description</th>
                            <th>Region</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <th scope="row">1. {{-- $loop->iteration --}}</th>
                                <td>{{ $community->title }}</td>
                                {{-- <td><img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ $community->image }}" alt=""></td> --}}
                                <td>{{ $community->description }}</td>
                                <td>{{ $community->location }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <a class="btn btn-info " href="{{ route('community.manage.ubah', ['id' => $community->id]) }}">EDIT</a>
                                            <a class="btn btn-info " href="{{ route('community.manage.addmanagepeopleview', ['community_id' => $community->id]) }}">Organizer</a>
                                            <a class="btn btn-info " href="{{ route('community.manage.member', ['community_id' => $community->id]) }}">Member</a>
                                            <a class="btn btn-info " href="{{ route('community.manage.leaderboard', ['community_id' => $community->id]) }}">Leaderboard</a>
                                            {{-- <form action="{{ route('community.hapus', ['id' => $community->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger ">DELETE</button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </div>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>