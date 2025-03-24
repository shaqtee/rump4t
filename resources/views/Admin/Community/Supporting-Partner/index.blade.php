<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('community.sponsor.tambah') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
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
                    <form action="{{ route('community.sponsor.semua') }}" method="GET" class="d-flex">
                            <select id="searchIndex" class="form-control" style="margin-right: 10px;">
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
                            <th>Community</th>
                            {{-- <th>Event</th> --}}
                            <th>Name</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sponsors as $key => $spsr)
                            <tr>
                                <th scope="row">{{ $sponsors->firstItem() + $key }}</th>
                                <td>{{ $spsr->sponsorCommonity->title ?? '-' }}</td>
                                {{-- <td>{{ $spsr->sponsorEvent->title ?? '-' }}</td> --}}
                                <td>{{ $spsr->name }}</td>
                                <td><img class="img-thumbnail" style="width: 100px; height: 100px; object-fit: fill;" src="{{ $spsr->image }}" alt=""></td>
                                <td>{{ $spsr->description }}</td>
                                <td>{{ ($spsr->active == '1') ? 'Active' : 'Deactivate'}}</td>
                                <td>
                                        <a class="btn btn-info" href="{{ route('community.sponsor.ubah', ['id' => $spsr->id]) }}">EDIT</a>
                                        {{-- <form action="{{ route('community.sponsor.hapus', ['id' => $spsr->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">DELETE</button>
                                        </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($sponsors->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $sponsors->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($sponsors->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $sponsors->url(1) }}">1</a>
                            </li>
                            @if ($sponsors->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $sponsors->lastPage()) as $i)
                            @if ($i >= $sponsors->currentPage() - 2 && $i <= $sponsors->currentPage() + 2)
                                @if ($i == $sponsors->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $sponsors->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($sponsors->currentPage() < $sponsors->lastPage() - 2)
                            @if ($sponsors->currentPage() < $sponsors->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $sponsors->url($sponsors->lastPage()) }}">{{ $sponsors->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($sponsors->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $sponsors->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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