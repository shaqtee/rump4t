@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')


<div class="container">
    <h2>Edit Comment</h2>
    <div class="card">
        <div class="card-header">
            Komentar dari <b>{{ $comment->user->name }}</b> pada <b> {{ $post->title }}</b>

            <p class="m-2">{{ $comment->komentar }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('socialmedia.moderation.comments.edit',  ["id" => $comment->id_post, "comment_id" => $comment->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="comment">Edit your comment:</label>
                    <textarea id="comment" name="comment" class="form-control" rows="5">{{ old('comment', $comment->content) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Preview:</label>
                    <div id="comment-preview" class="border p-3" style="background-color: #f9f9f9;">
                        {{ old('comment', $comment->content) }}
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('comment').addEventListener('input', function () {
        document.getElementById('comment-preview').innerText = this.value;
    });
</script>


@include('Admin.Layouts.footer')