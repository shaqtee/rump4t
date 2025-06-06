<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-center">Postingan&nbsp;<span class="text-primary">{{ $group->title }}</span></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('groups.posting.create', ['groups_id' => $groups_id]) }}" class="btn btn-success">Buat Postingan Baru</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-4 mb-4" style="height: 100%; {{ $post->deleted_at !== null ? 'opacity: .4;' : '' }}">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                       @if($post->user !== null)
                        <h6 class="card-subtitle mb-2 text-muted">Dibuat oleh : {{ $post->user->name }}</h6>
                        @endif
                            <h6 class="card-subtitle mb-2 text-muted">Dibuat pada : {{ Carbon\Carbon::parse ($post->created_at) }}</h6>
                       
                        @if($post->url_cover_image !== null)
                            <img src="{{ $post->url_cover_image }}" class="card-img-top" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" alt="Post Image" style="object-fit: cover; height: 200px; width: 100%;">
                            @endif
                        <p class="card-text">{{ Str::limit($post->desc, 400) }}</p>
                        @if($post->moderation !== null)
                            <p class="card-text text-danger">Dihapus karena: {{ $post->moderation->reason }}</p>
                        @endif
                        <hr>
                        @if($post->user === null || $post->user->id !== auth()->id())
                            <a href="{{ route('socialmedia.moderation.moderate', $post->id) }}" class="btn btn-primary btn-sm">Moderate</a>
                        @endif
                        @if($post->user === null || $post->user->id === auth()->id())
                            <a href="{{ route('socialmedia.moderation.ubah', $post->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        @endif
                        <a href="{{ route('socialmedia.moderation.comments', $post->id) }}" class="btn btn-info btn-sm">Comments</a>
                        @if($post->user === null || $post->user->id === auth()->id())
                            <form action="{{ route('socialmedia.moderation.hapus', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center mt-4">
                    {!! $posts->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
</div>