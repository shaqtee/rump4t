<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            <form action="{{ route('users.tambah') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="name">Name</label>
                            @error('name')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input readonly type="text" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', isset($users) ? $users->name : '') }}" name="name" id="name"
                                placeholder="Name" required autofocus>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="avgScore">Average Score</label>
                            @error('avgScore')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input readonly type="text" class="form-control @error('avgScore') is-invalid @enderror"
                                value="{{ old('avgScore', isset($hcp) ? $hcp['avgScore'] : '') }}" name="avgScore" id="avgScore"
                                placeholder="avgScore" required autofocus>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="rounds">Round's</label>
                            @error('rounds')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input readonly type="text" class="form-control @error('rounds') is-invalid @enderror"
                                value="{{ old('rounds', isset($hcp) ? $hcp['rounds'] : '') }}" name="rounds" id="rounds"
                                placeholder="rounds" required autofocus>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="hcp">Handicap Index</label>
                            @error('hcp')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input readonly type="text" class="form-control @error('hcp') is-invalid @enderror"
                                value="{{ old('hcp', isset($hcp) ? $hcp['handicapIndex'] : '') }}" name="hcp" id="hcp"
                                placeholder="hcp" required autofocus>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
