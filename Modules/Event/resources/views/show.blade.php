@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')


<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Event Details</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('events.admin.index') }}" class="btn btn-primary mb-3">Kembali</a>
            <p><strong>Nama Event:</strong> {{ $event->title }}</p>
            <p><strong>Tanggal Event:</strong> {{ \Carbon\Carbon::parse($event->play_date_start)->translatedFormat('d F Y') }}</p>
            {{-- tanggal berakhir  --}}
            <p><strong>Tanggal Penutupan Pendaftaran:</strong> {{ \Carbon\Carbon::parse($event->close_registration)->translatedFormat('d F Y') }}</p>
            {{-- region --}}
            @if($event->region !== null)
            <p><strong>Region:</strong> {{ $event->region_data->value }}</p>
            @endif 
            {{-- location --}}
            <p><strong>Lokasi:</strong> {{ $event->location }}</p>
            <p><strong>Deskripsi:</strong> {{ $event->description }}</p>
            <!-- // biaya -->
            <p><strong>Biaya:</strong> {{ 'Rp ' . number_format($event->price, 0, ',', '.') }}</p>
            <img src="{{ $event->image }}" alt="">
        </div>
    </div>
</di>


@include("Admin.Layouts.footer")