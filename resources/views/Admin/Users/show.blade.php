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
                            value="{{ $users->nomor_anggota ?? 'Belum Terisi' }}" name="nomor_anggota" id="nomor_anggota" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="community">Komunitas</label>
                        <input readonly type="text" class="form-control @error('community') is-invalid @enderror"
                            value="{{ $users->community->title ?? 'Belum Terisi' }}" name="community" id="community" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="region">Regional</label>
                        <input readonly type="text" class="form-control @error('region') is-invalid @enderror"
                            value="{{ $region->value ?? 'Belum Terisi' }}" name="region" id="region" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="name">Nama</label>
                        @error('name')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', isset($users) ? $users->name : '') }}" name="name" id="name"
                            placeholder="Name" required autofocus>
                    </div>
                     <div class="form-group">
                        <label for="nickname">Panggilan</label>
                        @error('nickname')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('nickname') is-invalid @enderror"
                            value="{{ old('nickname', isset($users) ? $users->nickname : '') }}" name="nickname" id="nickname"
                            placeholder="Nickname" required autofocus>
                    </div>
                    <div class="form-group">
                        @if (isset($users))
                            <label for="image">Foto</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <label for="">Foto Profil</label>
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
                        <label for="phone">Telepon</label>
                        @error('phone')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', isset($users) ? $users->phone : '') }}" name="phone" id="phone"
                            placeholder="Phone" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        @error('gender')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', isset($users) ? ($users->gender == 'L' ? 'Laki-Laki' : 'Perempuan') : '') }}"
                            name="phone" id="phone" placeholder="Phone" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="birth_place">Tempat Lahir</label>
                        <input readonly type="text" class="form-control @error('birth_place') is-invalid @enderror"
                            value="{{ $users->birth_place ?? 'Belum Terisi' }}" name="birth_place" id="birth_place" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="birth_date">Tanggal Lahir</label>
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
                        <label for="age">Umur</label>
                        <input readonly type="text" class="form-control @error('age') is-invalid @enderror"
                            value="{{ $users->age ?? 'Belum Terisi' }}" name="age" id="age" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        @error('address')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', isset($users) ? $users->address : '') }}" name="address"
                            id="address" placeholder="Address" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Kode Pos</label>
                        <input readonly type="text" class="form-control @error('postal_code') is-invalid @enderror"
                            value="{{ $users->postal_code ?? 'Belum Terisi' }}" name="postal_code" id="postal_code" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="province">Provinsi</label>
                        <input readonly type="text" class="form-control @error('province') is-invalid @enderror"
                            value="{{ $users->province->name ?? 'Belum Terisi' }}" name="province" id="province" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="kota_kabupaten">Kota / Kabupaten</label>
                        <input readonly type="text" class="form-control @error('address') is-invalid @enderror"
                            value="{{ $users->regency->name ?? 'Belum Terisi' }}" name="address" id="kota_kabupaten" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="district">Kecamatan</label>
                        <input readonly type="text" class="form-control @error('district') is-invalid @enderror"
                            value="{{ $users->district->name ?? 'Belum Terisi' }}" name="district" id="district" autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="village">Desa / Kelurahan</label>
                        <input readonly type="text" class="form-control @error('village') is-invalid @enderror"
                            value="{{ $users->village->name ?? 'Belum Terisi' }}" name="village" id="village" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="year_of_entry">Tahun Masuk PTPP</label>
                        <input readonly type="text" class="form-control @error('year_of_entry') is-invalid @enderror"
                            value="{{ $users->year_of_entry ?? 'Belum Terisi' }}" name="year_of_entry" id="year_of_entry" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="year_of_retirement">Tahun Pensiun</label>
                        <input readonly type="text" class="form-control @error('year_of_retirement') is-invalid @enderror"
                            value="{{ $users->year_of_retirement ?? 'Belum Terisi' }}" name="year_of_retirement" id="year_of_retirement" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="retirement_type">Jenis Pensiun</label>
                        <input readonly type="text" class="form-control @error('retirement_type') is-invalid @enderror"
                            value="{{ $retirement_type->value ?? 'Belum Terisi' }}" name="retirement_type" id="retirement_type" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="last_employee_status">Status Karyawan Terakhir</label>
                        <input readonly type="text" class="form-control @error('last_employee_status') is-invalid @enderror"
                            value="{{ $last_employee_status->value ?? 'Belum Terisi' }}" name="last_employee_status" id="last_employee_status" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="position">Jabatan Terakhir</label>
                        @error('position')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('position', isset($users) ? $users->position : '') }}" name="position"
                            id="position" placeholder="Position" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="last_division">Divisi Terakhir</label>
                        <input readonly type="text" class="form-control @error('last_division') is-invalid @enderror"
                            value="{{ $users->last_division ?? 'Belum Terisi' }}" name="last_division" id="last_division" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="spouse_name">Nama Pasangan</label>
                        <input readonly type="text" class="form-control @error('spouse_name') is-invalid @enderror"
                            value="{{ $users->spouse_name ?? 'Belum Terisi' }}" name="spouse_name" id="spouse_name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="shirt_size">Ukuran Kaos</label>
                        <input readonly type="text" class="form-control @error('shirt_size') is-invalid @enderror"
                            value="{{ $shirt_size->value ?? 'Belum Terisi' }}" name="shirt_size" id="shirt_size" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="notes">Keterangan</label>
                        <input readonly type="text" class="form-control @error('notes') is-invalid @enderror"
                            value="{{ $users->notes ?? 'Belum Terisi' }}" name="notes" id="notes" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="ec_name">Nama Kontak Darurat</label>
                        <input readonly type="text" class="form-control @error('ec_name') is-invalid @enderror"
                            value="{{ $users->ec_name ?? 'Belum Terisi' }}" name="ec_name" id="ec_name" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="ec_kinship">Hubungan Kontak Darurat</label>
                        <input readonly type="text" class="form-control @error('ec_kinship') is-invalid @enderror"
                            value="{{ $users->ec_kinship ?? 'Belum Terisi' }}" name="ec_kinship" id="ec_kinship" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="pass_away_status">Status Tutup Usia</label>
                        <input readonly type="text" class="form-control {{ $users->pass_away_status ? 'text-danger' : '' }} @error('pass_away_status') is-invalid @enderror"
                            value="{{ $users->pass_away_status ? 'Sudah Tutup Usia' : '' }}" name="pass_away_status" id="pass_away_status" autofocus>
                    </div>

                    <div class="form-group">
                        <label for="status_anggota">Status Anggota</label>
                        @error('status_anggota')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('status_anggota', isset($users) && $users->status_anggota == '1')  ? 'Umum' : 'Khusus' }}"
                            name="status_anggota" type="text" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="active">Status Akun</label>
                        @error('active')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly class="form-control @error('position') is-invalid @enderror"
                            value="{{ old('active', isset($users) && $users->active) == '1' ? 'Aktif' : 'Non-Aktif' }}"
                            name="active" type="text" required autofocus>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
