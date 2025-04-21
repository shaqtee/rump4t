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
                    <label for="type">Region Type</label>
                    <select name="parameter" id="type" class="form-control" required>
                        <option value="m_area" {{ old('parameter', $region->parameter) == 'm_area' ? 'selected' : '' }}>Area</option>
                        <option value="m_region" {{ old('parameter', $region->parameter) == 'm_region' ? 'selected' : '' }}>Region</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Region Name</label>
                    <input type="text" name="value" id="name" class="form-control" value="{{ old('value', $region->value) }}" required>
                </div>
            
                <button type="submit" class="btn btn-primary mt-3">Update</button>
            </form>
        </div>
    </div>

@include('Admin.Layouts.footer')