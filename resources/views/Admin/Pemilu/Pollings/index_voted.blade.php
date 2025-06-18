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
                    {{-- <label for="perPage">Show</label>
                    <select id="perPage" class="form-control" style="width: auto;" onchange="changePage(this.value)">
                        <option value="10" {{ request('size') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('size') == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ request('size') == 20 ? 'selected' : '' }}>20</option>
                    </select> --}}
                </div>
                <div class="col-auto">
                    {{-- <form action="#" method="GET" class="d-flex">
                            <select id="searchIndex" class="form-control" style="margin-right: 10px;">
                                @foreach ($columns as $items => $values)
                                    @foreach ($values as $item => $value)
                                        <option value="{{ $item }}" data-placeholder="{{ $value['Label'] }}"> {{ $value['Label'] }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <input class="form-control" type="text" id="dynamicInput" name="search" placeholder="">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form> --}}
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
                            <th>User</th>
                            <th>Chosen</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pollings[0]->polling_users as $key => $p)
                            <tr>
                                <th scope="row">{{ $pollings->firstItem() + $key }}</th>
                                <th>{{ $p->name }}</th>
                                <th>
                                    @foreach ( $pollings[0]->candidate_users as $cu )
                                        @if($cu->id == $p->pivot->t_pemilu_candidates_id)
                                            {{ $cu->name }}
                                        @endif
                                    @endforeach
                                </th>
                                <th class="text-success">Legitimate</th>
                                <th>
                                    <form action="{{ route('pemilu.pollings.cancel_voted', $p->pivot->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger ">Cancel</button>
                                    </form>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        {{-- @if ($pollings->onFirstPage())
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
                        @endif --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>