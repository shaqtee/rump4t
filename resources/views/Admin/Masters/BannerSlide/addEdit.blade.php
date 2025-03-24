<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($bannerSlide))
            <form action="{{ route('banner-slide.update', ['banner_slide' => $bannerSlide->id]) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @else
            <form action="{{ route('banner-slide.store') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($bannerSlide) ? $bannerSlide->name : '') }}" name="name" id="name" placeholder="Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image" @if(!$bannerSlide) required autofocus @endif onchange="previewImage()">
                            @if (isset($bannerSlide))
                                <div class="mt-2">
                                    <label for="">Your Cover</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($bannerSlide) ? $bannerSlide->image : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="on_view">Status View</label>
                            @error('on_view')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="true" name="on_view" type="radio" {{ old('on_view', isset($bannerSlide) && $bannerSlide->on_view) == '1' ? 'checked' : '' }} required autofocus> <span>On</span></label>
                                    <label class="rdiobox"><input value="false" name="on_view" type="radio" {{ old('on_view', isset($bannerSlide) && $bannerSlide->on_view) == '0' ? 'checked' : '' }} required autofocus> <span>Off</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>