<div class="mt-3">
    <div class="card box-shadow-0">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <div class="form-group">
                <label for="title">Event</label>
                @error('title')
                    <small style="color: red">{{ $message }}</small>
                @enderror
                <input type="text" class="form-control @error('title') is-invalid @enderror"
                    value="{{ old('title', isset($eventWinner) ? $eventWinner->title : '') }}" name="title"
                    id="title" placeholder="title" readonly>
            </div>
            <div class="form-group">
                <label>User Winner Category</label>
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap">
                        <thead>
                            <tr>
                                <th>Winner Category</th>
                                <th>Sort</th>
                                <th>User Winner</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eventWinner->winnerCategory as $winnerCategory)
                                <tr>
                                    <td>{{ $winnerCategory->masterWinnerCategory->name ?? '???' }}</td>
                                    <form action="{{ route('event.winners.ubah', ['id' => $winnerCategory->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <td>
                                            <div class="form-group">
                                                @error('sort')
                                                    <small style="color: red">{{ $message }}</small>
                                                @enderror
                                                <input type="number" class="form-control @error('sort') is-invalid @enderror"  value="{{ old('sort', isset($winnerCategory) ? $winnerCategory->sort : '') }}" name="sort" id="sort" placeholder="Sort" required autofocus>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <select name="t_user_id" class="form-control select2" @if(isset($winnerCategory->name) && !empty($winnerCategory->name)) disabled @endif
                                                                id="t_user_id_{{ $winnerCategory->id }}">
                                                                <option selected value="">Select User Winner</option>
                                                                @foreach ($userEvent as $ue)
                                                                    <option value="{{ $ue->id }}"
                                                                        @if (old('t_user_id', isset($winnerCategory) ? $winnerCategory->usersWinner->id ?? null : '') == $ue->id) selected @endif>
                                                                        {{ $ue->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-3">
                                                            <input type="checkbox" data-checkboxes="mygroup" @if(isset($winnerCategory->name) && !empty($winnerCategory->name)) checked @endif
                                                                class="custom-control-input" name="checkbox" value="true"
                                                                id="checkbox-{{ $winnerCategory->id }}"
                                                                onclick="load_detail_question('{{ $winnerCategory->id }}')">
                                                            <label for="checkbox-{{ $winnerCategory->id }}"
                                                                class="custom-control-label mt-1">Other</label>
                                                        </div>
                                                    </div>
                                                    <div class="row cid_{{ $winnerCategory->id }} @if(!isset($winnerCategory->name) && empty($winnerCategory->name)) hide @endif"
                                                        @if(!isset($winnerCategory->name) && empty($winnerCategory->name)) style="display: none" @endif>
                                                        <div class="col-md-8 mt-2">
                                                            <input type="text"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                name="name" value="{{ old('name', isset($winnerCategory) ? $winnerCategory->name : '') }}"
                                                                placeholder="Enter User Winner" autofocus>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="submit" class="btn btn-success mt-2">Save</button>
                                                </div>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>