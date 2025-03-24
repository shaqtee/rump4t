<div class="mt-3">
    <div class="card box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($score))
                <form action="{{ route('event.ubah', ['id' => $letsPlay->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('event.inputScore') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        <div class="form-group">
                            <input type="hidden" class="form-control @error('t_lets_play_id') is-invalid @enderror"  value="{{ old('t_lets_play_id', isset($letsPlay) ? $letsPlay->id : '') }}" name="t_lets_play_id" id="t_lets_play_id" placeholder="t_lets_play_id" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="t_user_id">Name</label>
                            @error('t_user_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_user_id" id="t_user_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Member</option>
                                @foreach ($letsPlay->memberLetsPlay as $member)
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
                            <input type="number" class="form-control @error('gross_score') is-invalid @enderror"  value="{{ old('gross_score', isset($letsPlay) ? $letsPlay->gross_score : '') }}" name="gross_score" id="gross_score" placeholder="Gross Score" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Image Score</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image Score" @if(!$score) required autofocus @endif onchange="previewImage()">
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