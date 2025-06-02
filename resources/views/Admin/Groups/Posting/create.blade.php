{{-- // content section  --}}
<style>
    .container {
        margin-top: 20px;
    }
    .table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    .table th {
        background-color: #f2f2f2;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }
    .table-striped tbody tr:nth-of-type(even) {
        background-color: #fff;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Create a New Post</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('groups.posting.store', ['groups_id' => $groups_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Post Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter the post title">
                </div>
                <div class="form-group">
                    <label for="content">Post Content</label>
                    <textarea name="content" id="content" class="form-control" rows="4" placeholder="Write your post content here..."></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Create Post</button>
            </form>
        </div>
    </div>
</div>