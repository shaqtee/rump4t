@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>Post Preview</h5>
        </div>
        <div class="card-body">
            @if ($post->url_cover_image !== null)
                <img src="{{ $post->url_cover_image }}" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" class="card-img-top" alt="Post Image"
                    style="object-fit: cover; height: 200px; width: 100%;">

            @endif
            <h6 class="card-title">{{$post->title}}</h6>
            <p class="card-text">
                {{ $post->desc }}
            </p>


            <h3>Kolom Moderasi</h3>
            <form action="{{ route('socialmedia.moderation.moderate', $post->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="comments">Comments</label>
                    <textarea class="form-control" id="comments" name="comments"
                        rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
                <a href="{{ !empty($post->t_small_groups_id) ? route('groups.posting.posts', ['groups_id' => $post->t_small_groups_id]) : route('socialmedia.moderation.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>



        </div>
    </div>
</div>

@include('Admin.Layouts.footer')