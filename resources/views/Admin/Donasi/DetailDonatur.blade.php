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
                            <th>Nominal</th>
                            <th>Bukti Sumbangan</th>
                            <th>Note</th>
                            <th>Donatur</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            
                        @endphp
                        @foreach ($donatur as $key => $d)
                            <tr>
                                <th scope="row">{{ $donatur->firstItem() + $key }}</th>
                                <td>Rp. {{ $d->nominal  ?? '-'}}</td>
                                <td><img class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;" src="{{ $d->bukti_donasi }}" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" alt=""></td>
                                <td>{{ $d->note ?? '-'}}</td>
                                <td>{{ $d->user->name  ?? '-'}}</td>
                                <td>{{ \Carbon\Carbon::parse($d->created_at)->translatedFormat('d F Y') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($donatur->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $winnerCategory->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($donatur->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $winnerCategory->url(1) }}">1</a>
                            </li>
                            @if ($donatur->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $donatur->lastPage()) as $i)
                            @if ($i >= $donatur->currentPage() - 2 && $i <= $donatur->currentPage() + 2)
                                @if ($i == $donatur->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $donatur->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($donatur->currentPage() < $donatur->lastPage() - 2)
                            @if ($donatur->currentPage() < $donatur->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $donatur->url($donatur->lastPage()) }}">{{ $donatur->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($donatur->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $donatur->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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