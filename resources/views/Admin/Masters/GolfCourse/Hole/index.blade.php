<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('golf-course.hole.create', ['golf_course_id' => $golfCourse->id]) }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Golf Course</label>
                @error('name')
                    <small style="color: red">{{ $message }}</small>
                @enderror
                <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($golfCourse) ? $golfCourse->name : '') }}" name="name" id="name" placeholder="Name" readonly required autofocus>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hole Number</th>
                            <th>Par</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hole as $key => $h)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $h->hole_number }}</td>
                                <td>{{ $h->par }}</td>
                                <td>
                                        <a class="btn btn-info " href="{{ route('golf-course.hole.edit', ['golf_course_id' => $h->id]) }}">EDIT</a>
                                </td>
                                <td>
                                    <form action="{{ route('golf-course.hole.delete', ['golf_course_id' => $h->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>