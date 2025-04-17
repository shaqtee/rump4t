@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')


<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>Membalas Komentar</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <h6>Komentar:</h6>
                <p><strong>{{ $comment->user->name }}</strong></p>
                <p>Pada {{ $comment->created_at->diffForHumans() }} </p>
                <p>{{ $comment->komentar }}</p>
            </div>
            <form action="{{ route('socialmedia.moderation.subcomments.reply' , ["id" => $post->id , "comment_id" => $comment->id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="comment">Komentar</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" placeholder="Tulis balasan Anda di sini..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Kirim</button>
            </form>
        </div>
    </div>
</div>


@include('Admin.Layouts.footer')