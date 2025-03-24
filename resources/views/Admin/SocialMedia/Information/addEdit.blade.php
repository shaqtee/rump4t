<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($information))
            <form action="{{ route('informations.update', ['information' => $information->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('informations.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="title">Title</label>
                            @error('title')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('title') is-invalid @enderror"  value="{{ old('title', isset($information) ? $information->title : '') }}" name="title" id="title" placeholder="Title" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="t_event_id">Event</label>
                            @error('t_event_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select class="form-control select2" name="t_event_id" required autofocus>
                                <option label="Choose one"></option>
                                @foreach ($events as $evt)
                                    <option value="{{ $evt->id }}"
                                        @if (old('t_event_id', isset($information) ? $information->t_event_id : '') == $evt->id)
                                            selected
                                        @endif
                                    >
                                        {{ $evt->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', isset($information) ? $information->image : '') }}" name="image" id="image" placeholder="Image" @if(!$information) required autofocus @endif onchange="previewImage()">
                        </div>
                        @if (isset($information))
                            <div class="form-group">
                                <img class="img-thumbnail mb-3" src="{{ isset($information) ? $information->image : '' }}" alt="" width="300" height="200">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="file">File</label>
                            @error('file')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="file" class="form-control @error('file') is-invalid @enderror" value="{{ old('file', isset($information) ? $information->file : '') }}" name="file" id="file" placeholder="file" @if(!$information) autofocus @endif>
                            @if (isset($information))
                            <br>
                                <table>
                                    <thead>
                                        <tr>
                                            <td>Download</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a target="_blank" href="{{ $information->file }}" class="badge badge-info">Download file</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="description">Description</label>
                            @error('description')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <textarea class="form-control" name="description" rows="25" required autofocus>{{ isset($information) ? $information->description : old('description') }}</textarea>
                        </div>
                    </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>
