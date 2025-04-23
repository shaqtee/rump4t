
@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')
{{-- @section('content') --}}
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>Edit News</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('news-admin.ubah', $news->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $news->title) }}" required>
                </div>

                <div class="form-group">
                    <label for="region">Region</label>
                    <select name="region_id" id="region" class="form-control" required>
                        <option value="">Global</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ old('region_id', $news->region_id) == $region->id ? 'selected' : '' }}>
                                {{ $region->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" class="form-control" rows="5" required>{{ old('content', $news->content) }}</textarea>
                </div>
                <input type="image" src="{{ $news->image }}" alt="">
                {{-- <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div> --}}
                   {{-- dropdown featured true or false  --}}
                <div class="form-group">
                    <label for="featured">Featured</label>
                    <select name="featured" id="featured" class="form-control">
                        <option value="0" {{ old('featured', $news->featured) == 0 ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('featured', $news->featured) == 1 ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image">Unggah File yang ingin diubah (Jika ada)</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>



                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('news.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
{{-- @endsection --}}

@include('Admin.Layouts.footer')
