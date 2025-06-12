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
                                        @if($items != 1) {{-- hiding description search --}}
                                        <option value="{{ $item }}" data-placeholder="{{ $value['Label'] }}"> {{ $value['Label'] }}</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                            <input class="form-control" type="text" id="dynamicInput" name="" placeholder="">
                        <button class="btn btn-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Image</th>
                            {{-- <th>Description</th> --}}
                            <th>City</th>
                            <th colspan="5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groups as $key => $com)
                            <tr>
                                <th scope="row">{{ $groups->firstItem() + $key }}</th>
                                <td style="text-align:center;">{{ $com->title }}</td>
                                <td style="text-align:center;"><img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ $com->image }}" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" alt=""></td>
                                {{-- <td>{{ $com->description }}</td> --}}
                                <td style="text-align:center;">{{ $com->location }}</td>
                                <td style="text-align:center;"> 
                                    <a class="btn btn-info " href="{{ route('groups.posting.posts', ['groups_id' => $com->id]) }}">Posts</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($groups->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $groups->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($groups->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url(1) }}">1</a>
                            </li>
                            @if ($groups->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $groups->lastPage()) as $i)
                            {{-- {{ dump($groups->currentPage()) }} --}}
                            @if ($i >= $groups->currentPage() - 2 && $i <= $groups->currentPage() + 2)
                                @if ($i == $groups->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $groups->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($groups->currentPage() < $groups->lastPage() - 2)
                            @if ($groups->currentPage() < $groups->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $groups->url($groups->lastPage()) }}">{{ $groups->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($groups->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $groups->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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