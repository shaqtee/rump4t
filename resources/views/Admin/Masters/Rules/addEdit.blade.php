<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($rulesScore))
            <form action="{{ route('rules-score.update', ['rules_score' => $rulesScore->id]) }}" method="POST">
            @method('PATCH')
            @else
            <form action="{{ route('rules-score.store') }}" method="POST">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <label for="description">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($rulesScore) ? $rulesScore->name : '') }}" name="name" id="name" placeholder="Name" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="holes">Holes</label>
                            @error('holes')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                                <label class="form-check-label" style="font-weight: bold;" for="selectAllHoles">Select All</label>
                            </div>
                            <div id="holes">
                                @for ($i = 1; $i <= 18; $i++)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input hole-checkbox @error('holes') is-invalid @enderror" name="holes[]" id="hole{{ $i }}" value="{{ $i }}"
                                            {{ in_array($i, old('holes', isset($rulesScore) ? $rulesScore->holes ?? [] : [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="hole{{ $i }}">Hole {{ $i }}</label>
                                    </div>
                                @endfor
                            </div>
                        </div>                        
                    </div>
                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#selectAll').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('.hole-checkbox').prop('checked', isChecked);
        });

        $('.hole-checkbox').on('change', function () {
            const totalCheckboxes = $('.hole-checkbox').length;
            const checkedCheckboxes = $('.hole-checkbox:checked').length;

            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        });
    });
</script>
