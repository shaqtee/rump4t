@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4"></div>
    <div class="card">
        <div class="card-header">
            <h3>Regions</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('regions.tambah') }}" class="btn btn-primary mb-3">Tambah Region</a>
        </div>
        <div class="card-body">
            <table id="regionsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Region</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $region)
                        <tr>
                            <td>{{ $region->id }}</td>
                            <td>{{ $region->name }}</td>
                            <td>
                                <a href="{{ route('regions.ubah', $region->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('regions.hapus', $region->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin?' , 'yakin, dong!')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $regions->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@include('Admin.Layouts.footer')
