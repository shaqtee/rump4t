<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($users))
                <form action="{{ route('users.ubah', ['id' => $users->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('users.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                {{-- here --}}
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="t_community_id">Komunitas</label>
                            {{-- @if(!isset($users->t_community_id) && empty($users->t_community_id)) <small> Haven't Joined Yet </small> @endif --}}
                            @error('t_community_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            {{-- <select name="t_community_id" id="t_community_id" class="form-control select2" autofocus {{ !isset($users->t_community_id) && empty($users->t_community_id) ? 'disabled' : ''}}> --}}
                            <select name="t_community_id" id="t_community_id" class="form-control select2" autofocus >
                                <option label="Choose one"></option>
                            @foreach ($community as $com)
                                <option value="{{ $com->id }}"
                                    @if(old('t_community_id', isset($users) ? $users->t_community_id : '') == $com->id)
                                        selected
                                    @endif
                                >
                                    {{  $com->title }}
                                </option>
                            @endforeach
                        </select>
                        </div>

                        {{-- REGION --}}
                        <div class="form-group">
                            <label for="region">Regional</label>
                            @error('region')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="region" id="region" class="form-control select2" required autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($regions as $r)
                                    <option value="{{ $r->id }}" 
                                        @if (old('region', isset($users) ? $users->region : '') == $r->id)
                                            selected
                                        @endif
                                    >
                                        {{ $r->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Nama</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($users) ? $users->name : '') }}" name="name" id="name" placeholder="Nama Lengkap" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="nickname">Panggilan</label>
                            @error('nickname')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('nickname') is-invalid @enderror"  value="{{ old('nickname', isset($users) ? $users->nickname : '') }}" name="nickname" id="nickname" placeholder="Nama Panggilan" autofocus>
                        </div>

                        <div class="form-group">
                            <label for="image">Foto</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image" @if(!$users) autofocus @endif onchange="previewImage()">
                            @if (isset($users))
                                <div class="mt-2">
                                    <label for="">Foto Profil</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($users) ? $users->image : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            @error('email')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('email') is-invalid @enderror"  value="{{ old('email', isset($users) ? $users->email : '') }}" name="email" id="email" placeholder="Email" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            @error('phone')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"  value="{{ old('phone', isset($users) ? $users->phone : '') }}" name="phone" id="phone" placeholder="Nomor Telepon" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="gender">Jenis Kelamin</label>
                            @error('gender')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="gender" id="gender" class="form-control select" autofocus>
                                <option label="Choose one" disabled selected></option>
                                <option value="L" {{ (old('gender', isset($users) ? $users->gender : '') == 'L') ? 'selected' : ''}}>Laki-Laki</option>
                                <option value="P" {{ (old('gender', isset($users) ? $users->gender : '') == 'P') ? 'selected' : ''}}>Perempuan</option>
                            </select>
                        </div>

                        {{-- TEMPAT LAHIR --}}
                        <div class="form-group">
                            <label for="birth_place">Tempat Lahir</label>
                            @error('birth_place')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('birth_place') is-invalid @enderror"  value="{{ old('birth_place', isset($users) ? $users->birth_place : '') }}" name="birth_place" id="birth_place" placeholder="Tempat Lahir" autofocus>
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
                                <input class="form-control fc-datepicker" id="datetimepicker" name="birth_date" type="text" value="{{ old('birth_date', isset($users) ? $users->birth_date : '') }}" placeholder="Tanggal Lahir" autofocus>
                            </div>
                        </div>

                        {{-- UMUR --}}
                        <div class="form-group">
                            <label for="birth_place">Umur</label>
                            @error('age')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('age') is-invalid @enderror"  value="{{ old('age', isset($users) ? $users->age : '') }}" name="age" id="age" placeholder="Umur" readonly>
                        </div>
                        {{-- <div class="form-group">
                            <label for="hcp_index">HCP Index</label>
                            @error('hcp_index')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('hcp_index') is-invalid @enderror"  value="{{ old('hcp_index', isset($users) ? $users->hcp_index : '') }}" name="hcp_index" id="hcp_index" placeholder="HCP Index" required autofocus>
                        </div> --}}

                        {{-- FACULTY --}}
                        {{-- <div class="form-group">
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
                        </div> --}}

                        {{-- BATCH --}}              
                        {{-- <div class="form-group">
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
                        </div> --}}

                        {{-- OFFICE NAME --}}
                        {{-- <div class="form-group">
                            <label for="office_name">Office Name</label>
                            @error('office_name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('office_name') is-invalid @enderror"  value="{{ old('office_name', isset($users) ? $users->office_name : '') }}" name="office_name" id="office_name" placeholder="Office Name" required autofocus>
                        </div> --}}

                        <div class="form-group">
                            <label for="address">Alamat</label>
                            @error('address')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('address') is-invalid @enderror"  value="{{ old('address', isset($users) ? $users->address : '') }}" name="address" id="address" placeholder="Alamat" autofocus>
                        </div>

                        {{-- KODE POS --}}
                        <div class="form-group">
                            <label for="postal_code">Kode Pos</label>
                            @error('postal_code')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror"  value="{{ old('postal_code', isset($users) ? $users->postal_code : '') }}" name="postal_code" id="postal_code" placeholder="Kode Pos" autofocus>
                        </div>

                        {{-- Propinsi --}}
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            @error('provinsi')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="provinsi" id="provinsi" class="form-control select2" data-area="{{ $users->kota_kabupaten ?? '' }}" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($provinces as $p)
                                    <option value="{{ $p->id }}" 
                                        @if (old('provinsi', isset($users) ? $users->provinsi : '') == $p->id)
                                            selected
                                        @endif
                                    >
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KOTA / KABUPATEN --}}
                        <div class="form-group">
                            <label for="kota_kabupaten">Kota / Kabupaten</label>&nbsp;&nbsp;
                            <div class="d-none loader-city spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            @error('kota_kabupaten')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="kota_kabupaten" id="kota_kabupaten" class="form-control select2" data-area="{{ $users->kecamatan ?? '' }}" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($regencies as $r)
                                    <option value="{{ $r->id }}" 
                                        @if (old('kota_kabupaten', isset($users) ? $users->kota_kabupaten : '') == $r->id)
                                            selected
                                        @endif
                                    >
                                        {{ $r->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KECAMATAN --}}
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>&nbsp;&nbsp;
                            <div class="d-none loader-district spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            @error('kecamatan')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="kecamatan" id="kecamatan" class="form-control select2" data-area="{{ $users->desa_kelurahan ?? '' }}" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($districts as $d)
                                    <option value="{{ $d->id }}" 
                                        @if (old('kecamatan', isset($users) ? $users->kecamatan : '') == $d->id)
                                            selected
                                        @endif
                                    >
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DESA / KELURAHAN --}}
                        <div class="form-group">
                            <label for="desa_kelurahan">Desa / Kelurahan</label>&nbsp;&nbsp;
                            <div class="d-none loader-village spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            @error('desa_kelurahan')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="desa_kelurahan" id="desa_kelurahan" class="form-control select2" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($villages as $v)
                                    <option value="{{ $v->id }}" 
                                        @if (old('desa_kelurahan', isset($users) ? $users->desa_kelurahan : '') == $v->id)
                                            selected
                                        @endif
                                    >
                                        {{ $v->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TAHUN MASUK PTPP --}}
                        <div class="form-group">
                            <label for="year_of_entry">Tahun Masuk PTPP</label>
                            @error('year_of_entry')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input class="form-control yearPicker" id="year_of_entry" name="year_of_entry" type="text" value="{{ old('year_of_entry', isset($users) ? $users->year_of_entry : '') }}" placeholder="Tahun Masuk" autofocus>
                            </div>
                        </div>

                        {{-- TAHUN PENSIUN PTPP --}}
                        <div class="form-group">
                            <label for="year_of_retirement">Tahun Pensiun PTPP</label>
                            @error('year_of_retirement')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input class="form-control yearPicker" id="year_of_retirement" name="year_of_retirement" type="text" value="{{ old('year_of_retirement', isset($users) ? $users->year_of_retirement : '') }}" placeholder="Tahun Pensiun" autofocus>
                            </div>
                        </div>

                        {{-- JENIS PENSIUN --}}
                        <div class="form-group">
                            <label for="retirement_type">Jenis Pensiun</label>
                            @error('retirement_type')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="retirement_type" id="retirement_type" class="form-control select2" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($retirement_type as $rt)
                                    <option value="{{ $rt->id }}" 
                                        @if (old('retirement_type', isset($users) ? $users->retirement_type : '') == $rt->id)
                                            selected
                                        @endif
                                    >
                                        {{ $rt->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS KARYAWAN TERAKHIR --}}
                        <div class="form-group">
                            <label for="last_employee_status">Status Karyawan Terakhir</label>
                            @error('last_employee_status')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="last_employee_status" id="last_employee_status" class="form-control select2" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($last_employee_status as $les)
                                    <option value="{{ $les->id }}" 
                                        @if (old('last_employee_status', isset($users) ? $users->last_employee_status : '') == $les->id)
                                            selected
                                        @endif
                                    >
                                        {{ $les->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BUSSINESS SECTOR --}}
                        {{-- <div class="form-group">
                            <label for="business_sector">Bussiness Sector</label>
                            @error('business_sector')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('business_sector') is-invalid @enderror"  value="{{ old('business_sector', isset($users) ? $users->business_sector : '') }}" name="business_sector" id="business_sector" placeholder="Bussiness Sector" required autofocus>
                        </div> --}}

                        {{-- JABATAN TERAKHIR --}}
                        <div class="form-group">
                            <label for="position">Jabatan Terakhir</label>
                            @error('position')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('position') is-invalid @enderror"  value="{{ old('position', isset($users) ? $users->position : '') }}" name="position" id="position" placeholder="Jabatan Terakhir" autofocus>
                        </div>

                        {{-- DIVISI TERAKHIR --}}
                        <div class="form-group">
                            <label for="last_division">Divisi Terakhir</label>
                            @error('last_division')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('last_division') is-invalid @enderror"  value="{{ old('last_division', isset($users) ? $users->last_division : '') }}" name="last_division" id="last_division" placeholder="Divisi Terakhir" autofocus>
                        </div>

                        {{-- NAMA PASANGAN --}}
                        <div class="form-group">
                            <label for="spouse_name">Nama Pasangan</label>
                            @error('spouse_name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('spouse_name') is-invalid @enderror"  value="{{ old('spouse_name', isset($users) ? $users->spouse_name : '') }}" name="spouse_name" id="spouse_name" placeholder="Nama Pasangan" autofocus>
                        </div>

                        {{-- UKURAN KAOS --}}
                        <div class="form-group">
                            <label for="shirt_size">Ukuran Kaos</label>
                            @error('shirt_size')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="shirt_size" id="shirt_size" class="form-control select2" autofocus>
                                <option label="Choose one" selected disabled></option>
                                @foreach ($shirt_size as $ss)
                                    <option value="{{ $ss->id }}" 
                                        @if (old('shirt_size', isset($users) ? $users->shirt_size : '') == $ss->id)
                                            selected
                                        @endif
                                    >
                                        {{ $ss->value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="form-group">
                            <label for="notes">Keterangan</label>
                            @error('notes')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('notes') is-invalid @enderror"  value="{{ old('notes', isset($users) ? $users->notes : '') }}" name="notes" id="notes" placeholder="Keterangan" autofocus>
                        </div>

                        {{-- NAMA KONTAK DARURAT --}}
                        <div class="form-group">
                            <label for="ec_name">Nama Kontak Darurat</label>
                            @error('ec_name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('ec_name') is-invalid @enderror"  value="{{ old('ec_name', isset($users) ? $users->ec_name : '') }}" name="ec_name" id="ec_name" placeholder="Nama Kontak Darurat" autofocus>
                        </div>

                        {{-- HUBUNGAN KELUARGA KONTAK DARURAT --}}
                        <div class="form-group">
                            <label for="ec_kinship">Hubungan Kontak Darurat</label>
                            @error('ec_kinship')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('ec_kinship') is-invalid @enderror"  value="{{ old('ec_kinship', isset($users) ? $users->ec_kinship : '') }}" name="ec_kinship" id="ec_kinship" placeholder="Hubungan Keluarga Kontak Darurat" autofocus>
                        </div>

                        {{-- PHONE KONTAK DARURAT --}}
                        <div class="form-group">
                            <label for="ec_contact">Telepon Kontak Darurat</label>
                            @error('ec_contact')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('ec_contact') is-invalid @enderror"  value="{{ old('ec_contact', isset($users) ? $users->ec_contact : '') }}" name="ec_contact" id="ec_contact" placeholder="Telepon Kontak Darurat" autofocus>
                        </div>

                        {{-- PASS AWAY STATUS --}}
                        <div class="form-group">
                            <input name="pass_away_status" type="checkbox" id="pass_away_status" {{ isset($users) && $users->pass_away_status ? 'checked' : '' }}>
                            <label for="pass_away_status">
                                Status Tutup Usia
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="status_anggota">Status Anggota</label>
                            @error('status_anggota')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="status_anggota" type="radio" {{ old('status_anggota', isset($users) && $users->status_anggota == '1' ? 'checked' : '')  }} autofocus> <span>Umum</span></label>
                                    <label class="rdiobox"><input value="2" name="status_anggota" type="radio" {{ old('status_anggota', isset($users) && $users->status_anggota == '2'? 'checked' : '')  }} autofocus> <span>Khusus</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="active">Status Akun</label>
                            @error('active')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="active" type="radio" {{ old('active', isset($users) && $users->active) == '1' ? 'checked' : '' }} required autofocus> <span>Aktifkan</span></label>
                                    <label class="rdiobox"><input value="0" name="active" type="radio" {{ old('active', isset($users) && $users->active) == '0' ? 'checked' : '' }} required autofocus> <span>Non-Aktifkan</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>