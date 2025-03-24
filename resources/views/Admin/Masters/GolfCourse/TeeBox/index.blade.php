<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('golf-course.teebox.create', ['golf_course_id' => $golfCourse->id]) }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
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
                            <th>Tee Box</th>
                            <th>description</th>
                            <th>Course Rating</th>
                            <th>Slope Rating</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teeBox as $key => $tb)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $tb->tee_type }}</td>
                                <td>{{ $tb->description }}</td>
                                <td>{{ $tb->course_rating }}</td>
                                <td>{{ $tb->slope_rating }}</td>
                                <td>
                                    <a class="btn btn-info " href="{{ route('golf-course.teebox.edit', ['golf_course_id' => $tb->id]) }}">EDIT</a> 
                                </td>
                                <td>
                                    <form action="{{ route('golf-course.teebox.delete', ['golf_course_id' => $tb->id]) }}" method="POST">
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
            {{-- <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($teeBox->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $teeBox->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $teeBox->lastPage(); $i++)
                            <li class="page-item {{ ($teeBox->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $teeBox->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($teeBox->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $teeBox->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>