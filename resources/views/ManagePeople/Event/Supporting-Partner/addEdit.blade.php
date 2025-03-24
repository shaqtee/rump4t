<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($sponsors))
                <form action="{{ route('event.manage.sponsor.ubah', ['id' => $sponsors->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('event.manage.sponsor.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="t_event_id">Events</label>
                                <input type="text" class="form-control @error('t_event_id') is-invalid @enderror" value="{{ old('t_event_id', isset($sponsors) ? $sponsors->sponsorEvent->title : $event->title) }}" id="t_event_id" placeholder="Event" required autofocus readonly>
                                <input type="hidden" class="form-control @error('t_event_id') is-invalid @enderror" value="{{ old('t_event_id', isset($sponsors) ? $sponsors->sponsorEvent->id : $event->id) }}" name="t_event_id" id="t_event_id" placeholder="Event" required autofocus>
                                {{-- @error('t_event_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <select name="t_event_id" id="t_event_id" class="form-control select2">
                                    <option label="Choose one" disabled selected>Select Events</option>
                                    @foreach ($events as $evt)
                                        <option value="{{ $evt->id }}" 
                                            @if (old('t_event_id', isset($sponsors) ? $sponsors->t_event_id : '') == $evt->id)
                                                selected
                                            @endif
                                        >
                                            {{ $evt->title }}
                                        </option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                @error('name')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($sponsors) ? $sponsors->name : '') }}" name="name" id="name" placeholder="name" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                @error('image')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image" @if(!$sponsors) required autofocus @endif onchange="previewImage()">
                                @if (isset($sponsors))
                                    <div class="mt-2">
                                        <label for="">Your image</label>
                                        <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($sponsors) ? $sponsors->image : '' }}" style="display: block;">
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                @error('description')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('description') is-invalid @enderror"  value="{{ old('description', isset($sponsors) ? $sponsors->description : '') }}" name="description" id="description" placeholder="Description" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="active">Active</label>
                                @error('active')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <div class="row">
                                    <div class="col">
                                        <label class="rdiobox"><input value="1" name="active" type="radio" {{ old('active', isset($sponsors) && $sponsors->active) == '1' ? 'checked' : '' }} required autofocus> <span>Active</span></label>
                                        <label class="rdiobox"><input value="0" name="active" type="radio" {{ old('active', isset($sponsors) && $sponsors->active) == '0' ? 'checked' : '' }} required autofocus> <span>Deactive</span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="link_website">Website</label>
                                @error('link_website')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('link_website') is-invalid @enderror"  value="{{ old('link_website', isset($sponsors) ? $sponsors->socialMedia->link_website : '') }}" name="link_website" id="link_website" placeholder="Link" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="link_instagram">Instagram</label>
                                @error('link_instagram')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('link_instagram') is-invalid @enderror"  value="{{ old('link_instagram', isset($sponsors) ? $sponsors->socialMedia->link_instagram : '') }}" name="link_instagram" id="link_instagram" placeholder="Link" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="link_facebook">Facebook</label>
                                @error('link_facebook')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('link_facebook') is-invalid @enderror"  value="{{ old('link_facebook', isset($sponsors) ? $sponsors->socialMedia->link_facebook : '') }}" name="link_facebook" id="link_facebook" placeholder="Link" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="link_twitter">Twitter/X</label>
                                @error('link_twitter')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('link_twitter') is-invalid @enderror"  value="{{ old('link_twitter', isset($sponsors) ? $sponsors->socialMedia->link_twitter : '') }}" name="link_twitter" id="link_twitter" placeholder="Link" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="link_youtube">Youtube</label>
                                @error('link_youtube')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('link_youtube') is-invalid @enderror"  value="{{ old('link_youtube', isset($sponsors) ? $sponsors->socialMedia->link_youtube : '') }}" name="link_youtube" id="link_youtube" placeholder="Youtube" required autofocus>
                            </div>
                        </div>
                    </div>  
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>