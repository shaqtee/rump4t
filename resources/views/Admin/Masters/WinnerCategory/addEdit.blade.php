<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($winnerCategory))
            <form action="{{ route('winner-category.update', ['winner_category' => $winnerCategory->id]) }}" method="POST">
            @method('PATCH')
            @else
            <form action="{{ route('winner-category.store') }}" method="POST">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="code">code</label>
                            @error('code')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('code') is-invalid @enderror"  value="{{ old('code', isset($winnerCategory) ? $winnerCategory->code : '') }}" name="code" id="code" placeholder="Code" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($winnerCategory) ? $winnerCategory->name : '') }}" name="name" id="name" placeholder="Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            @error('description')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('description') is-invalid @enderror"  value="{{ old('description', isset($winnerCategory) ? $winnerCategory->description : '') }}" name="description" id="description" placeholder="Description" autofocus>
                        </div>
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>