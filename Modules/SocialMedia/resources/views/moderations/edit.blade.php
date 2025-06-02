@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')


<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Edit Post</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('socialmedia.moderation.ubah', $post->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}">
                </div>

                <div class="form-group mt-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required>{{ $post->desc }}</textarea>
                </div>

                <div class="form-group mt-3">
                    <label for="image">Existing Image</label>
                    <div class="mb-3">
                        <img src="{{  $post->url_cover_image }}" alt="Existing Image" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                    <label for="image">Upload New Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ !empty($post->t_small_groups_id) ? route('groups.posting.posts', ['groups_id' => $post->t_small_groups_id]) : route('socialmedia.moderation.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>


@include('Admin.Layouts.footer')
