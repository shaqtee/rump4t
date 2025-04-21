@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')
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
                <h3>News List</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('news-admin.tambah') }}" class="btn btn-success mb-3">Add News</a>
                <table id="newsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Region</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($news as $item)
                            <tr>
                                <td>{{ $item->short_id }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->author_name }} <img style=" margin 20px ; border-radius: 25%; width: 50px ; height: 50px;" src="{{ $item->author_image }}" alt=""></td>
                                @if($item->region == null)
                                    <td>Global</td>
                                @else
                                <td>{{ $item->region->value }}</td>
                                @endif
                                <td>{{ $item->created_at }}</td>
                                <td>
                                    <a href="{{ route('news-admin.ubah', $item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('news-admin.hapus', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

{{-- // scripts section  --}}

@push('scripts')
<script>
    $(document).ready(function() {
        $('#newsTable').DataTable();
    });
</script>
@endpush

@include('Admin.Layouts.footer')