<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($photos))
                <form action="{{ route('event.album.photo.ubah', ['id' => $photos->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('event.album.photo.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="t_album_id">Albums</label>
                            @error('t_album_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('t_album_id') is-invalid @enderror"  value="{{ old('t_album_id', isset($photos) ? $photos->photoEvent->name : $albums->name) }}" id="t_album_id" placeholder="Album" required autofocus readonly>
                            <input type="hidden" class="form-control @error('t_album_id') is-invalid @enderror"  value="{{ old('t_album_id', isset($photos) ? $photos->photoEvent->id : $albums->id) }}" name="t_album_id" required autofocus>
                            {{-- <select name="t_album_id" id="t_album_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Albums</option>
                                @foreach ($albums as $alb)
                                    <option value="{{ $alb->id }}" 
                                        @if (old('t_album_id', isset($photos) ? $photos->t_album_id : '') == $alb->id)
                                            selected
                                        @endif
                                    >
                                        {{ $alb->name }}
                                    </option>
                                @endforeach
                            </select> --}}
                        </div>                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($photos) ? $photos->name : '') }}" name="name" id="name" placeholder="name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="image" @if(!$photos) required autofocus @endif onchange="previewImage()">
                            @if (isset($photos))
                                <div class="mt-2">
                                    <label for="">Your Image</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($photos) ? $photos->image : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="active">Active</label>
                            @error('active')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="active" type="radio" {{ old('active', isset($photos) && $photos->active) == '1' ? 'checked' : '' }} required autofocus> <span>Active</span></label>
                                    <label class="rdiobox"><input value="0" name="active" type="radio" {{ old('active', isset($photos) && $photos->active) == '0' ? 'checked' : '' }} required autofocus> <span>Deactive</span></label>
                                </div>
                            </div>
                        </div>
                    </div>  
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>