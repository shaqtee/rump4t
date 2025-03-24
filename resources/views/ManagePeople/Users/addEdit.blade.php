<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($users))
                <form action="{{ route('users.manage.ubah', ['id' => $users->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('users.manage.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($users) ? $users->name : '') }}" name="name" id="name" placeholder="Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image" @if(!$users) required autofocus @endif onchange="previewImage()">
                            @if (isset($users))
                                <div class="mt-2">
                                    <label for="">Your Image</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($users) ? $users->image : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            @error('email')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('email') is-invalid @enderror"  value="{{ old('email', isset($users) ? $users->email : '') }}" name="email" id="email" placeholder="Email" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            @error('phone')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"  value="{{ old('phone', isset($users) ? $users->phone : '') }}" name="phone" id="phone" placeholder="Phone" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            @error('gender')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="gender" id="gender" class="form-control select" required autofocus>
                                <option label="Choose one" disabled selected></option>
                                <option value="L" {{ (old('gender', isset($users) ? $users->gender : '')) ? 'selected' : ''}}>Laki-Laki</option>
                                <option value="P" {{ (old('gender', isset($users) ? $users->gender : '')) ? 'selected' : ''}}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="birth_date">Birth Date</label>
                            @error('birth_date')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input class="form-control fc-datepicker" name="birth_date" type="text" value="{{ old('birth_date', isset($users) ? $users->birth_date : '') }}" placeholder="Birth Date" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hcp_index">HCP Index</label>
                            @error('hcp_index')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('hcp_index') is-invalid @enderror"  value="{{ old('hcp_index', isset($users) ? $users->hcp_index : '') }}" name="hcp_index" id="hcp_index" placeholder="HCP Index" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="faculty">Faculty</label>
                            @error('faculty')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="faculty" id="faculty" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Faculty</option>
                                @foreach ($faculty as $fcty)
                                    <option value="{{ $fcty->value1 }}" 
                                        @if (old('faculty', isset($users) ? $users->faculty : '') == $fcty->value1)
                                            selected
                                        @endif
                                    >
                                        {{ $fcty->value1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="batch">Batch</label>
                            @error('batch')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="batch" id="batch" class="form-control select2" required autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}"
                                        @if(old('batch', isset($users) ? $users->batch : '') == $year)
                                            selected
                                        @endif
                                    >
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="office_name">Office Name</label>
                            @error('office_name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('office_name') is-invalid @enderror"  value="{{ old('office_name', isset($users) ? $users->office_name : '') }}" name="office_name" id="office_name" placeholder="Office Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            @error('address')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('address') is-invalid @enderror"  value="{{ old('address', isset($users) ? $users->address : '') }}" name="address" id="address" placeholder="Address" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="t_city_id">City</label>
                            @error('t_city_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_city_id" id="t_city_id" class="form-control select2" required autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($city as $c)
                                    <option value="{{ $c->id }}" 
                                        @if (old('t_city_id', isset($users) ? $users->t_city_id : '') == $c->id)
                                            selected
                                        @endif
                                    >
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="business_sector">Bussiness Sector</label>
                            @error('business_sector')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('business_sector') is-invalid @enderror"  value="{{ old('business_sector', isset($users) ? $users->business_sector : '') }}" name="business_sector" id="business_sector" placeholder="Bussiness Sector" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            @error('position')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('position') is-invalid @enderror"  value="{{ old('position', isset($users) ? $users->position : '') }}" name="position" id="position" placeholder="Position" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="active">Active</label>
                            @error('active')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="active" type="radio" {{ old('active', isset($users) && $users->active) == '1' ? 'checked' : '' }} required autofocus> <span>Active</span></label>
                                    <label class="rdiobox"><input value="0" name="active" type="radio" {{ old('active', isset($users) && $users->active) == '0' ? 'checked' : '' }} required autofocus> <span>Deactive</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_admin">Manage Event</label>
                            @error('is_admin')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="3" name="is_admin" type="radio" {{ old('is_admin', isset($users) && $users->is_admin) == '3' ? 'checked' : '' }}> <span>Yes</span></label>
                                    <label class="rdiobox"><input value="0" name="is_admin" type="radio" {{ old('is_admin', isset($users) && $users->is_admin) == '0' || '1' || '2' ? 'checked' : '' }}> <span>No</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>