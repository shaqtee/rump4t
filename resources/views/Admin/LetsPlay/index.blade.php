<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            {{-- <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a href="{{ route('community.posting.tambah') }}" class="btn btn-success "><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div> --}}
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
                    <form action="{{ route('letsplay.semua') }}" method="GET" class="d-flex">
                            <select id="searchIndex" class="form-control">
                                @foreach ($columns as $items => $values)
                                    @foreach ($values as $item => $value)
                                        <option value="{{ $item }}" data-placeholder="{{ $value['Label'] }}"> {{ $value['Label'] }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <input class="form-control" type="text" id="dynamicInput" name="" placeholder="">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Organized</th>
                            <th>Golf Course</th>
                            <th>Play Date</th>
                            <th>Type Score</th>
                            <th>Max Flight</th>
                            <th>Tee Box</th>
                            <th>Round Type</th>
                            <th>Private</th>
                            <th>Active</th>
                            <th colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($letsPlay as $key => $lp)
                            <tr>
                                <th scope="row">{{ $letsPlay->firstItem() + $key }}</th>
                                <td>{{ $lp->title ?? '-' }}</td>
                                <td><img class="img-thumbnail" style="width: 100px; height: 100px; object-fit: fill;" src="{{ $lp->image }}" alt=""></td>
                                <td>{{ $lp->organized?->name ?? '-'}}</td>
                                <td>{{ $lp->golfCourse->name ?? '-'}}</td>
                                <td>{{ $lp->play_date ?? '-'}}</td>
                                <td>{{ $lp->type_scor ?? '-'}}</td>
                                <td>Max {{ $lp->max_flight ?? '-'}} Flight</td>
                                <td>{{ $lp->teeBox->tee_type ?? '-'}}</td>
                                <td>{{ $lp->roundType->value1 ?? '-'}}</td>
                                <td>{{ ($lp->is_private == '1') ? 'Private' : 'Public' }}</td>
                                <td>{{ ($lp->active == '1') ? 'Active' : 'Deactivate' }}</td>
                                <td>
                                    <a class="btn btn-info " href="{{ route('letsplay.member', ['id' => $lp->id]) }}">Players</a>
                                </td>
                                <td>
                                    <a class="btn btn-info " href="{{ route('letsplay.viewInputScore', ['id' => $lp->id]) }}">Input Score</a>
                                </td>
                                <td>
                                    <form action="{{ route('letsplay.edit', ['id' => $lp->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if ($lp->active == '1')
                                            <input type="hidden" name="active" value="0">
                                            <button type="submit" class="btn btn-danger ">Deactive</button>
                                        @else
                                            <input type="hidden" name="active" value="1">
                                            <button type="submit" class="btn btn-primary ">Active</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($letsPlay->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $letsPlay->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($letsPlay->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $letsPlay->url(1) }}">1</a>
                            </li>
                            @if ($letsPlay->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $letsPlay->lastPage()) as $i)
                            @if ($i >= $letsPlay->currentPage() - 2 && $i <= $letsPlay->currentPage() + 2)
                                @if ($i == $letsPlay->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $letsPlay->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($letsPlay->currentPage() < $letsPlay->lastPage() - 2)
                            @if ($letsPlay->currentPage() < $letsPlay->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $letsPlay->url($letsPlay->lastPage()) }}">{{ $letsPlay->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($letsPlay->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $letsPlay->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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