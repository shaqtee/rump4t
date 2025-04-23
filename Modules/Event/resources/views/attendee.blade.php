@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Attendee List</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Voucher</th>
                        <th>Payment Date</th>
                        <th>Nominal Payment</th>
                        <th>Shirt Size</th>
                        <th>Companion Shirt Size</th>
                        <th>Proof of Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendees as $attendee)
                        <tr>
                            <td>{{ $attendee['data_input']['nama'] ?? $attendee['user']["name"] ?? 'N/A' }}</td>
                            <td>{{ $attendee['data_input']['no_hp'] ?? $attendee["user"]['phone'] ?? 'N/A' }}</td>
                            <td>{{ $attendee['voucher'] ?? 'N/A' }}</td>
                            <td>{{ $attendee['payment_date'] ?? 'N/A' }}</td>
                            <td>{{ $attendee['nominal_pembayaran'] ?? 'N/A' }}</td>
                            <td>
                                @if (!empty($attendee['data_input']['ukuran_kaos']))
                                    {{ $attendee['data_input']['ukuran_kaos'] }} ({{ $attendee['data_input']['type_lengan'] ?? 'N/A' }})
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if (!empty($attendee['data_input']['ukuran_kaos_pendamping']))
                                    {{ $attendee['data_input']['ukuran_kaos_pendamping'] }} ({{ $attendee['data_input']['type_lengan_pendamping'] ?? 'N/A' }})
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if (!empty($attendee['image_bukti']))
                                    <img src="{{ $attendee['image_bukti'] }}" alt="Proof of Payment" style="width: 100px; height: auto;">
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')