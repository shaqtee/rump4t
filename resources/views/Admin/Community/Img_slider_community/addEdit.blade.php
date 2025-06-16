<div class="mt-3">
    <div class="card box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('community_image.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="community_id" value="{{ $community_id }}">
            
                <div id="options-wrapper">
                    @if (!empty($images) && count($images))
                        @foreach ($images as $img)
                            <div class="community-img border p-3 mb-3">
                                <input type="hidden" name="img_id[]" value="{{ $img->id }}">
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm remove-option float-right">Hapus Opsi</button>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label>Gambar</label>
                                    <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                                    <input type="file" class="form-control" name="url_image[]" id="image" placeholder="Image" onchange="previewImageOption(this)">
                                    @error('image')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <div class="mt-3">
                                        <img class="wd-100p wd-sm-200" src="{{$img->url_image }}" alt="">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="community-img border p-3 mb-3">
                            <input type="hidden" name="img_id[]" value="">
                            <div class="form-group">
                                <label>Gambar</label>
                                <input type="file" name="url_image[]" class="form-control">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-option">Hapus Opsi</button>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-secondary" id="add-image" style="margin-right: 10px;">+ Tambah Gambar</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
</div>
<script>
    document.getElementById('add-image').addEventListener('click', function () {
        const wrapper = document.getElementById('options-wrapper');
        const newOption = `
        <div class="community-img border p-3 mb-3">
            <div>
                <button type="button" class="btn btn-danger btn-sm remove-option float-right">Hapus Opsi</button>
            </div>
            <div class="form-group">
                <label>Gambar</label>
                <input type="file" name="url_image[]" class="form-control">
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', newOption);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-option')) {
            e.target.closest('.community-img').remove();
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
