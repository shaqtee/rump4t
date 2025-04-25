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
                        <label for="nomor_anggota">Nomor Anggota</label>
                        <input readonly type="text" class="form-control text-danger @error('nomor_anggota') is-invalid @enderror"
                            value="{{ $users->nomor_anggota ?? 'Empty' }}" name="nomor_anggota" id="nomor_anggota" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="community">Community</label>
                        <input readonly type="text" class="form-control @error('community') is-invalid @enderror"
                            value="{{ $users->community->title ?? 'Empty' }}" name="community" id="community" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="region">Region</label>
                        <input readonly type="text" class="form-control @error('region') is-invalid @enderror"
                            value="{{ $region->value ?? 'Empty' }}" name="region" id="region" autofocus>
                    </div>

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
                        <label for="birth_place">Birth Place</label>
                        <input readonly type="text" class="form-control @error('birth_place') is-invalid @enderror"
                            value="{{ $users->birth_place ?? 'Empty' }}" name="birth_place" id="birth_place" autofocus>
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
                        <label for="age">Age</label>
                        <input readonly type="text" class="form-control @error('age') is-invalid @enderror"
                            value="{{ $users->age ?? 'Empty' }}" name="age" id="age" autofocus>
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
                        <label for="postal_code">Postal Code</label>
                        <input readonly type="text" class="form-control @error('postal_code') is-invalid @enderror"
                            value="{{ $users->postal_code ?? 'Empty' }}" name="postal_code" id="postal_code" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="province">Province</label>
                        <input readonly type="text" class="form-control @error('province') is-invalid @enderror"
                            value="{{ $users->province->name ?? 'Empty' }}" name="province" id="province" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="kota_kabupaten">City</label>
                        <input readonly type="text" class="form-control @error('address') is-invalid @enderror"
                            value="{{ $users->regency->name ?? 'Empty' }}" name="address" id="kota_kabupaten" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="district">District</label>
                        <input readonly type="text" class="form-control @error('district') is-invalid @enderror"
                            value="{{ $users->district->name ?? 'Empty' }}" name="district" id="district" autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="village">Village</label>
                        <input readonly type="text" class="form-control @error('village') is-invalid @enderror"
                            value="{{ $users->village->name ?? 'Empty' }}" name="village" id="village" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="year_of_entry">Year of Entry</label>
                        <input readonly type="text" class="form-control @error('year_of_entry') is-invalid @enderror"
                            value="{{ $users->year_of_entry ?? 'Empty' }}" name="year_of_entry" id="year_of_entry" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="year_of_retirement">Year of Retirement</label>
                        <input readonly type="text" class="form-control @error('year_of_retirement') is-invalid @enderror"
                            value="{{ $users->year_of_retirement ?? 'Empty' }}" name="year_of_retirement" id="year_of_retirement" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="retirement_type">Retirement Type</label>
                        <input readonly type="text" class="form-control @error('retirement_type') is-invalid @enderror"
                            value="{{ $retirement_type->value ?? 'Empty' }}" name="retirement_type" id="retirement_type" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="last_employee_status">Last Employee Status</label>
                        <input readonly type="text" class="form-control @error('last_employee_status') is-invalid @enderror"
                            value="{{ $last_employee_status->value ?? 'Empty' }}" name="last_employee_status" id="last_employee_status" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="position">Last Position</label>
                        @error('position')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('position', isset($users) ? $users->position : '') }}" name="position"
                            id="position" placeholder="Position" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="last_division">Last Division</label>
                        <input readonly type="text" class="form-control @error('last_division') is-invalid @enderror"
                            value="{{ $users->last_division ?? 'Empty' }}" name="last_division" id="last_division" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="spouse_name">Spouse Name</label>
                        <input readonly type="text" class="form-control @error('spouse_name') is-invalid @enderror"
                            value="{{ $users->spouse_name ?? 'Empty' }}" name="spouse_name" id="spouse_name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="shirt_size">T-Shirt Size</label>
                        <input readonly type="text" class="form-control @error('shirt_size') is-invalid @enderror"
                            value="{{ $shirt_size->value ?? 'Empty' }}" name="shirt_size" id="shirt_size" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <input readonly type="text" class="form-control @error('notes') is-invalid @enderror"
                            value="{{ $users->notes ?? 'Empty' }}" name="notes" id="notes" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="ec_name">Emergency Contact Name</label>
                        <input readonly type="text" class="form-control @error('ec_name') is-invalid @enderror"
                            value="{{ $users->ec_name ?? 'Empty' }}" name="ec_name" id="ec_name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="ec_kinship">Emergency Contact Kinship</label>
                        <input readonly type="text" class="form-control @error('ec_kinship') is-invalid @enderror"
                            value="{{ $users->ec_kinship ?? 'Empty' }}" name="ec_kinship" id="ec_kinship" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="status_anggota">Member Status</label>
                        @error('status_anggota')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('status_anggota', isset($users) && $users->status_anggota == '1')  ? 'Regular' : 'Privilege' }}"
                            name="status_anggota" type="text" required autofocus>
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
