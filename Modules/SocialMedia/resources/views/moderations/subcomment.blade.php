@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <h1>Balasan</h1>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><strong>{{ $comment->user->name }}</strong></h5>
                    <p class="card-text">{{ $comment->komentar }}</p>
                    <p class="text-muted small mb-0">Posted on {{ $comment->created_at->format('M d, Y H:i') }}</p>
                    <div class="d-flex">
                        <a href="{{ route('socialmedia.moderation.subcomments.reply', ["id" => $post->id , 'comment_id' => $comment->id]) }}" class="btn btn-primary btn-sm m-2">Reply</a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm m-2">Back</a>
                    </div>
                    @if(count($subcomments) !== 0)

                    @foreach($subcomments as $subcomment)
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><strong>{{ $subcomment->user->name }}</strong></h5>
                                <p class="card-text">{{ $subcomment->komentar }}</p>
                                <p class="text-muted small mb-0">Posted on {{ $subcomment->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                <h2 class="my-2">Tidak ada balasan. mau membalas?</h2>

                @endif
                </div>
            </div>
        </div>

    </div>
</div>

@include('Admin.Layouts.footer')