<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($hole))
            <form action="{{ route('golf-course.hole.update', ['golf_course_id' => $golfCourse->course_id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('golf-course.hole.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <input type="hidden" class="form-control @error('id') is-invalid @enderror"  value="{{ old('id', isset($golfCourse) ? $golfCourse->id : '') }}" name="course_id" id="id" required autofocus>
                        <div class="form-group">
                            <label for="hole_number">Hole Number (1-18)</label>
                            @error('hole_number')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('hole_number') is-invalid @enderror"  value="{{ old('hole_number', isset($hole) ? $hole->hole_number : '') }}" name="hole_number" id="hole_number" placeholder="Hole Number" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="par">Par</label>
                            @error('par')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('par') is-invalid @enderror"  value="{{ old('par', isset($hole) ? $hole->par : '') }}" name="par" id="par" placeholder="Par" required autofocus>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>