@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $post->title }}</h2>
            {{-- show image  --}}
            @if($post->url_cover_image)
                <img src="{{ $post->url_cover_image }}" class=" card-img-top" alt="{{ $post->title }}">
                @endif
            <p class="card-text">{{ $post->desc }}</p>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title">Comments</h3>
            @foreach($comments as $comment)
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->komentar }}</p>
                        <small class="text-muted">Posted on {{ $comment->created_at->format('d M Y, H:i') }}</small>
                        {{-- add delete and edit button --}}
                        <div class="d-flex justify-content-end mt-2">
                            <form action="{{ route('socialmedia.moderation.comments.hapus',  [$post->id , $comment->id]) }}" method="POST" class="mr-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            @if(auth()->id() === $comment->id_user)
                                <a href="{{ route('socialmedia.moderation.comments.edit', [$post->id , $comment->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                            @endif
                    </div>
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
