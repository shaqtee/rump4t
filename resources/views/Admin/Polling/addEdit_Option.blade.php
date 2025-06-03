<div class="mt-3">
    <div class="card box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('polling_option.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="polling_id" value="{{ $polling_id }}">
            
                <div id="options-wrapper">
                    @if (!empty($options) && count($options))
                        @foreach ($options as $opt)
                            <div class="polling-option border p-3 mb-3">
                                <input type="hidden" name="option_id[]" value="{{ $opt->id }}">
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm remove-option float-right">Hapus Opsi</button>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Nilai Opsi</label>
                                    <input type="text" name="option_value[]" class="form-control" value="{{ $opt->option_value }}">
                                </div>
                                <div class="form-group">
                                    <label>Isi Teks Opsi</label>
                                    <textarea name="option_text[]" class="form-control">{{ $opt->option_text }}</textarea>
                                </div>
                                {{-- <div class="form-group">
                                    <label>Gambar Opsi (Opsional)</label>
                                    <input type="file" name="option_image[]" class="form-control">
                                    @if ($opt->option_image)
                                        <img src="{{ asset('storage/' . $opt->option_image) }}" alt="Gambar" style="max-width: 100px; margin-top: 10px;">
                                    @endif
                                </div> --}}
                                <div class="form-group">
                                    <label>Gambar Opsi (Opsional)</label>
                                    <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                                    <input type="file" class="form-control" name="option_image[]" id="image" placeholder="Image" onchange="previewImageOption(this)">
                                    @error('image')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <div class="mt-3">
                                        <img class="wd-100p wd-sm-200" src="{{$opt->option_image }}" alt="">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="polling-option border p-3 mb-3">
                            <input type="hidden" name="option_id[]" value="">
                            <div class="form-group">
                                <label>Nilai Opsi</label>
                                <input type="text" name="option_value[]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Isi Teks Opsi</label>
                                <textarea name="option_text[]" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Gambar Opsi (Opsional)</label>
                                <input type="file" name="option_image[]" class="form-control">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-option">Hapus Opsi</button>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-secondary" id="add-option" style="margin-right: 10px;">+ Tambah Option</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
<script>
    document.getElementById('add-option').addEventListener('click', function () {
        const wrapper = document.getElementById('options-wrapper');
        const newOption = `
        <div class="polling-option border p-3 mb-3">
            <div class="form-group">
                <label>Nilai Opsi (Maks 10 Karakter)</label>
                <input type="text" name="option_value[]" class="form-control" maxlength="10">
            </div>
            <div class="form-group">
                <label>Isi Teks Opsi</label>
                <textarea name="option_text[]" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Gambar Opsi (Opsional)</label>
                <input type="file" name="option_image[]" class="form-control">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-option mt-2">Hapus Opsi</button>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', newOption);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.polling-option').remove();
        }
    });

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
