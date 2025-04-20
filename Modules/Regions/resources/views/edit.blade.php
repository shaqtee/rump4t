@include('Admin.Layouts.head')
@include('Admin.Layouts.sidebar')
@include('Admin.Layouts.header')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h1>Edit Region</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('regions.ubah', $region->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Region Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $region->name) }}" required>
                </div>
            
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>

@include('Admin.Layouts.footer')