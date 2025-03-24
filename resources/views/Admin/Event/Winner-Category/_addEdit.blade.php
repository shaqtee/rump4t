<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($winner_category))
                <form action="{{ route('event.winners.ubah', ['id' => $winner_category->id]) }}" method="POST">
                @method('PATCH')
            @else
                <form action="{{ route('event.winners.tambah') }}" method="POST">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group" id="multiple-insert">
                            <label for="t_event_id">Event</label>
                            @error('t_event_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_event_id" id="t_event_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Event</option>
                                @foreach ($event as $evt)
                                    <option value="{{ $evt->id }}" 
                                        @if (old('t_event_id', isset($winner_category) ? $winner_category->t_event_id : '') == $evt->id)
                                            selected
                                        @endif
                                    >
                                        {{ $evt->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="code">Code</label>
                            @error('code')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('code') is-invalid @enderror"  value="{{ old('code', isset($winner_category) ? $winner_category->code : '') }}" name="code" id="code" placeholder="Code" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($winner_category) ? $winner_category->name : '') }}" name="name" id="name" placeholder="Name" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            @error('description')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('description') is-invalid @enderror"  value="{{ old('description', isset($winner_category) ? $winner_category->description : '') }}" name="description" id="description" placeholder="Description" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
                    </div>
                </form>
        </div>
    </div>
</div>