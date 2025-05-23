<div class="row">
    <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 mt-3">
        <div class="card box-shadow-0 ">
            <div class="card-header">
                <h4 class="card-sort mb-1">Add Data</h4>
            </div>
            <div class="card-body pt-0">
                <form action="{{ route('event.tambah.winner-category') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <input type="hidden" name="t_event_id" id="t_event_id" value="{{ $event_id }}">
                        <div class="form-group">
                            <label for="t_winner_category_id">Winner Category</label>
                            @error('t_winner_category_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_winner_category_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Category</option>
                                @foreach ($masterWinerCategory as $mwc)
                                    <option value="{{ $mwc->id }}"
                                        @if (old('t_winner_category_id', isset($winnerCategory) ? $winnerCategory->t_winner_category_id : '') == $mwc->id)
                                            selected
                                        @endif
                                    >
                                        {{ $mwc->code }} - {{ $mwc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sort">Sort</label>
                            @error('sort')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="number" class="form-control @error('sort') is-invalid @enderror"  value="{{ old('sort', isset($winnerCategory) ? $winnerCategory->sort : '') }}" name="sort" id="sort" placeholder="Sort" required autofocus>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 mt-3">
        <div class="card box-shadow-0 ">
            <div class="card-header">
                <h4 class="card-sort mb-1">Show Data</h4>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Winner Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataWinnerCategory as $key => $value)
                                <tr>
                                    <td>{{ $value->sort }}</td>
                                    <td>{{ !empty($value->masterWinnerCategory) ?  $value->masterWinnerCategory->code ." - ". $value->masterWinnerCategory->name : "-"}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>