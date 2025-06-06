<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($pollings))
            <form action="{{ route('pemilu_admin.update', $pollings->id) }}" method="POST">
            @method('PATCH')
            @else
            <form action="{{ route('pemilu_admin.store') }}" method="POST">
            @endif
                @csrf
                {{-- TITLE --}}
                <div class="form-group">
                    <label for="title">Judul Pemilu</label>
                    @error('title')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $pollings->title ?? '') }}">
                </div>

                {{-- DESCRIPTION --}}
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    @error('description')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <textarea name="description" id="description"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $pollings->description ?? '') }}</textarea>
                </div>

                {{-- START DATE --}}
                 <div class="form-group">
                    <label for="start_date">Mulai</label>
                    @error('start_date')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    @php
                        $now = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    @endphp
                    <input type="datetime-local" name="start_date" id="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date', isset($pollings->start_date) ? \Carbon\Carbon::parse($pollings->start_date)->format('Y-m-d\TH:i') : '') }}">
                </div>

                {{-- END DATE --}}
                <div class="form-group">
                    <label for="end_date">Berakhir</label>
                    @error('end_date')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    @php
                        $now = \Carbon\Carbon::now()->format('Y-m-d\TH:i');
                    @endphp
                    <input type="datetime-local" name="end_date" id="end_date"
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date', isset($pollings->end_date) ? \Carbon\Carbon::parse($pollings->end_date)->format('Y-m-d\TH:i') : '') }}" min="{{ $now }}">
                </div>

                {{-- Activate --}}
                <div class="form-group">
                    <label for="is_active">Status</label>
                    @error('is_active')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="row">
                        <div class="col">
                            <label class="rdiobox"><input value="1" name="is_active" type="radio" {{ old('is_active', isset($pollings) && $pollings->is_active == '1' ? 'checked' : 'checked') }} required autofocus> <span>Aktifkan</span></label>
                            <label class="rdiobox"><input value="0" name="is_active" type="radio" {{ old('is_active', isset($pollings) && $pollings->is_active == '0' ? 'checked' : '') }} required autofocus> <span>Non-Aktifkan</span></label>
                        </div>
                    </div>
                </div>

                <br><br>

                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        
    });
</script>
