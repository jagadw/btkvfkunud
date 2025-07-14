<div class="d-flex flex-column flex-column-fluid">
    <style>
        @media print {

            body,
            html {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 190mm !important;
                min-height: 277mm;
                margin: 10mm auto;
                background: #fff;
                box-shadow: none;
                padding: 0;
            }
        }

        .container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            padding: 20mm 10mm;
        }

        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
    <x-slot:title>Laporan Tindakan</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Preview Laporan</h1>
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
                <button
                    class="btn btn-md fw-bold btn-danger"
                    wire:click="downloadPDF">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    Unduh Laporan
                </button>

                <div class="main-content d-flex flex-column">

                    <div class="row mt-5">
                        <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                            <div class="d-flex justify-content-center align-items-center w-100 mb-3">
                                <img src="{{asset('assets/media/logos/logo-unud.webp')}}" alt="" style="width:100px; height:auto; ">
                                <div class="d-flex flex-column text-center">
                                    <p class="fw-bold mb-1">LAPORAN KEGIATAN</p>
                                    <p class="fw-bold mb-1">PPDS BEDAH TORAKS KARDIAK DAN VASKULAR</p>
                                    <p class="fw-bold mb-3">STASE BEDAH {{ strtoupper($divisi) }}</p>
                                    <p class="fw-bold mb-1">RSUP PROF DR I.G.N.G NGOERAH DENPASAR-BALI</p>
                                    <p class="fw-bold mb-2">
                                        Periode : {{ \Carbon\Carbon::parse($tanggal_operasi)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
    
                    </div>
                    <h5 class="text-dark fw-bold mb-4">Data Pasien</h5>
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
    
                    <h5 class="text-dark fw-bold mb-4">Data Tindakan</h5>
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
    
                    @if($divisi == 'Jantung Dewasa' || $divisi == 'Jantung Pediatrik dan Kongenital')
                    <hr class="my-4">
    
                    <h5 class="text-dark fw-bold mb-4">Data Conference</h5>
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
    
                    <h5 class="text-dark fw-bold mb-4">Asisten & On Loop</h5>
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
    
                    <h5 class="text-dark fw-bold mb-4">Foto Tindakan</h5>
                    <div>
                        <img src="{{ $foto_tindakan }}" alt="Foto Tindakan" class="img-fluid rounded-3 shadow" style="max-width: 300px;">
                    </div>
                    @endif
                </div>


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
            Livewire.on('download-single-pdf', () => {
                var element = document.querySelector('.main-content');
                if (!element) {
                    toastr.error('Main content not found');
                    return;
                }

                // Opsi konfigurasi
                var opt = {
                    margin: 0.5,
                    filename: `Log TKV Lanjut I {{$divisi}} {{ $selectedDokter ?? (Auth::user()->mahasiswa?->inisial_residen ?? '') }}.pdf`,
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        allowTaint: true,
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                // Jalankan html2pdf dan langsung save
                html2pdf()
                    .set(opt)
                    .from(element)
                    .save()
                    .then(() => {
                        toastr.success('PDF berhasil diunduh!');
                    })
                    .catch(() => {
                        toastr.error('Gagal mengunduh PDF');
                    });
            });
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