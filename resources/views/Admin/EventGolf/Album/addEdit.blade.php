<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($albums))
                <form action="{{ route('event.album.ubah', ['id' => $albums->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('event.album.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="t_event_id">Events</label>
                            @error('t_event_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_event_id" id="t_event_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Event</option>
                                @foreach ($event as $evt)
                                    <option value="{{ $evt->id }}" 
                                        @if (old('t_event_id', isset($albums) ? $albums->t_event_id : '') == $evt->id)
                                            selected
                                        @endif
                                    >
                                        {{ $evt->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($albums) ? $albums->name : '') }}" name="name" id="name" placeholder="name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Cover</label>
                            @error('cover')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('cover') is-invalid @enderror" name="cover" id="image" placeholder="Cover" @if(!$albums) required autofocus @endif onchange="previewImage()">
                            @if (isset($albums))
                                <div class="mt-2">
                                    <label for="">Your Cover</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($albums) ? $albums->cover : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            @error('description')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('description') is-invalid @enderror"  value="{{ old('description', isset($albums) ? $albums->description : '') }}" name="description" id="description" placeholder="Description" required autofocus>
                        </div>
                    </div>  
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>