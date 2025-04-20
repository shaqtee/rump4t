@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4"></div>
    <div class="card">
        <div class="card-header">
            <h3>Events</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('events.tambah') }}" class="btn btn-primary mb-3">Tambah Acara</a>
        </div>
        <div class="card-body">
            <table id="eventsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Acara</th>
                        <th>Tangga Peaksanaan</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->play_date_start)->format('j F Y') }}</td>
                            <td>{{ $event->location }}</td>
                            <td>
                            
                                <a href="{{ route('events.ubah', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('events.hapus', $event->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin?' , 'yakin, dong!')">Delete</button>
                                </form>
                                <a href="{{ route('events.detail', $event->id) }}" class="btn btn-info btn-sm">Details</a>
                                <a href="{{ route('events.bukutamu', $event->id) }}" class="btn btn-primary btn-sm">Attendees</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $events->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#eventsTable').DataTable();
    });
</script>


@include("Admin.Layouts.footer")