@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container">
    <h2>Create Event</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Event : </label>
                    <input type="text" class="form-control" id="name" name="title" required>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal Event:</label>
                    <input type="date" class="form-control" id="date" name="play_date_start" required>
                </div>
                {{-- close_registration_date date --}}
                <div class="form-group">
                    <label for="close_registration_date">Tanggal Penutupan Pendaftaran:</label>
                    <input type="date" class="form-control" id="close_registration_date" name="close_registration" required>
                </div>
                {{-- region --}}
                <div class="form-group">
                    <label for="region">Region:</label>
                    <select class="form-control" id="region" name="region" required>
                        <option value="">Pilih Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="location">Lokasi:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskkripsi:</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                <!-- // biaya -->
                <div class="form-group">
                    <label for="price">Harga:</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                </div>
                <!-- // add image form -->
                <div class="form-group">
                    <label for="selected_fields">Pilih Field Tambahan:</label>
                    <div>
                        <label><input type="checkbox" name="selected_fields[]" value="nomor_anggota"> Nomor Anggota</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nama"> Nama</label>
                        <label><input type="checkbox" name="selected_fields[]" value="no_hp"> No HP / WA</label>
                        <label><input type="checkbox" name="selected_fields[]" value="hadir"> Akan Hadir?</label>
                        <label><input type="checkbox" name="selected_fields[]" value="pembayaran"> Telah Melakukan Pembayaran</label>
                        <label><input type="checkbox" name="selected_fields[]" value="bukti_transfer"> Upload Bukti Transfer</label>
                        <label><input type="checkbox" name="selected_fields[]" value="ukuran_kaos"> Ukuran Kaos</label>
                        <label><input type="checkbox" name="selected_fields[]" value="type_lengan"> Type Lengan</label>
                        <label><input type="checkbox" name="selected_fields[]" value="dengan_pendamping"> Dengan Pendamping?</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nama_pendamping"> Nama Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="ukuran_kaos_pendamping"> Ukuran Kaos Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="type_lengan_pendamping"> Type Lengan Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nik"> NIK</label>
                        <label><input type="checkbox" name="selected_fields[]" value="photo_nik"> Upload Photo NIK</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nik_pendamping"> NIK Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="photo_nik_pendamping"> Upload Photo NIK Pendamping</label>
                        <label><input type="checkbox" name="selected_fields[]" value="nominal_pembayaran"> Nominal Pembayaran</label>
                    </div>
                </div>


                <div class="form-group">
                    <label for="image">Banner:</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')