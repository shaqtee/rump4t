<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('donasi_admin.create') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
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
                    <form action="{{ route('donasi.admin') }}" method="GET" class="d-flex">
                            {{-- <select id="searchIndex" class="form-control" style="margin-right: 10px;">
                                @foreach ($columns as $items => $values)
                                    @foreach ($values as $item => $value)
                                        <option value="{{ $item }}" data-placeholder="{{ $value['Label'] }}"> {{ $value['Label'] }}</option>
                                    @endforeach
                                @endforeach
                            </select> --}}
                            <input class="form-control" type="text" id="dynamicInput" name="search" placeholder="">
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
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Description</th>
                            <th>Target Sumbangan</th>
                            <th>Total Sumbangan</th>
                            <th>Total Donatur</th>
                            <th>Created At</th>
                            <th colspan="4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            
                        @endphp
                        @foreach ($donation as $key => $d)
                            <tr>
                                <th scope="row">{{ $donation->firstItem() + $key }}</th>
                                <td>{{ $d->title ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($d->start_date)->translatedFormat('d F Y') ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($d->end_date)->translatedFormat('d F Y') ?? '-' }}</td>
                                <td>{{ $d->description ?? '-'}}</td>
                                <td>Rp. {{ number_format($d->target_sumbangan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($d->donatur_sum_nominal, 0, ',', '.') }}</td>
                                <td>{{ $d->total_donatur }} Donatur</td>
                                <td>{{ $d->created_at  ?? '-'}}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('donasi_admin.edit_form', $d->id) }}">EDIT</a>
                                </td>
                                <td>
                                    <a class="btn btn-warning" href="{{ route('donasi_image.add', $d->id) }}">ADD IMAGE</a>
                                </td>
                                <td>
                                    <a class="btn btn-secondary" href="{{ route('donatur.detail', $d->id) }}">DONATUR</a>
                                </td>
                                <td>
                                    <form action="{{ route('donasi_admin.destroy', $d->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">DELETE</button>
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
                        @if ($donation->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $winnerCategory->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($donation->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $winnerCategory->url(1) }}">1</a>
                            </li>
                            @if ($donation->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $donation->lastPage()) as $i)
                            @if ($i >= $donation->currentPage() - 2 && $i <= $donation->currentPage() + 2)
                                @if ($i == $donation->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $donation->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($donation->currentPage() < $donation->lastPage() - 2)
                            @if ($donation->currentPage() < $donation->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $donation->url($donation->lastPage()) }}">{{ $donation->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($donation->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $donation->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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