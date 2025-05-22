<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('banner-slide.create') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
                </div>
                <div class="col-auto">
                    <div>
                        <label><b>Automation</b></label>&nbsp;&nbsp;
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="bday_auto" class="custom-control-input" id="bday_auto" {{ $bday_auto->is_active == 1 ? 'checked':'' }}>
                            <label class="custom-control-label" for="bday_auto">
                                <div class="d-flex align-items-center">
                                    Birthday&nbsp;
                                    <div class="d-none loader-bday-auto spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
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
                    <form action="{{ route('banner-slide.index') }}" method="GET" class="d-flex">
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
                            <th>Name</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bannerSlide as $key => $bs)
                            <tr>
                                <th scope="row">{{ $bannerSlide->firstItem() + $key }}</th>
                                <td>{{ $bs->name }}</td>
                                <td><img  class="img-thumbnail" src="{{ $bs->image }}" onerror="this.onerror=null;this.src='https://placehold.co/120x120?text=No+Image';" style="width: 100px; height: 100px; object-fit: cover;" alt=""></td>
                                @if(!empty($bs->flag_auto))
                                    <td class="text-primary">Auto</td>
                                @else
                                    <td>{{ ($bs->on_view == true) ? 'On View' : 'Off View' }}</td>
                                @endif

                                @if(!empty($bs->flag_auto))
                                    <td class="text-primary">Auto</td>
                                @else
                                    <td>
                                        <a class="btn btn-info" href="{{ route('banner-slide.edit', ['banner_slide' => $bs->id]) }}">EDIT</a>
                                    </td>
                                @endif

                                @if(!empty($bs->flag_auto))
                                    <td class="text-primary">Auto</td>
                                @else
                                    <td>   
                                        <form action="{{ route('banner-slide.destroy', ['banner_slide' => $bs->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">DELETE</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-12">
                    <ul class="pagination pagination-success justify-content-center mt-3">
                        @if ($bannerSlide->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $bannerSlide->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($bannerSlide->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $bannerSlide->url(1) }}">1</a>
                            </li>
                            @if ($bannerSlide->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $bannerSlide->lastPage()) as $i)
                            @if ($i >= $bannerSlide->currentPage() - 2 && $i <= $bannerSlide->currentPage() + 2)
                                @if ($i == $bannerSlide->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $bannerSlide->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($bannerSlide->currentPage() < $bannerSlide->lastPage() - 2)
                            @if ($bannerSlide->currentPage() < $bannerSlide->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $bannerSlide->url($bannerSlide->lastPage()) }}">{{ $bannerSlide->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($bannerSlide->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $bannerSlide->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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