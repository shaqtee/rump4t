@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container">
    <h2>Create Event</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('event.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Event : </label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal Event:</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="location">Lokasi:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskkripsi:</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
                <!-- // biaya -->
                <div class="form-group">
                    <label for="price">Harga:</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                </div>
                <!-- // add image form -->
                <div class="form-group">
                    <label for="image">Banner:</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')