<div class="col-lg-6 col-xl-6 col-md-12 col-sm-12 mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">Add Data</h4>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('groups.tambah') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title" required autofocus>
                        @error('title')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        {{-- <input type="text" class="form-control" name="description" id="description" placeholder="Description" required autofocus> --}}
                        <textarea class="form-control" name="description" id="description" cols="30" rows="10"></textarea>
                        @error('description')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="region">Region</label>
                        <input type="text" class="form-control" name="location" id="region" placeholder="Region" required autofocus>
                        @error('location')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <p class="mg-b-10">Kabupaten / Kota</p>
                        <select onchange="change_location(this)" data-name="" class="form-control select2" required autofocus>
                            <option label="Choose one"></option>
                            @foreach ($city as $cty)
                            <option value="{{ $cty->id.'_'.$cty->name }}">
                                {{ $cty->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('location')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input id="location" type="hidden" name="location"/>
                        <input id="t_city_id" type="hidden" name="t_city_id"/>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                        <input type="file" class="form-control" name="image" id="image" placeholder="Image" required autofocus onchange="previewImage()">
                        @error('image')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
    function change_location(v){
        console.log($(v).val())
        let dataLocation = $(v).val().split('_');
        $('#t_city_id').val(dataLocation[0]);
        $('#location').val(dataLocation[1]);
    }
</script>