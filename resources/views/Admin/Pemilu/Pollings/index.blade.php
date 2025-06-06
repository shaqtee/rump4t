<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3 justify-content-between align-items-center">
                <div class="col-auto">
                    <label for="perPage">Show</label>
                    <select id="perPage" class="form-control" style="width: auto;" onchange="changePage(this.value)">
                        <option value="10" {{ request('size') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('size') == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ request('size') == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </div>
                <div class="col-auto">
                    <form action="#" method="GET" class="d-flex">
                            <select id="searchIndex" class="form-control" style="margin-right: 10px;">
                                @foreach ($columns as $items => $values)
                                    @foreach ($values as $item => $value)
                                        <option value="{{ $item }}" data-placeholder="{{ $value['Label'] }}"> {{ $value['Label'] }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <input class="form-control" type="text" id="dynamicInput" name="search" placeholder="">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                @php
                    $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary'];
                @endphp
                <table class="table table-bordered table-hover text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Candidates</th>
                            <th>Quick Count</th>
                            <th>Total Votes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pollings as $key => $polling)
                            @php
                                $today = date('Y-m-d H:i:s');
                                $expired = strtotime($polling->end_date) < strtotime($today) ? true : false;

                                $totalVotes = $polling->polling_users->count();
                                $voteCounts = [];
                                $pollingId = $polling->id;

                                $options = [
                                    'pemilu' => $pollingId,
                                    'candidates' => [],
                                    'voted_users' => [],
                                ];

                                foreach ($polling->candidate_users as $candidate) {
                                    $candidateId = $candidate->pivot->id;
                                    $voteCounts[$candidateId] = $polling->polling_users
                                        ->where('pivot.t_pemilu_candidates_id', $candidateId)
                                        ->count();

                                    if ($candidate->pivot->is_active) {
                                        $options['candidates'][$pollingId][] = [
                                            't_pemilu_candidates_id' => $candidateId,
                                            'name' => $candidate->name,
                                        ];
                                    }
                                }

                                $options['voted_users'][$pollingId] = $polling->polling_users
                                    ->pluck('id')
                                    ->filter()
                                    ->toArray();
                            @endphp

                            <tr>
                                <th scope="row">{{ $pollings->firstItem() + $key }}</th>

                                <td>
                                    <a href="{{ url('admin/pemilu?title=' . $polling->title) }}" target="_blank">
                                        {{ $polling->title ?? '-' }}
                                    </a>
                                </td>

                                <td class="text-start">
                                    @foreach ($polling->candidate_users as $candidate)
                                        @if ($candidate->pivot->is_active)
                                            <div>
                                                <a href="{{ route('users.lihat', $candidate->id) }}" target="_blank">
                                                    {{ $candidate->name }}
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>

                                <td>
                                    @foreach ($polling->candidate_users as $i => $candidate)
                                        @if ($candidate->pivot->is_active)
                                            @php
                                                $count = $voteCounts[$candidate->pivot->id] ?? 0;
                                                $percentage = $totalVotes > 0 ? round(($count / $totalVotes) * 100, 2) : 0;
                                                $color = $colors[$i % count($colors)];
                                            @endphp
                                            <div class="mb-2 text-start">
                                                <small>
                                                    {{ $candidate->name }} - {{ $count }} 
                                                    <span class="text-danger font-weight-bold">({{ $percentage }}%)</span>
                                                </small>
                                                <div class="progress">
                                                    <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </td>

                                <td>{{ $totalVotes }}</td>

                                <td>
                                    @if ($expired)
                                        <button class="btn btn-sm btn-secondary disabled">Expired</button>
                                    @else
                                        <button class="btn btn-sm btn-info"
                                            onclick="addVote(this)"
                                            data-title="{{ $polling->title }}"
                                            data-votes='@json($options)'
                                            data-effect="effect-scale"
                                            data-toggle="modal"
                                            data-target="#modalVotes">
                                            Votes
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- modal Admin --}}
                <div class="modal" id="modalVotes">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-content-demo">
                            <div class="modal-header">
                                <h6 class="modal-title">Add Vote <span id="title" class="text-danger"></span></h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body pt-0">
                                    <div class="w-100 d-flex justify-content-center flex-column align-items-center">
                                        <div class="loader-vote spinner-grow text-primary" role="status">
                                            <span class=" sr-only">Loading...</span>
                                        </div>
                                        <div class="loader-vote">Loading...</div>
                                    </div>
                                    <form action="{{ route('pemilu.pollings.vote') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="id">User</label>
                                            @error('id')
                                                <small style="color: red">{{ $message }}</small>
                                            @enderror
                                            <select name="user_id" id="user_id" class="form-control select2" style="width: 100%" required autofocus>
                                                <option label="Choose one"></option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="candidate">Candidate : <span id="disp_candidate" class="text-danger"></span></label>
                                            <div id="candidate"></div>
                                        </div>

                                        <input type="hidden" name="t_pemilu_candidates_id" />
                                        <input type="hidden" name="t_pemilu_id" />

                                        <div class="modal-footer">
                                            <button class="btn ripple btn-success" type="submit">Save</button>
                                            <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end modal Admin --}}
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($pollings->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $pollings->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($pollings->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $pollings->url(1) }}">1</a>
                            </li>
                            @if ($pollings->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $pollings->lastPage()) as $i)
                            @if ($i >= $pollings->currentPage() - 2 && $i <= $pollings->currentPage() + 2)
                                @if ($i == $pollings->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $pollings->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($pollings->currentPage() < $pollings->lastPage() - 2)
                            @if ($pollings->currentPage() < $pollings->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $pollings->url($pollings->lastPage()) }}">{{ $pollings->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($pollings->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $pollings->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-forward"></i></span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addVote(button) {
        const $button = $(button);
        const title = $button.data('title');
        const { pemilu, candidates, voted_users } = $button.data('votes');
        const pollingId = pemilu;

        // Isi hidden input
        $("input[name='t_pemilu_id']").val(pollingId);

        // Tampilkan loader
        $(".loader-vote").show();

        // Kosongkan Kandidat
        $("#disp_candidate").empty()

        // Kirim data ke server
        $.post(
            "{{ route('pemilu.pollings.ajax_user_vote') }}",
            {
                _token: "{{ csrf_token() }}",
                voted_users: voted_users[pollingId] || []
            }
        )
        .done((data) => {
            console.log('AJAX Response:', data);

            // Update select user
            const userOptions = data.users.map(user =>
                `<option value="${user.id}">${user.name}</option>`
            ).join('');
            $('#user_id').html(userOptions);

            // Update kandidat
            const candidateButtons = (candidates[pollingId] || []).map(candidate =>
                `<div class="btn btn-sm btn-primary" id="${candidate.t_pemilu_candidates_id}" 
                    data-name="${candidate.name}" onclick="select_candidate(this)">
                    ${candidate.name}
                </div>`
            ).join('&nbsp;&nbsp;');

            $('#title').text(title);
            $('#candidate').html(candidateButtons);
        })
        .fail((xhr) => {
            console.error('AJAX Error:', xhr.responseText);
            alert('Gagal mengambil data vote. Silakan coba lagi.');
        })
        .always(() => {
            $(".loader-vote").hide();
        });
    }

    function select_candidate(button) {
        const $button = $(button);
        $("#disp_candidate").empty().text($button.data('name'));
        $("input[name='t_pemilu_candidates_id']").val(button.id);
    }
</script>