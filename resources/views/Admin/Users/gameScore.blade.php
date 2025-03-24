<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('users.tambah') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        @error('name')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                        <input readonly type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', isset($users) ? $users->name : '') }}" name="name" id="name"
                            placeholder="Name" required autofocus>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mg-b-0 text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Lets Play</th>
                                    <th>Golf Course</th>
                                    <th>Tee Box</th>
                                    <th>Gross Score</th>
                                    <th>Score Image</th>
                                    <th>Round Type</th>
                                    <th>Course Rating</th>
                                    <th>Slope Rating</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->MyScore as $ms)
                                    <tr>
                                        <td>{{ $ms->event->title ?? '-' }}</td>
                                        <td>{{ $ms->letsPlay->title ?? '-' }}</td>
                                        <td>{{ $ms->t_course_name }}</td>
                                        <td>{{ $ms->t_tee_name }}</td>
                                        <td>{{ $ms->gross_score }}</td>
                                        <td><img class="img-thumbnail" src="{{ $ms->image_score }}" style="width: 100px; height: 100px; object-fit: fill;" alt="Bukti Score"></td>
                                        <td>{{ $ms->m_round_type_name }}</td>
                                        <td>{{ $ms->course_rating }}</td>
                                        <td>{{ $ms->slope_rating }}</td>
                                        <td>{{ $ms->date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
