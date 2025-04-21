
@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Create News</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('news-admin.tambah') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="description" rows="5" class="form-control" required></textarea>
                </div>
                {{-- region --}}
                <div class="mb-3">
                    <label for="region" class="form-label">Region</label>
                    <select id="region" name="region_id" class="form-control">
                        <option value="">Global</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->value }}</option>
                        @endforeach
                    </select>
                {{-- image --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" id="image" name="image" class="form-control" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')
