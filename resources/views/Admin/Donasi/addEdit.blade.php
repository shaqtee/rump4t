<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($donation))
            <form action="{{ route('donasi_admin.edit', $donation->id) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('donasi_admin.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                {{-- TITLE --}}
                <div class="form-group">
                    <label for="title">Judul Donasi</label>
                    @error('title')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $donation->title ?? '') }}">
                </div>

                 {{-- START DATE --}}
                 <div class="form-group">
                    <label for="start_date">Tanggal Mulai</label>
                    @error('start_date')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    @php
                        $now = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    @endphp
                    <input type="datetime-local" name="start_date" id="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', isset($donation->start_date) ? \Carbon\Carbon::parse($donation->start_date)->format('Y-m-d\TH:i') : '') }}" min="{{ $now }}">
                </div>

                {{-- END DATE --}}
                <div class="form-group">
                    <label for="end_date">Tanggal Selesai</label>
                    @error('end_date')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    @php
                        $now = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    @endphp
                    <input type="datetime-local" name="end_date" id="end_date"
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date', isset($donation->end_date) ? \Carbon\Carbon::parse($donation->end_date)->format('Y-m-d\TH:i') : '') }}" min="{{ $now }}">
                </div>

                {{-- DESCRIPTION --}}
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    @error('description')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $donation->description ?? '') }}</textarea>
                </div>

                {{-- Target Sumbangan --}}
                <div class="form-group">
                    <label for="target_sumbangan">Target Sumbangan</label>
                    @error('target_sumbangan')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Rp.</span>
                        <input type="text" name="target_sumbangan" id="target_sumbangan" class="form-control @error('target_sumbangan') is-invalid @enderror"
                        value="{{ old('target_sumbangan', $donation->target_sumbangan ?? '') }}">
                    </div>
                </div>
                <br>
                <br>
                <h4 class="card-title">Data Penggalang Dana</h4>

                {{-- GAMBAR PENGGALANG DANA --}}
                <div class="form-group">
                    <label>Gambar Penggalang Dana (Opsional)</label>
                    <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                    <input type="file" class="form-control" name="img_penggalang_dana" id="image" placeholder="Image" onchange="previewImageOption(this)">
                    @error('image')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="mt-3">
                        <img class="wd-100p wd-sm-200" src="{{$donation->img_penggalang_dana ?? ""}}" alt="">
                    </div>
                </div>

                {{-- NAMA PENGGALANG DANA --}}
                <div class="form-group">
                    <label for="nama_penggalang_dana">Nama Penggalang Dana</label>
                    @error('nama_penggalang_dana')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="nama_penggalang_dana" id="nama_penggalang_dana" class="form-control @error('nama_penggalang_dana') is-invalid @enderror"
                        value="{{ old('nama_penggalang_dana', $donation->nama_penggalang_dana ?? '') }}">
                </div>

                {{-- NAMA BANK --}}
                <div class="form-group">
                    <label for="nama_bank">Nama Bank</label>
                    @error('nama_bank')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="nama_bank" id="nama_bank" class="form-control @error('nama_bank') is-invalid @enderror"
                        value="{{ old('nama_bank', $donation->nama_bank ?? '') }}">
                </div>

                {{-- NOMOR REKENING --}}
                <div class="form-group">
                    <label for="nomor_rekening">Nomor Rekening</label>
                    @error('nomor_rekening')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror"
                        value="{{ old('nomor_rekening', $donation->nomor_rekening ?? '') }}">
                </div>

                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        function previewImageOption(input) {
        const wrapper = input.closest('.form-group');
        const preview = wrapper.querySelector('.image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>