@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container">
    <h1>Create Region</h1>
    <div class="card">
        <div class="card-header">
            <h5>Create Region</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('regions.tambah') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Region Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter region name" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')
