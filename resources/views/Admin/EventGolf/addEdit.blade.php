<div class="mt-3">
    <div class="card box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($event))
                <form action="{{ route('event.ubah', ['id' => $event->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('event.tambah') }}" method="POST" enctype="multipart/form-data">
            @endif
                @csrf
                    <div class="">
                        {{-- <div class="form-group">
                            <label for="t_community_id">Community</label>
                            @error('t_community_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_community_id" id="t_community_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Community</option>
                                @foreach ($community as $cmnty)
                                    <option value="{{ $cmnty->id }}"
                                        @if (old('t_community_id', isset($event) ? $event->t_community_id : '') == $cmnty->id)
                                            selected
                                        @endif
                                    >
                                        {{ $cmnty->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="title">Title</label>
                            @error('title')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('title') is-invalid @enderror"  value="{{ old('title', isset($event) ? $event->title : '') }}" name="title" id="title" placeholder="title" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="image">Image</label>
                            @error('image')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                            <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="image" @if(!$event) required autofocus @endif onchange="previewImage()">
                            @if (isset($event))
                                <div class="mt-2">
                                    <label for="">Your image</label>
                                    <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ isset($event) ? $event->image : '' }}" style="display: block;">
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            @error('description')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" placeholder="Description" rows="4" required autofocus>{{ old('description', isset($event) ? $event->description : '') }}</textarea>
                        </div>
                        {{-- <div class="form-group">
                            <label for="location">Location</label>
                            @error('location')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('location') is-invalid @enderror"  value="{{ old('location', isset($event) ? $event->location : '') }}" name="location" id="location" placeholder="Location" required autofocus>
                        </div> --}}
                        <div class="form-group">
                            <label for="m_golf_course_id">Golf Course</label>
                            @error('m_golf_course_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="m_golf_course_id" id="m_golf_course_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Golf Course</option>
                                @foreach ($golfCourse as $gc)
                                    <option value="{{ $gc->id }}" data-tee="{{ json_encode($gc->teeCourse) }}"
                                        @if (old('m_golf_course_id', isset($event) ? $event->m_golf_course_id : '') == $gc->id)
                                            selected
                                        @endif
                                    >
                                        {{ $gc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="course-area-container" class="mt-3"></div>
                        <div class="form-group mt-2">
                            <label for="price">Price</label>
                            @error('price')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <input type="text" class="form-control @error('price') is-invalid @enderror"  value="{{ old('price', isset($event) ? $event->price : '') }}" name="price" id="price" placeholder="Price" required autofocus>
                        </div>
                        {{-- <div class="form-group">
                            <label for="type_scoring">Type Scoring</label>
                            @error('type_scoring')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="type_scoring" id="type_scoring" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Type</option>
                                @foreach ($type_scoring as $ts)
                                    <option value="{{ $ts->value2 }}"
                                        @if (old('type_scoring', isset($event) ? $event->type_scoring : '') == $ts->value2)
                                            selected
                                        @endif
                                    >
                                        {{ $ts->value1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group" >
                            <label>Auto Scoring</label>
                            <br>
                            <label class="custom-switch">
                                <input name="auto_scoring" id="auto_scoring" type="checkbox"
                                       {{ $autoPeoria ? "checked" : "" }}
                                       onchange="toggleTypeScoring()">
                                <span class="custom-switch-slider"></span>
                            </label>
                        </div>
                        {{-- <div class="form-group">
                            <label for="type_scoring">Type Scoring</label>
                            @error('type_scoring')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="type_scoring" id="type_scoring" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Type</option>
                                @foreach ($type_scoring as $ts)
                                    <option value="{{ $ts->id }}"
                                        @if (old('type_scoring', isset($event) ? $event->type_scoring : '') == $ts->id)
                                            selected
                                        @endif
                                    >
                                        {{ $ts->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="type_scoring">Type Scoring</label>
                            @error('type_scoring')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="type_scoring_show" id="type_scoring" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Type</option>
                                @foreach ($type_scoring as $ts)
                                    <option value="{{ $ts->id }}"
                                        @if (old('type_scoring', isset($event) ? $event->type_scoring : '') == $ts->id)
                                            selected
                                        @endif
                                    >
                                        {{ $ts->name }}
                                    </option>
                                @endforeach
                            </select>
                            <!-- Hidden input to send the value when type_scoring is disabled -->
                            <input type="hidden" name="type_scoring" id="type_scoring_hidden" value="" />
                        </div>
                        @if (isset($event))
                        <div class="form-group">
                            <label for="period">Period</label>
                            @error('period')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="period" id="period" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Period</option>
                                @foreach ($period as $p)
                                    <option value="{{ $p->value2 }}" {{ ($p->value2 != 4) ? 'disabled' : '' }}
                                        @if (old('period', isset($event) ? $event->period : '') == $p->value2)
                                            selected
                                        @endif
                                    >
                                        {{ $p->value1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="play_date_start">Start Play Date</label>
                            @error('play_date_start')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input class="form-control fc-datepicker" id="datetimepicker" name="play_date_start" type="text" value="{{ old('play_date_start', isset($event) ? $event->play_date_start : '') }}" placeholder="Start Date Play" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="play_date_end">End Play Date</label>
                            @error('play_date_end')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input class="form-control fc-datepicker" id="datetimepicker2" name="play_date_end" type="text" value="{{ old('play_date_end', isset($event) ? $event->play_date_end : '') }}" placeholder="End Date Play" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="close_registration">Close Regists</label>
                            @error('close_registration')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                </div>
                                <input class="form-control fc-datepicker" name="close_registration" type="text" value="{{ old('close_registration', isset($event) ? $event->close_registration : '') }}" placeholder="Close Regists" required autofocus>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label for="t_winner_category_id">Winner Category</label>
                            @error('t_winner_category_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                @foreach ($master_wc as $mwc)
                                    <div class="col-lg-3">
                                        <label class="ckbox">
                                            <input name="t_winner_category_id[]" value="{{ $mwc->id }}" {{ (isset($winner_category) && in_array($mwc->id, array_column($winner_category, 't_winner_category_id'))) ? 'checked' : '' }} type="checkbox">
                                            <span>{{ $mwc->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <label for="t_tee_man_id">Tee For Man</label>
                            @error('t_tee_man_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_tee_man_id" id="t_tee_man_id" class="form-control select2" required autofocus disabled data-selected="{{ old('t_tee_man_id', isset($event) ? $event->t_tee_man_id : '') }}">
                                <option value="" disabled selected>Select Tee</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="t_tee_ladies_id">Tee For Ladies</label>
                            @error('t_tee_ladies_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_tee_ladies_id" id="t_tee_ladies_id" class="form-control select2" required autofocus disabled data-selected="{{ old('t_tee_ladies_id', isset($event) ? $event->t_tee_ladies_id : '') }}">
                                <option value="" disabled selected>Select Tee</option>
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label for="t_tee_man_id">Tee For Man</label>
                            @error('t_tee_man_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_tee_man_id" id="t_tee_man_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Tee</option>
                                @foreach ($tee_box as $tb)
                                    <option value="{{ $tb->id }}"
                                        @if (old('t_tee_man_id', isset($event) ? $event->t_tee_man_id : '') == $tb->id)
                                            selected
                                        @endif
                                    >
                                        {{ $tb->tee_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="t_tee_ladies_id">Tee For Ladies</label>
                            @error('t_tee_ladies_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="t_tee_ladies_id" id="t_tee_ladies_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Tee</option>
                                @foreach ($tee_box as $tb)
                                    <option value="{{ $tb->id }}"
                                        @if (old('t_tee_ladies_id', isset($event) ? $event->t_tee_ladies_id : '') == $tb->id)
                                            selected
                                        @endif
                                    >
                                        {{ $tb->tee_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="form-group">
                            <label for="m_round_type_id">Round Type</label>
                            @error('m_round_type_id')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <select name="m_round_type_id" id="m_round_type_id" class="form-control select2" required autofocus>
                                <option label="Choose one" disabled selected>Select Round Type</option>
                                @foreach ($holes as $h)
                                    <option value="{{ $h->id }}"
                                        @if (old('m_round_type_id', isset($event) ? $event->m_round_type_id : '') == $h->id)
                                            selected
                                        @endif
                                    >
                                        {{ $h->value1 }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="nama_bank">Nama Bank</label>
                                    @error('nama_bank')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <input type="text" class="form-control @error('nama_bank') is-invalid @enderror"  value="{{ old('nama_bank', isset($event) ? $event->nama_bank : '') }}" name="nama_bank" id="nama_bank" placeholder="Nama Bank" required autofocus>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="nama_rekening">Atas Nama</label>
                                    @error('nama_rekening')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <input type="text" class="form-control @error('nama_rekening') is-invalid @enderror"  value="{{ old('nama_rekening', isset($event) ? $event->nama_rekening : '') }}" name="nama_rekening" id="nama_rekening" placeholder="Nama Pengguna" required autofocus>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="no_rekening">No Rekening</label>
                                    @error('no_rekening')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                    <input type="text" class="form-control @error('no_rekening') is-invalid @enderror"  value="{{ old('no_rekening', isset($event) ? $event->no_rekening : '') }}" name="no_rekening" id="no_rekening" placeholder="No Rekening" required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="active">Active</label>
                            @error('active')
                                <small style="color: red">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                <div class="col">
                                    <label class="rdiobox"><input value="1" name="active" type="radio" {{ old('active', isset($event) && $event->active) == '1' ? 'checked' : '' }} required autofocus> <span>Active</span></label>
                                    <label class="rdiobox"><input value="0" name="active" type="radio" {{ old('active', isset($event) && $event->active) == '0' ? 'checked' : '' }} required autofocus> <span>Deactive</span></label>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <br>
                            <h5>Pilih Field untuk Ditampilkan</h5>
                              <div class="row p-3">
                                <div class="col">
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="nomor_anggota"> Nomor Anggota</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="nama"> Nama</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="no_hp"> Nomor HP / WA</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="hadir"> Akan Hadir?</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="pembayaran"> Telah Melakukan Pembayaran</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="bukti_transfer"> Upload Bukti Transfer</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="ukuran_kaos"> Ukuran Kaos Anggota</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="type_lengan"> Type Lengan</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="nominal_pembayaran"> Nominal Pembayaran</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="dengan_pendamping"> Dengan Pendamping</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="nama_pendamping"> Nama Pendamping</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="ukuran_kaos_pendamping"> Ukuran Kaos Pendamping</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="type_lengan_pendamping"> Type Lengan Pendamping</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="nik"> NIK</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="photo_nik"> Upload Photo NIK</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="nik_pendamping"> NIK Pendamping</label>
                                    </div>
                                    <div class="row">
                                        <label><input type="checkbox" name="fields[]" value="photo_nik_pendamping"> Upload Photo NIK Pendamping</label>
                                    </div>
                                </div>
                              </div>
                            <div class="form-preview" id="formPreview">
                              <h5>Preview Form</h5>
                              <br>
                              <div id="previewContent">Checklist field di atas untuk melihat preview form.</div>
                            </div>
                        </div> --}}
                        <button type="submit" class="btn btn-success mt-3 mb-0">Submit</button>
                    </div>
                </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    // Function to toggle 'type_scoring' based on 'default_peoria'
    function toggleTypeScoring() {
        const autoPeoria = document.getElementById('auto_scoring');
        const typeScoring = document.getElementById('type_scoring');
        const hiddenInput = document.getElementById('type_scoring_hidden');
        const defaultPeoria = '<?php echo $defaultPeoria; ?>'; // Ganti dengan nilai PHP jika diperlukan

        if (autoPeoria.checked) {
            typeScoring.setAttribute('disabled', true); // Disable the select element
            hiddenInput.value = defaultPeoria; // Set the value for the hidden input
        } else {
            typeScoring.removeAttribute('disabled'); // Enable the select element
            hiddenInput.value = ''; // Clear the hidden input value
        }
    }

    function chekTypeScoring() {
        const autoPeoria = document.getElementById('auto_scoring');
        const typeScoring = document.getElementById('type_scoring');
        const hiddenInput = document.getElementById('type_scoring_hidden');
        const defaultPeoria = '<?php echo $defaultPeoria; ?>';

        if (autoPeoria.checked) {
            typeScoring.setAttribute('disabled', true);
            hiddenInput.value = defaultPeoria;
        } else {
            typeScoring.removeAttribute('disabled');
            hiddenInput.value = '';
        }
    }

    chekTypeScoring();
    document.addEventListener('DOMContentLoaded', toggleTypeScoring);
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">

<script>
   const baseUrl = "{{ route('admin.course-area.by-golf-course', ['id' => '__ID__']) }}";

    function fetchCourseAreas(golfCourseId) {
        if (golfCourseId) {
            const url = baseUrl.replace('__ID__', golfCourseId);

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    $('#course-area-container').html('');

                    if (response.length > 0) {
                        let html = `<label class="mt-2">Course Areas</label>
                                    <ul id="course-area-list" class="list-group">`;

                        response.forEach(function (area, index) {
                            html += `
                                <li class="list-group-item d-flex align-items-center justify-content-between" data-index="${index}">
                                    <div>
                                        <input type="hidden" name="course_area_ids[]" value="${area.id}">
                                        <span>${area.course_name} (Holes: ${area.holes_number})</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-light move-up">⬆️</button>
                                        <button type="button" class="btn btn-sm btn-light move-down">⬇️</button>
                                    </div>
                                </li>`;
                        });

                        html += `</ul>`;
                        $('#course-area-container').html(html);

                        $('#course-area-list').on('click', '.move-up', function () {
                            let item = $(this).closest('li');
                            item.prev().before(item);
                        });

                        $('#course-area-list').on('click', '.move-down', function () {
                            let item = $(this).closest('li');
                            item.next().after(item);
                        });
                    } else {
                        $('#course-area-container').html('<p class="text-muted">No course areas found.</p>');
                    }
                },
                error: function () {
                    alert('Failed to fetch course areas.');
                }
            });
        }
    }

    $(document).ready(function () {
        const initialGolfCourseId = $('#m_golf_course_id').val();
        if (initialGolfCourseId) {
            fetchCourseAreas(initialGolfCourseId);
        }

        $('#m_golf_course_id').on('change', function () {
            const golfCourseId = $(this).val();
            fetchCourseAreas(golfCourseId);
        });
    });

</script>
{{-- <script>
    const fieldTemplates = {
      nomor_anggota: `<div class="form-group"><label style="margin-right: 12px;">Nomor Anggota</label><input type="text" readonly value="123-45-6"></div>`,
      nama: `<div class="form-group"><label style="margin-right: 12px;">Nama</label><input type="text" readonly></div>`,
      no_hp: `<div class="form-group"><label style="margin-right: 12px;">No HP / WA</label><input type="text" readonly></div>`,
      hadir: `<div class="form-group"><label style="margin-right: 12px;">Akan Hadir?</label><select><option>Hadir</option><option>Tidak Hadir</option><option>Tentative</option></select></div>`,
      pembayaran: `<div class="form-group"><label style="margin-right: 12px;">Telah Melakukan Pembayaran</label><select><option>Sudah Membayar</option><option>Belum Membayar</option></select></div>`,
      bukti_transfer: `<div class="form-group"><label style="margin-right: 12px;">Upload Bukti Transfer</label><input type="file"></div>`,
      ukuran_kaos: `<div class="form-group"><label style="margin-right: 12px;">Ukuran Kaos</label><select><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option></select></div>`,
      type_lengan: `<div class="form-group"><label style="margin-right: 12px;">Type Lengan</label><select><option>Pendek</option><option>Panjang</option></select></div>`,
      dengan_pendamping: `<div class="form-group"><label style="margin-right: 12px;">Dengan Pendamping?</label><input type="text" placeholder="Ya / Tidak"></div>`,
      nama_pendamping: `<div class="form-group"><label style="margin-right: 12px;">Nama Pendamping</label><input type="text"></div>`,
      ukuran_kaos_pendamping: `<div class="form-group"><label style="margin-right: 12px;">Ukuran Kaos Pendamping</label><select><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option></select></div>`,
      type_lengan_pendamping: `<div class="form-group"><label style="margin-right: 12px;">Type Lengan Pendamping</label><select><option>Pendek</option><option>Panjang</option></select></div>`,
      nik: `<div class="form-group"><label style="margin-right: 12px;">NIK</label><input type="text" maxlength="16"></div>`,
      photo_nik: `<div class="form-group"><label style="margin-right: 12px;">Upload Photo NIK</label><input type="file"></div>`,
      nik_pendamping: `<div class="form-group"><label style="margin-right: 12px;">NIK Pendamping</label><input type="text" maxlength="16"></div>`,
      photo_nik_pendamping: `<div class="form-group"><label style="margin-right: 12px;">Upload Photo NIK Pendamping</label><input type="file"></div>`,
      nominal_pembayaran: `<div class="form-group"><label style="margin-right: 12px;">Nominal Pembayaran</label><input type="text"></div>`
    };

    $('input[type="checkbox"]').on('change', function () {
      const selected = $('input[type="checkbox"]:checked').map(function () {
        return $(this).val();
      }).get();

      if (selected.length === 0) {
        $('#previewContent').html('Checklist field di atas untuk melihat preview form.');
        return;
      }

      let previewHTML = '';
      selected.forEach(field => {
        if (fieldTemplates[field]) {
          previewHTML += fieldTemplates[field];
        }
      });

      $('#previewContent').html(previewHTML);
    });
  </script> --}}
