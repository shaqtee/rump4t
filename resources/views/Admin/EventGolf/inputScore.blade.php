<div class="mt-3">
    <div class="card box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($score))
                <form action="{{ route('event.ubah', ['id' => $event->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('event.inputScore') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <input type="hidden" class="form-control @error('t_event_id') is-invalid @enderror"  value="{{ old('t_event_id', isset($event) ? $event->id : '') }}" name="t_event_id" id="t_event_id" placeholder="t_event_id" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="t_user_id">Name</label>
                            @error('t_user_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_user_id" id="t_user_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Member</option>
                                @foreach ($event->membersEvent as $member)
                                    <option value="{{ $member->id }}" 
                                        @if (old('t_user_id', isset($score) ? $score->t_user_id : '') == $member->id)
                                            selected
                                        @endif
                                    >
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="gross_score">Gross Score</label>
                            @error('gross_score')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="number" class="form-control @error('gross_score') is-invalid @enderror"  value="{{ old('gross_score', isset($event) ? $event->gross_score : '') }}" name="gross_score" id="gross_score" placeholder="Gross Score" required autofocus>
                        </div>
                        @for ($i = 1; $i <= 18; $i++)
                            <div class="mt-5">
                                <h4 class="card-title">Input Hole {{ $i }}</h4>
                                <div class="card box-shadow-0 ">
                                    <input type="hidden" id="hole{{ $i }}_id" name="holes[{{ $i }}][hole_id]" value="{{ old('holes.' . $i . '.hole_id', isset($score) ? $score->holes[$i-1]->hole_id : '') }}">
                                    <div class="form-group mx-3 mt-3">
                                        <label for="hole{{ $i }}_stroke">Total Stroke</label>
                                        <input type="text" id="hole{{ $i }}_stroke" name="holes[{{ $i }}][stroke]" class="form-control">
                                    </div>
                                    <div class="form-group mx-3 mt-2">
                                        <label for="hole{{ $i }}_putts">Putts</label>
                                        <input type="text" id="hole{{ $i }}_putts" name="holes[{{ $i }}][putts]" class="form-control">
                                    </div>
                                    <div class="form-group mx-3 mt-2">
                                        <label for="hole{{ $i }}_sand_shots">Sand Shots</label>
                                        <input type="text" id="hole{{ $i }}_sand_shots" name="holes[{{ $i }}][sand_shots]" class="form-control">
                                    </div>
                                    <div class="form-group mx-3 mt-2">
                                        <label for="hole{{ $i }}_penalties">Penalties</label>
                                        <input type="text" id="hole{{ $i }}_penalties" name="holes[{{ $i }}][penalties]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        @endfor
                        <div class="form-group">
                            <label for="image">Image Score</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image Score" @if(!$score) autofocus @endif onchange="previewImage()">
                            @if (isset($score))
                                <div class="mt-2">
                                    <label for="">Your Image Score</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($score) ? $score->image_score : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
                    </div>
                </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#t_user_id').change(function () {
            let userId = $(this).val(); 
            let eventId = $('#t_event_id').val(); 

            if (userId) {
                $.ajax({
                    url: "{{ route('event.getUserScore') }}",
                    method: 'GET',
                    data: { user_id: userId, event_id: eventId },
                    success: function (response) {
                        
                        for (let i = 1; i <= 18; i++) {
                            $(`#hole${i}_stroke`).val(response[`hole${i}_stroke`] || '');
                            $(`#hole${i}_putts`).val(response[`hole${i}_putts`] || '');
                            $(`#hole${i}_sand_shots`).val(response[`hole${i}_sand_shots`] || '');
                            $(`#hole${i}_penalties`).val(response[`hole${i}_penalties`] || '');

                            $(`#hole${i}_id`).val(response[`hole${i}_id`] || '');
                        }

                        $('#gross_score').val(response.grossScore || '');

                        if (response.imageScore) {
                            $('.image-preview').attr('src', response.imageScore).show();
                        } else {
                            $('.image-preview').hide(); 
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching user scores:', error);
                    }
                });
            }
        });
    });

</script>