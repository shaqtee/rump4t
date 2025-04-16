@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $post->title }}</h2>
            <p class="card-text">{{ $post->content }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Comments</h3>
            @foreach($comments as $comment)
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>
                        <small class="text-muted">Posted on {{ $comment->created_at->format('d M Y, H:i') }}</small>
                    </div>
                </div>
            @endforeach

            <form action="{{ route('socialmedia.moderation.comments' , $post->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="content">Add a Comment</label>
                    <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
</div>


@include('Admin.Layouts.footer')
