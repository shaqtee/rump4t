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
                    <label for="region_type">Region Type</label>
                    <select name="parameter" class="form-control" required>
                        <option value="m_area">Area</option>
                        <option value="m_region">Region</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Region Name</label>
                    <input type="text" name="value" id="name" class="form-control" placeholder="Enter region or are name" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

@include('Admin.Layouts.footer')
