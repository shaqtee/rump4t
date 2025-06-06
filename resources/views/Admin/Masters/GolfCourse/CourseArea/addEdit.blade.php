<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($course_area))
            <form action="{{ route('golf-course.course_area.update', ['golf_course_id' => $course_area->id]) }}" method="POST">
            @method('PATCH')
            @else
            <form action="{{ route('golf-course.course_area.store') }}" method="POST">
            @endif
                @csrf
                    <div class="">
                        <input type="hidden" class="form-control @error('id') is-invalid @enderror"  value="{{ old('id', isset($golfCourse) ? $golfCourse : '') }}" name="course_id" id="course_id" required autofocus>
                        <div class="form-group">
                            <label for="course_name">Course Area</label>
                            @error('course_name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror"  value="{{ old('course_name', isset($course_area) ? $course_area->course_name : '') }}" name="course_name" id="course_name" placeholder="Course Area" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="holes_number">Hole Number</label>
                            @error('holes_number')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('holes_number') is-invalid @enderror"  value="{{ old('holes_number', isset($course_area) ? $course_area->holes_number : '') }}" name="holes_number" id="holes_number" placeholder="Hole Number" required autofocus>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>