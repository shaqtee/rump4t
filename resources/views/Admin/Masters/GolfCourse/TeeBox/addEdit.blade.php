<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($teeBox))
            <form action="{{ route('golf-course.teebox.update', ['golf_course_id' => $golfCourse->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('golf-course.teebox.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <input type="hidden" class="form-control @error('id') is-invalid @enderror"  value="{{ old('t_golf_course_id', isset($golfCourse) ? $golfCourse->id : '') }}" name="t_golf_course_id" id="t_golf_course_id" placeholder="t_golf_course_id" required autofocus>
                        <div class="form-group">
                            <label for="tee_type">Tee Box</label>
                            @error('tee_type')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="tee_type" id="tee_type" class="form-control select2" required autofocus>
                                    <option label="Choose one"></option>
                                @foreach ($MasterTeeBox as $mtb)
                                    <option value="{{ $mtb->value1 }}"
                                        @if (old('tee_type', isset($teeBox) && $teeBox->tee_type == $mtb->value1) == $mtb->value1)
                                        selected
                                        @endif
                                    >
                                        {{  $mtb->value1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">description</label>
                            @error('description')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('description') is-invalid @enderror"  value="{{ old('description', isset($teeBox) ? $teeBox->description : '') }}" name="description" id="description" placeholder="description" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="course_rating">Course Rating</label>
                            @error('course_rating')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('course_rating') is-invalid @enderror"  value="{{ old('course_rating', isset($teeBox) ? $teeBox->course_rating : '') }}" name="course_rating" id="course_rating" placeholder="Course Rating" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="slope_rating">Slope Rating</label>
                            @error('slope_rating')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('slope_rating') is-invalid @enderror"  value="{{ old('slope_rating', isset($teeBox) ? $teeBox->slope_rating : '') }}" name="slope_rating" id="slope_rating" placeholder="Slope Rating" required autofocus>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>