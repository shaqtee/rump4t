<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($golfCourse))
            <form action="{{ route('golf-course.update', ['golf_course' => $golfCourse->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('golf-course.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        {{-- <div class="form-group">
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
                        </div> --}}
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($golfCourse) ? $golfCourse->name : '') }}" name="name" id="name" placeholder="Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            @error('address')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('address') is-invalid @enderror"  value="{{ old('address', isset($golfCourse) ? $golfCourse->address : '') }}" name="address" id="address" placeholder="Address" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            @error('contact')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('contact') is-invalid @enderror"  value="{{ old('contact', isset($golfCourse) ? $golfCourse->contact : '') }}" name="contact" id="contact" placeholder="Contact" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="contact_person_name">Contact Person</label>
                            <div class="row">
                                <div class="col">
                                    @error('contact_person_name')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <input type="text" class="form-control @error('contact_person_name') is-invalid @enderror"  value="{{ old('contact_person_name', isset($golfCourse) ? $golfCourse->contact_person_name : '') }}" name="contact_person_name" id="contact_person_name" placeholder="Name" required autofocus>
                                </div>
                                <div class="col">
                                    @error('contact_person_Phone')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <input type="text" class="form-control @error('contact_person_Phone') is-invalid @enderror"  value="{{ old('contact_person_Phone', isset($golfCourse) ? $golfCourse->contact_person_Phone : '') }}" name="contact_person_Phone" id="contact_person_Phone" placeholder="Number" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number_par">Number Of Par</label>
                            @error('number_par')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('number_par') is-invalid @enderror"  value="{{ old('number_par', isset($golfCourse) ? $golfCourse->number_par : '') }}" name="number_par" id="number_par" placeholder="Number Of Par" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="is_staging">Active</label>
                            @error('is_staging')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="is_staging" type="radio" {{ old('is_staging', isset($golfCourse) && $golfCourse->is_staging) == '1' ? 'checked' : '' }} required autofocus> <span>Active</span></label>
                                    <label class="rdiobox"><input value="0" name="is_staging" type="radio" {{ old('is_staging', isset($golfCourse) && $golfCourse->is_staging) == '0' ? 'checked' : '' }} required autofocus> <span>Deactive</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>