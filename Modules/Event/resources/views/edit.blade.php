@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')


<div class="container">
    <h2>Edit Event</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('events.admin.ubah', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama Event:</label>
                    <input type="text" class="form-control" id="name" name="title" value="{{ old('title', $event->title) }}" required>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal Event:</label>
                    <input type="date" class="form-control" id="date" name="play_date_start" value="{{ old('play_date_start', \Carbon\Carbon::parse($event->play_date_start)->format('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label for="close_registration_date">Tanggal Penutupan Pendaftaran:</label>
                    <input type="date" class="form-control" id="close_registration_date" name="close_registration" value="{{ old('close_registration', $event->close_registration) }}" required>
                </div>
                <div class="form-group">
                    <label for="region">Region:</label>
                    <select class="form-control" id="region" name="region" required>
                        <option value="">Pilih Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region', $event->region) == $region->id ? 'selected' : '' }}>{{ $region->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="location">Lokasi:</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $event->location) }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>
                </div>
                <div class="form-group">
                    <label for="price">Harga:</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{ old('price', $event->price) }}" required>
                </div>
                <div class="form-group">
                    <label for="selected_fields">Pilih Field Tambahan:</label>
                    <div>
                        @php
                            $selectedFields = old('selected_fields', $event->selected_fields ?? []);
                        @endphp
                        <label><input type="checkbox" name="selected_fields[]" value="nomor_anggota" {{ in_array('nomor_anggota', $selectedFields) ? 'checked' : '' }}> Nomor Anggota</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nama" {{ in_array('nama', $selectedFields) ? 'checked' : '' }}> Nama</label>
                        <label><input type="checkbox" name="selected_fields[]" value="no_hp" {{ in_array('no_hp', $selectedFields) ? 'checked' : '' }}> No HP / WA</label>
                        <label><input type="checkbox" name="selected_fields[]" value="hadir" {{ in_array('hadir', $selectedFields) ? 'checked' : '' }}> Akan Hadir?</label>
                        <label><input type="checkbox" name="selected_fields[]" value="pembayaran" {{ in_array('pembayaran', $selectedFields) ? 'checked' : '' }}> Telah Melakukan Pembayaran</label>
                        <label><input type="checkbox" name="selected_fields[]" value="bukti_transfer" {{ in_array('bukti_transfer', $selectedFields) ? 'checked' : '' }}> Upload Bukti Transfer</label>
                        <label><input type="checkbox" name="selected_fields[]" value="ukuran_kaos" {{ in_array('ukuran_kaos', $selectedFields) ? 'checked' : '' }}> Ukuran Kaos</label>
                        <label><input type="checkbox" name="selected_fields[]" value="type_lengan" {{ in_array('type_lengan', $selectedFields) ? 'checked' : '' }}> Type Lengan</label>
                        <label><input type="checkbox" name="selected_fields[]" value="dengan_pendamping" {{ in_array('dengan_pendamping', $selectedFields) ? 'checked' : '' }}> Dengan Pendamping?</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nama_pendamping" {{ in_array('nama_pendamping', $selectedFields) ? 'checked' : '' }}> Nama Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="ukuran_kaos_pendamping" {{ in_array('ukuran_kaos_pendamping', $selectedFields) ? 'checked' : '' }}> Ukuran Kaos Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="type_lengan_pendamping" {{ in_array('type_lengan_pendamping', $selectedFields) ? 'checked' : '' }}> Type Lengan Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nik" {{ in_array('nik', $selectedFields) ? 'checked' : '' }}> NIK</label>
                        <label><input type="checkbox" name="selected_fields[]" value="photo_nik" {{ in_array('photo_nik', $selectedFields) ? 'checked' : '' }}> Upload Photo NIK</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nik_pendamping" {{ in_array('nik_pendamping', $selectedFields) ? 'checked' : '' }}> NIK Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="photo_nik_pendamping" {{ in_array('photo_nik_pendamping', $selectedFields) ? 'checked' : '' }}> Upload Photo NIK Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nominal_pembayaran" {{ in_array('nominal_pembayaran', $selectedFields) ? 'checked' : '' }}> Nominal Pembayaran</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image">Banner:</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Update Event</button>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')