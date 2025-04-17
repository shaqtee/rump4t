@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Edit Subcomment</h2>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Preview:</strong> {{ $subcomment->komentar }}
            </div>
            <p><strong>Created At:</strong> {{ $subcomment->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Created By:</strong> {{ $subcomment->user->name }}</p>
            <form action="{{ route('socialmedia.moderation.subcomments.ubah', [$post->id , $comment->id , $subcomment->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" class="form-control" rows="4" required>{{ $subcomment->content }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')
