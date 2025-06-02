{{-- <style>
    trix-toolbar [data-trix-button-group="file-tools"]{
        display: none;
    }
</style> --}}
<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($posting))
            <form action="{{ route('community.posting.ubah', ['id' => $posting->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('community.posting.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="t_community_id">Community</label>
                            @error('t_community_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            {{-- {{ dump($posting) }} --}}
                            <select name="t_community_id" id="t_community_id" class="form-control select2" required autofocus>
                                    <option label="Choose one"></option>
                                @foreach ($community as $com)
                                    <option value="{{ $com->id }}"
                                        @if(old('t_community_id', isset($posting) ? $posting->t_community_id : '') == $com->id)
                                            selected
                                        @endif
                                    >
                                        {{  $com->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            @error('title')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('title') is-invalid @enderror"  value="{{ old('title', isset($posting) ? $posting->title : '') }}" name="title" id="title" placeholder="Title" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', isset($posting) ? $posting->image : '') }}" name="image" id="image" placeholder="Image" @if(!$posting) required autofocus @endif onchange="previewImage()">
                        </div>
                        @if (isset($posting))
                            <div class="form-group">
                                <img class="img-thumbnail mb-3" src="{{ isset($posting) ? $posting->image : '' }}" alt="" width="300" height="200">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="active">Active</label>
                            @error('active')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="active" type="radio" {{ old('active', isset($posting) && $posting->active) == '1' ? 'checked' : '' }} required autofocus> <span>Active</span></label>
                                    <label class="rdiobox"><input value="0" name="active" type="radio" {{ old('active', isset($posting) && $posting->active) == '0' ? 'checked' : '' }} required autofocus> <span>Deactive</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="content">Content</label>
                            @error('content')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <textarea class="form-control" placeholder="Body" name="content" rows="25" required autofocus>
                                {{ isset($posting) ? $posting->content : old('content') }}
                            </textarea>
                        </div>
                    </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>