<div class="row">
    @foreach ($election->candidates as $key => $candidate)
        <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12 mt-3">
            <div class="card  box-shadow-0">
                <div class="card-header">
                    <h4 class="card-title mb-1">{{ $title }} Winning candidates {{ $key + 1 }}</h4>
                </div>
                <div class="card-body pt-0">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control"  value="{{ old('name', isset($candidate) ? $candidate->user->name : '') }}" name="name" id="name" placeholder="Name" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    {{-- <input type="text" class="form-control"  value="{{ old('link', isset($candidate) ? $candidate->link : '') }}" name="link" id="link" placeholder="Link" disabled> --}}
                                    @if (isset($candidate->link))
                                        <div class="embed-responsive embed-responsive-16by9 mt-2">
                                            <iframe class="embed-responsive-item" src="{{ isset($candidate) ? $candidate->link : '' }}" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    {{-- <input type="file" class="form-control" value="{{ old('image', isset($candidate) ? $candidate->image : '') }}" name="image" id="image" placeholder="Image" @if(!$candidate) @endif> --}}
                                    @if (isset($candidate) && isset($candidate->image))
                                        <div class="form-group">
                                            <img class="img-thumbnail" src="{{ isset($candidate) ? $candidate->image : '' }}" alt="" width="300" height="200">
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" rows="5" disabled>{{ isset($information) ? $information->description : old('description') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="voters_count">Voters Count</label>
                                    <input type="text" class="form-control" value="{{ old('voters_count', isset($candidate) ? $candidate->voters_count : '') }}" placeholder="voters_count" disabled>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
