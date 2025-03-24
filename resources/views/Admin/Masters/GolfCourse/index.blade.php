<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            <div class="row justify-content-start mt-3">
                <div class="col-auto">
                    <a href="{{ route('golf-course.create') }}" class="btn btn-success  d-flex align-items-center justify-content-center"><i class="fa fa-plus"></i> ADD</a>
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
                    <form action="{{ route('golf-course.index') }}" method="GET" class="d-flex">
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
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Contact Person</th>
                            {{-- <th>Course Rating</th> --}}
                            {{-- <th>Slope Rating</th> --}}
                            <th>Par</th>
                            <th>Active</th>
                            <th colspan="4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($golfCourse as $key => $gc)
                            <tr>
                                <th scope="row">{{ $golfCourse->firstItem() + $key }}</th>
                                <td>{{ $gc->name }}</td>
                                <td>{{ $gc->address }}</td>
                                <td>{{ $gc->contact }}</td>
                                <td>{{ $gc->contact_person_name }}/{{ $gc->contact_person_Phone }}</td>
                                {{-- <td>{{ $gc->course_rating }}</td> --}}
                                {{-- <td>{{ $gc->slope_rating }}</td> --}}
                                <td>{{ $gc->number_par }}</td>
                                <td>{{ ($gc->is_staging == '1') ? 'Active' : 'Deactivate' }}</td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('golf-course.edit', ['golf_course' => $gc->id]) }}">EDIT</a>
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('golf-course.teebox.index', ['golf_course_id' => $gc->id]) }}">Tee Box</a>
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('golf-course.hole.index', ['golf_course_id' => $gc->id]) }}">Hole</a>
                                </td>
                                <td>
                                    <form action="{{ route('golf-course.destroy', ['golf_course' => $gc->id]) }}" method="POST">
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
                        @if ($golfCourse->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $golfCourse->previousPageUrl() }}" rel="prev"><i class="icon ion-ios-arrow-back"></i></a>
                            </li>
                        @endif
            
                        @if ($golfCourse->currentPage() > 3)
                            <li class="page-item">
                                <a class="page-link" href="{{ $golfCourse->url(1) }}">1</a>
                            </li>
                            @if ($golfCourse->currentPage() > 4)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif
            
                        @foreach(range(1, $golfCourse->lastPage()) as $i)
                            @if ($i >= $golfCourse->currentPage() - 2 && $i <= $golfCourse->currentPage() + 2)
                                @if ($i == $golfCourse->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $golfCourse->url($i) }}">{{ $i }}</a></li>
                                @endif
                            @endif
                        @endforeach
            
                        @if ($golfCourse->currentPage() < $golfCourse->lastPage() - 2)
                            @if ($golfCourse->currentPage() < $golfCourse->lastPage() - 3)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $golfCourse->url($golfCourse->lastPage()) }}">{{ $golfCourse->lastPage() }}</a>
                            </li>
                        @endif
            
                        @if ($golfCourse->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $golfCourse->nextPageUrl() }}" rel="next"><i class="icon ion-ios-arrow-forward"></i></a>
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