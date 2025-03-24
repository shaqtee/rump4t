<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('users.tambah') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        @error('name')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', isset($users) ? $users->name : '') }}" name="name" id="name"
                            placeholder="Name" required autofocus>
                    </div>
                     <div class="form-group">
                        <label for="nickname">Nickname</label>
                        @error('nickname')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('nickname') is-invalid @enderror"
                            value="{{ old('nickname', isset($users) ? $users->nickname : '') }}" name="nickname" id="nickname"
                            placeholder="Nickname" required autofocus>
                    </div>
                    <div class="form-group">
                        @if (isset($users))
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <label for="">Your Image</label>
                            <img class="img-thumbnail wd-100p wd-sm-200 mb-3"
                                src="{{ isset($users) ? $users->image : '' }}" style="display: block;">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        @error('email')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', isset($users) ? $users->email : '') }}" name="email" id="email"
                            placeholder="Email" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        @error('phone')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', isset($users) ? $users->phone : '') }}" name="phone" id="phone"
                            placeholder="Phone" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        @error('gender')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', isset($users) ? ($users->gender == 'L' ? 'Laki-Laki' : 'Perempuan') : '') }}"
                            name="phone" id="phone" placeholder="Phone" required autofocus>
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
                            <input readonly class="form-control fc-datepicker" id="datetimepicker" name="birth_date"
                                type="text" value="{{ old('birth_date', isset($users) ? $users->birth_date : '') }}"
                                placeholder="Birth Date" required autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hcp_index">HCP Index</label>
                        @error('hcp_index')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('hcp_index') is-invalid @enderror"
                            value="{{ old('hcp_index', isset($users) ? $users->hcp_index : '') }}" name="hcp_index"
                            id="hcp_index" placeholder="HCP Index" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="faculty">Faculty</label>
                        @error('faculty')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('faculty') is-invalid @enderror"
                            value="{{ old('faculty', isset($users) ? $users->faculty : '') }}" name="faculty"
                            id="faculty" placeholder="faculty" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="batch">Batch</label>
                        @error('batch')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('batch') is-invalid @enderror"
                            value="{{ old('batch', isset($users) ? $users->batch : '') }}" name="batch"
                            id="batch" placeholder="batch" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="office_name">Office Name</label>
                        @error('office_name')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('office_name') is-invalid @enderror"
                            value="{{ old('office_name', isset($users) ? $users->office_name : '') }}"
                            name="office_name" id="office_name" placeholder="Office Name" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        @error('address')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', isset($users) ? $users->address : '') }}" name="address"
                            id="address" placeholder="Address" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="t_city_id">City</label>
                        @error('t_city_id')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', isset($users) && isset($users->city) ? $users->city->name : '') }}" name="address"
                            id="address" placeholder="Address" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="business_sector">Bussiness Sector</label>
                        @error('business_sector')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text"
                            class="form-control @error('business_sector') is-invalid @enderror"
                            value="{{ old('business_sector', isset($users) ? $users->business_sector : '') }}"
                            name="business_sector" id="business_sector" placeholder="Bussiness Sector" required
                            autofocus>
                    </div>
                    <div class="form-group">
                        <label for="position">Position</label>
                        @error('position')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('position', isset($users) ? $users->position : '') }}" name="position"
                            id="position" placeholder="Position" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="active">Active</label>
                        @error('active')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('active', isset($users) && $users->active) == '1' ? 'Active' : 'Deactive' }}"
                            name="active" type="text" required autofocus>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
