<div class="row mt-3">
    <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 mt-3">
        <div class="card  box-shadow-0 ">
            <div class="card-header">
                <h4 class="card-title mb-1">{{ $title }}</h4>
            </div>
            <div class="card-body pt-0">
                @if (isset($election))
                <form action="{{ route('elections.update', ['election' => $election->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
                @else
                <form action="{{ route('elections.store') }}" method="POST" enctype="multipart/form-data">
                @endif
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="title">Title</label>
                                @error('title')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('title') is-invalid @enderror"  value="{{ old('title', isset($election) ? $election->title : '') }}" name="title" id="title" placeholder="Title" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                @error('start_date')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('start_date') is-invalid @enderror" id="datetimepicker-start-date" value="{{ old('start_date', isset($election) ? $election->start_date : '') }}" name="start_date" id="start_date" placeholder="Start Date" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                @error('end_date')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <input type="text" class="form-control @error('end_date') is-invalid @enderror" id="datetimepicker-end-date" value="{{ old('end_date', isset($election) ? $election->end_date : '') }}" name="end_date" id="end_date" placeholder="End Date" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="t_person_responsible_id">Person Responsible</label>
                                @error('t_person_responsible_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <select class="form-control select2" multiple="multiple" name="t_person_responsible_id[]">
                                    @foreach ($users as $responsible)
                                        <option value="{{ $responsible->id }}">
                                            {{ $responsible->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="t_candidates_id">Candidates</label>
                                @error('t_candidates_id')
                                    <small style="color: red">{{ $message }}</small>
                                @enderror
                                <select class="form-control select2" multiple="multiple" name="t_candidates_id[]">
                                    @foreach ($users as $candidate)
                                        <option value="{{ $candidate->id }}">
                                            {{ $candidate->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-6 col-md-6 col-sm-12 mt-3">
        <div class="card  box-shadow-0 ">
            <div class="card-header">
                <h4 class="card-title mb-1">Person Responsible</h4>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-bordered mg-b-0 text-md-nowrap text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if (isset($election) && isset($election->personResponsible))
                            <tbody>
                                @foreach ($election->personResponsible as $key => $person)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $person->user->name }}</td>
                                        <td>
                                            <form action="{{ route('socialmedia.elections.destroyPersonResponsible', ['id' => $person->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">DELETE</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@if (isset($election) && isset($election->candidates))
    <div class="row">
        @foreach ($election->candidates as $key => $candidate)
            <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12">
                <div class="card  box-shadow-0">
                    <div class="card-header">
                        <form action="{{ route('socialmedia.elections.destroyCandidate', ['id' => $candidate->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE')
                            <h4 class="card-title mb-1">Candidates {{ $key + 1 }} <button type="submit" class="badge badge-danger">Delete</button></h4>

                        </form>
                    </div>
                    <div class="card-body pt-0">
                        <form action="{{ route('socialmedia.elections.updateCandidate', ['id' => $candidate->id]) }}" method="POST" enctype="multipart/form-data">
                            @method('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        @error('name')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{ old('name', isset($candidate) ? $candidate->user->name : '') }}" name="name" id="name" placeholder="Name" disabled required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        @error('link')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                        <input type="text" class="form-control @error('link') is-invalid @enderror"  value="{{ old('link', isset($candidate) ? $candidate->link : '') }}" name="link" id="link" placeholder="Link" autofocus>
                                        @if (isset($candidate->link))
                                            <div class="embed-responsive embed-responsive-16by9 mt-2">
                                                <iframe class="embed-responsive-item" src="{{ isset($candidate) ? $candidate->link : '' }}" allowfullscreen></iframe>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        @error('image')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                        <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                                        <input type="file" class="form-control @error('image') is-invalid @enderror" value="{{ old('image', isset($candidate) ? $candidate->image : '') }}" name="image" id="image" placeholder="Image" @if(!$candidate) autofocus @endif onchange="previewImage()">
                                    </div>
                                    @if (isset($candidate) && isset($candidate->image))
                                        <div class="form-group">
                                            <img class="img-thumbnail mb-3" src="{{ isset($candidate) ? $candidate->image : '' }}" alt="" width="300" height="200">
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        @error('description')
                                            <small style="color: red">{{ $message }}</small>
                                        @enderror
                                        <textarea class="form-control" name="description" rows="5" autofocus>{{ isset($candidate) ? $candidate->description : old('description') }}</textarea>
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="voters_count">Voters Count</label>
                                        <input type="text" class="form-control" value="{{ old('voters_count', isset($candidate) ? $candidate->voters_count : '') }}" placeholder="Link" disabled>
                                    </div> --}}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3 mb-0">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
