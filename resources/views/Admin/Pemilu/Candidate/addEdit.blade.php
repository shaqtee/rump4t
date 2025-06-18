<div class="mt-3">
    <div class="card  box-shadow-0 ">
        <div class="card-header">
            <h4 class="card-title mb-1">{{ $title }}</h4>
        </div>
        <div class="card-body pt-0">
            @if (isset($candidate))
                <form action="{{ route('pemilu.candidate.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
                @method('PATCH')
            @else
                <form action="{{ route('pemilu.candidate.add', $pemilu_id) }}" method="POST" enctype="multipart/form-data">
            @endif
                {{-- here --}}
                @csrf
                <input type="hidden" id="t_pemilu_id" name="t_pemilu_id" value="{{ $pemilu_id }}">
                <div class="form-group">
                    <label for="image">Foto</label>
                    @error('image')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <img class="image-preview img-thumbnail wd-100p wd-sm-200 mb-3" style="display: block;">
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image" placeholder="Image" onchange="previewImage()">
                    @if (isset($candidate))
                        <div class="mt-2">
                            <label for="">Foto Profil</label>
                            <img class="img-thumbnail wd-100p wd-sm-200 mb-3" src="{{ $candidate->image }}" style="display: block;">
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ old('name', isset($candidate) ? $candidate->name : '' ) }}" required autofocus>
                    @error('name')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="birth_place">Tempat Lahir</label>
                    <input type="text" class="form-control" name="birth_place" id="birth_place" value="{{ old('birth_place', isset($candidate) ? $candidate->birth_place : '' ) }}" placeholder="Tempat Lahir" required autofocus>
                    @error('birth_place')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="birth_date">Tanggal Lahir</label>
                    @error('birth_date')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                        </div>
                        <input class="form-control fc-datepicker" id="datetimepicker" name="birth_date" value="{{ old('birth_date', isset($candidate) ? $candidate->birth_date : '') }}" type="text" value="" placeholder="Tanggal Lahir" autocomplete="off">
                    </div>
                </div>
                <div class="form-group">
                    <label for="riwayat_pendidikan">Riwayat Pendidikan</label>
                    <input type="text" class="form-control" name="riwayat_pendidikan" value="{{ old('riwayat_pendidikan', isset($candidate) ? $candidate->riwayat_pendidikan : '' ) }}" id="riwayat_pendidikan" placeholder="Riwayat Pendidikan" autocomplete="off">
                    @error('riwayat_pendidikan')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="year_of_retirement">Riwayat Pekerjaan</label>
                    @error('year_of_retirement')
                        <small style="color: red">{{ $message }}</small>
                    @enderror
                    <div class="d-flex" style="gap:10px;">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                Dari
                            </div>
                            <input class="form-control yearPicker" id="tahun_kerja_dari" type="text" value="" placeholder="Tahun" autofocus>
                        </div>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                Hingga
                            </div>
                            <input class="form-control yearPicker" id="tahun_kerja_hingga" type="text" value="" placeholder="Tahun" autofocus>
                        </div>
                    </div>
                    <input type="text" class="form-control mt-2" id="pekerjaan" placeholder="Pekerjaan" autocomplete="off">
                    <div class="btn btm-xs btn-primary mt-2 w-100" id="tambah_kerja">+ Tambahkan</div>
                    <div id="list_riwayat_kerja" class="mt-3"></div>
                </div>
                <div class="form-group">
                    <label>Visi Misi</label>
                    <textarea name="visi_misi" class="form-control">
                        {{ old('visi_misi', $candidate->visi_misi ?? '') }}
                    </textarea>
                </div>
                <div class="form-group mt-3">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                    <label class="mt-0" for="is_active">Activate</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-success" id="create_kandidat" type="submit">Save</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let riwayat_pekerjaan = {!! json_encode($candidate->riwayat_pekerjaan ?? []) !!};
    renderRiwayatKerja();
    //let riwayat_pekerjaan = [];

    // Handle tambah riwayat kerja
    $('#tambah_kerja').on('click', function() {
        let dari = $('#tahun_kerja_dari').val();
        let hingga = $('#tahun_kerja_hingga').val();
        let pekerjaan = $('#pekerjaan').val();
        let label = `${dari} - ${hingga}, ${pekerjaan}`;
        riwayat_pekerjaan.push(label);
        renderRiwayatKerja();
    });

    // Render riwayat kerja ke HTML
    function renderRiwayatKerja() {
        let row = riwayat_pekerjaan.map((v, i) =>
            `<input type="hidden" name="riwayat_pekerjaan[]" value="${v}">
                <div class="riwayat-item">
                <span class="riwayat-text">${i + 1}. ${v}</span>
                <button type="button" class="btn btn-sm btn-danger riwayat-btn" onclick="hapusRiwayat(${i})">hapus</button>
            </div>`
        ).join('');
        $('#list_riwayat_kerja').html(row);
    }

    // Hapus riwayat kerja
    function hapusRiwayat(idx) {
        riwayat_pekerjaan.splice(idx, 1);
        renderRiwayatKerja();
    }

    /*
    // FUNCTION CREATE CANDIDATE AJAX
    function create_candidate(e) {
        e.preventDefault();

        let form = $('#form_create_candidate')[0]; // ambil elemen form pertama (atau ganti selector sesuai kebutuhan)
        let formData = new FormData(form);

        // Kirim riwayat kerja sebagai JSON string
        formData.append('riwayat_pekerjaan', JSON.stringify(riwayat_pekerjaan));
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "/admin/pemilu/candidate/" + $('#t_pemilu_id').val() + "/add",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                console.log('create_candidate',res)
                // Misal: tutup modal, reset form, munculkan toast
                alert('Berhasil tambah kandidat!');
                form.reset();
                riwayat_pekerjaan = [];
                renderRiwayatKerja();
                // $('#modalAddMember').modal('hide'); // Kalau mau auto close modal
            },
            error: function(xhr) {
                // Tampilkan error, misal di atas form
                alert('Gagal menambah kandidat!\n' + xhr.responseText);
            }
        });
    }

    // Bind function ke tombol Save
    $('#create_kandidat').on('click', create_candidate);
    */
</script>