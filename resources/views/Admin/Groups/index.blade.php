<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start">
                <div class="col-auto">
                    <a href="{{ route('groups.tambah') }}" class="btn btn-success d-flex align-items-center justify-content-center mt-3"> <i class="fa fa-plus mr-2"></i> ADD</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Region</th>
                            <th colspan="5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($community as $key => $com)
                            <tr>
                                <th scope="row">{{ $community->firstItem() + $key }}</th>
                                <td>{{ $com->title }}</td>
                                <td><img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ $com->image }}" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" alt=""></td>
                                <td>{{ $com->description }}</td>
                                <td>{{ $com->location }}</td>
                                <td> 
                                    <a class="btn btn-info " href="{{ route('groups.ubah', ['id' => $com->id]) }}">Edit</a>
                                </td>
                                <td> 
                                    <a class="btn btn-info " href="{{ route('groups.member', ['groups_id' => $com->id]) }}">Member</a>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <form action="{{ route('groups.hapus', ['id' => $com->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger ">DELETE</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>