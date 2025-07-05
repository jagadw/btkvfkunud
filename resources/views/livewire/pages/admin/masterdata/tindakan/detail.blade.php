<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Tindakan</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Tindakan</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted" href="{{route('tindakan') }}" wire:navigate>Tindakan</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Detail Tindakan</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- <a class="btn btn-sm fw-bold btn-primary" href="{{route('add-tindakan')}}">Tambah Tindakan</a> --}}
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card shadow-lg p-5 border-0 rounded-4">
                <h5 class="text-primary fw-bold mb-4">Data Pasien</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Nama Pasien</p>
                            <h6 class="fw-bold">{{ $pasien->first()->nama ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">No. Rekam Medis</p>
                            <h6 class="fw-bold">{{ $pasien->first()->nomor_rekam_medis ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Tanggal Lahir</p>
                            @php
                                $tanggal_lahir = $pasien->first()->tanggal_lahir ?? null;
                                if ($tanggal_lahir) {
                                    $tgl = \Carbon\Carbon::parse($tanggal_lahir);
                                    $formatted = $tgl->format('d F Y');
                                } else {
                                    $formatted = '-';
                                }
                            @endphp
                            <h6 class="fw-bold">{{ $formatted }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Usia</p>
                            @php
                                $tanggal_lahir = $pasien->first()->tanggal_lahir ?? null;
                                if ($tanggal_lahir) {
                                    $lahir = \Carbon\Carbon::parse($tanggal_lahir);
                                    $now = \Carbon\Carbon::now();
                                    $diff = $lahir->diff($now);
                                    $usia_str = $diff->y . ' tahun ' . $diff->m . ' bulan ' . $diff->d . ' hari';
                                } else {
                                    $usia_str = '-';
                                }
                            @endphp
                            <h6 class="fw-bold">{{ $usia_str }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Jenis Kelamin</p>
                            <h6 class="fw-bold">{{ $pasien->first()->jenis_kelamin ?? '-' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted">Asal Rumah Sakit</p>
                            <h6 class="fw-bold">{{ $pasien->first()->asal_rumah_sakit ?? '-' }}</h6>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="text-primary fw-bold mb-4">Data Tindakan</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">DPJP</p>
                            <h6 class="fw-bold">{{ $dpjp->first()->name }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Tanggal Operasi</p>
                            @php
                                if (!empty($tanggal_operasi)) {
                                    $tgl_operasi = \Carbon\Carbon::parse($tanggal_operasi);
                                    $formatted_operasi = $tgl_operasi->format('d F Y');
                                } else {
                                    $formatted_operasi = '-';
                                }
                            @endphp
                            <h6 class="fw-bold">{{ $formatted_operasi }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Divisi</p>
                            <h6 class="fw-bold">{{ $divisi ?? '-' }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Diagnosa</p>
                            <h6 class="fw-bold">{{ $diagnosa ?? '-' }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Nama Tindakan</p>
                            <h6 class="fw-bold">{{ $nama_tindakan ?? '-' }}</h6>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1 text-muted">Laporan Tindakan</p>
                            <h6 class="fw-bold">{{ $laporan_tindakan ?? '-' }}</h6>
                        </div>
                    </div>
                </div>

                @if($divisi == 'Jantung Dewasa' || $divisi == 'Jantung Pediatri & Kongengital')
                <hr class="my-4">

                <h5 class="text-primary fw-bold mb-4">Data Conference</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Tanggal Conference</p>
                        @php
                            if (!empty($tanggal_conference)) {
                                $tgl_conf = \Carbon\Carbon::parse($tanggal_conference);
                                $formatted_conf = $tgl_conf->format('d F Y');
                            } else {
                                $formatted_conf = '-';
                            }
                        @endphp
                        <h6 class="fw-bold">{{ $formatted_conf }}</h6>  
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Kesesuaian</p>
                        <h6 class="fw-bold">{{ $kesesuaian == 1 ? 'Sesuai' : ($kesesuaian === 0 ? 'Tidak Sesuai' : '-') }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Hasil Conference</p>
                        <h6 class="fw-bold">{{ $hasil_conference ?? '-' }}</h6>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 text-muted">Realisasi Tindakan</p>
                        <h6 class="fw-bold">{{ $realisasi_tindakan ?? '-' }}</h6>
                    </div>
                </div>
                @endif

                <hr class="my-4">

                <h5 class="text-primary fw-bold mb-4">Asisten & On Loop</h5>
                <div class="mb-4">
                    @foreach($asistens as $index => $asisten)
                    <div class="border rounded-3 p-3 mb-3 bg-light">
                        <p class="mb-1 text-muted">Asisten {{ $index + 1 }}</p>
                        <h6 class="fw-bold">{{ $asisten['nama'] ?? '-' }}</h6>
                        <p class="mb-1 text-muted">Role</p>
                        <h6 class="fw-bold">{{ $asisten['role'] ?? '-' }}</h6>
                        <p class="mb-1 text-muted">Deskripsi</p>
                        <h6 class="fw-bold">{{ $asisten['deskripsi'] ?? '-' }}</h6>
                    </div>
                    @endforeach

                    <div class="border rounded-3 p-3 bg-light">
                        <p class="mb-1 text-muted">On Loop</p>
                        <h6 class="fw-bold">{{ $on_loop['nama'] ?? '-' }}</h6>
                        <p class="mb-1 text-muted">Role</p>
                        <h6 class="fw-bold">{{ $on_loop['role'] ?? '-' }}</h6>
                        <p class="mb-1 text-muted">Deskripsi</p>
                        <h6 class="fw-bold">{{ $on_loop['deskripsi'] ?? '-' }}</h6>
                    </div>
                </div>

                @if($foto_tindakan)
                <hr class="my-4">

                <h5 class="text-primary fw-bold mb-4">Foto Tindakan</h5>
                <div>
                    <img src="{{ $foto_tindakan }}" alt="Foto Tindakan" class="img-fluid rounded-3 shadow" style="max-width: 300px;">
                </div>
                @endif
            </div>

        </div>
    </div>

    @push('script')
    {{-- <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/dropify/dropify.min.js') }}"></script> --}}
    <script>
        // $(document).ready(function() {
        //     $('.dropify').dropify({
        //         messages: {
        //             'default': 'Drag and drop a file here or click'
        //             , 'replace': 'Drag and drop or click to replace'
        //             , 'remove': 'Remove'
        //             , 'error': 'Ooops, something wrong appended.'
        //         }
        //     });
        // });
        $(function() {
            Livewire.on('confirm-delete', (message) => {
                Swal.fire({
                    title: message,
                    showCancelButton: true,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak",
                    icon: "warning"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteMahasiswaConfirmed');
                    } else {
                        Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                    }
                });
            });


        });
    </script>
    @endpush