<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($pollings))
            <form action="{{ route('polling.update', ['pollings' => $pollings->id]) }}" method="POST">
            @method('PATCH')
            @else
            <form action="{{ route('polling.store') }}" method="POST">
            @endif
                @csrf
                     {{-- TITLE --}}
                <div class="form-group">
                    <label for="title">Judul Polling</label>
                    @error('title')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                        value="{{ old('title', $pollings->title ?? '') }}">
                </div>

                {{-- TITLE DESCRIPTION --}}
                <div class="form-group">
                    <label for="title_description">Deskripsi Judul</label>
                    @error('title_description')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <textarea name="title_description" id="title_description"
                        class="form-control @error('title_description') is-invalid @enderror">{{ old('title_description', $pollings->title_description ?? '') }}</textarea>
                </div>

                {{-- QUESTION --}}
                <div class="form-group">
                    <label for="question">Pertanyaan</label>
                    @error('question')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="text" name="question" id="question" class="form-control @error('question') is-invalid @enderror"
                        value="{{ old('question', $pollings->question ?? '') }}">
                </div>

                {{-- QUESTION DESCRIPTION --}}
                <div class="form-group">
                    <label for="question_description">Deskripsi Pertanyaan</label>
                    @error('question_description')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <textarea name="question_description" id="question_description"
                        class="form-control @error('question_description') is-invalid @enderror">{{ old('question_description', $pollings->question_description ?? '') }}</textarea>
                </div>

                {{-- DEADLINE --}}
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    @error('deadline')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <input type="datetime-local" name="deadline" id="deadline"
                        class="form-control @error('deadline') is-invalid @enderror"
                        value="{{ old('deadline', isset($pollings->deadline) ? \Carbon\Carbon::parse($pollings->deadline)->format('Y-m-d\TH:i') : '') }}">
                </div>

                {{-- TARGET ROLES --}}
                <div class="form-group">
                    <label for="target_roles">Target Roles</label>
                    @error('target_roles')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="target_roles[]" id="target_roles" class="form-control @error('target_roles') is-invalid @enderror" multiple>
                        <option value="pengurus" {{ in_array('pengurus', old('target_roles', $pollings->target_roles ?? [])) ? 'selected' : '' }}>Pengurus</option>
                        <option value="pengawas" {{ in_array('pengawas', old('target_roles', $pollings->target_roles ?? [])) ? 'selected' : '' }}>Pengawas</option>
                        <option value="pembina" {{ in_array('pembina', old('target_roles', $pollings->target_roles ?? [])) ? 'selected' : '' }}>Pembina</option>
                        <option value="anggota" {{ in_array('anggota', old('target_roles', $pollings->target_roles ?? [])) ? 'selected' : '' }}>Anggota</option>
                        <option value="custom" {{ in_array('custom', old('target_roles', $pollings->target_roles ?? [])) ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>

                {{-- REGION --}}
                <div class="form-group">
                    <label for="target_region_id">Wilayah</label>
                    @error('target_region_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="target_region_id" id="target_region_id" class="form-control @error('target_region_id') is-invalid @enderror">
                        <option value="">-- Pilih Wilayah --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ old('target_region_id', $pollings->target_region_id ?? '') == $region->id ? 'selected' : '' }}>
                                {{ $region->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- COMMUNITY --}}
                <div class="form-group">
                    <label for="target_community_id">Komunitas</label>
                    @error('target_community_id')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <select name="target_community_id" id="target_community_id" class="form-control @error('target_community_id') is-invalid @enderror">
                        <option value="">-- Pilih Komunitas --</option>
                        @foreach ($communities as $community)
                            <option value="{{ $community->id }}" {{ old('target_community_id', $pollings->target_community_id ?? '') == $community->id ? 'selected' : '' }}>
                                {{ $community->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

              {{-- IS CUSTOM TARGET --}}
                <div class="form-check">
                    <input type="hidden" name="is_custom_target" value="0">
                    <input type="checkbox" name="is_custom_target" id="is_custom_target" class="form-check-input" value="1"
                        {{ old('is_custom_target', $pollings->is_custom_target ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_custom_target">Gunakan Custom Target</label>
                </div>

                {{-- IS ACTIVE --}}
                <div class="form-check mt-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                        {{ old('is_active', $pollings->is_active ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Aktifkan Polling</label>
                </div>

                <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#selectAll').on('change', function () {
            const isChecked = $(this).is(':checked');
            $('.hole-checkbox').prop('checked', isChecked);
        });

        $('.hole-checkbox').on('change', function () {
            const totalCheckboxes = $('.hole-checkbox').length;
            const checkedCheckboxes = $('.hole-checkbox:checked').length;

            $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
        });
    });
</script>
