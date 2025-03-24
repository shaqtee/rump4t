<div class="col-xl-12 mt-3">
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between">
                <h4 class="card-title mg-b-0">{{ $title }}</h4>
            </div>
            {{-- <div class="row row-xs wd-xl-80p">
                <div class="col-sm-1 col-md-1 mt-2">
                    <a href="{{ route('event.manage.registrant.semua') }}" class="btn btn-success "><i class="fa fa-plus"></i> ADD</a>
                </div>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mg-b-0 text-md-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Event Title</th>
                            <th>User Name</th>
                            <th>Status Approve</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $key => $mbr)
                            <tr>
                                <th scope="row">{{ $members->firstItem() + $key }}</th>
                                <td>{{ $mbr->event->title }}</td>
                                <td>{{ $mbr->user->name }}</td>
                                <td>
                                    <form action="{{ route('event.manage.registrant.ubah', ['id' => $mbr->id]) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="d-flex align-items-center">
                                            <select name="approve" class="form-control select2 mr-2" {{-- (old('approve',isset($members)?$mbr->approve:''))=='PAID'||(old('approve',isset($members)?$mbr->approve:''))=='CANCEL'?'disabled':'' --}}>
                                                <option label="Choose one" disabled>Select Status Approve</option>
                                                <option value="WAITING_FOR_PAYMENT" {{ (old('approve', isset($members) ? $mbr->approve : '')) == 'WAITING_FOR_PAYMENT' ? 'selected' : ''}}>WAITING FOR PAYMENT</option>
                                                <option value="PAID" {{ (old('approve', isset($members) ? $mbr->approve : '')) == 'PAID' ? 'selected' : ''}}>PAID</option>
                                                <option value="CANCEL" {{ (old('approve', isset($members) ? $mbr->approve : '')) == 'CANCEL' ? 'selected' : ''}}>CANCEL</option>
                                            </select>
                                            <button type="submit" class="btn btn-success" {{-- (old('approve',isset($members)?$mbr->approve:''))=='PAID'||(old('approve',isset($members)?$mbr->approve:''))=='CANCEL'?'disabled':'' --}}>Save</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row row-sm">
                <div class="col-sm-6 col-lg-4">
                    <ul class="pagination pagination-success mt-3">
                        @if ($members->currentPage() >= 1)
                            <li class="page-item"><a class="page-link" href="{{ $members->previousPageUrl() }}"><i class="icon ion-ios-arrow-back"></i></a></li>
                        @endif
                    
                        @for ($i = 1; $i <= $members->lastPage(); $i++)
                            <li class="page-item {{ ($members->currentPage() == $i) ? 'active' : '' }}">
                                <a class="page-link" href="{{ $members->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor
                    
                        @if ($members->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $members->nextPageUrl() }}"><i class="icon ion-ios-arrow-forward"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>