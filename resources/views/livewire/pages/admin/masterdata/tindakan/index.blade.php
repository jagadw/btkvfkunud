<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Tindakan</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Manajemen Tindakan</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5  my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Tindakan</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button
                    class="btn btn-md fw-bold btn-danger"
                    wire:click="exportPDF"
                    @if(
                    $tindakans->contains(fn($t) => $t->verifikasi === 0) ||
                    $tindakans->isEmpty() ||
                    empty($tanggal_operasi_start) ||
                    empty($tanggal_operasi_end)
                    ||empty($selectedDivisi) || empty($tindakans)
                    )
                    disabled
                    @endif
                    >
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    Preview Laporan
                </button>
                @php
                if(Auth::user()->roles->pluck('name')->first() == 'dokter') {
                $mahasiswa = Auth::user()->mahasiswa()->withTrashed()->first();
                $disabled = !$mahasiswa || ($mahasiswa && ($mahasiswa->deleted_at || $mahasiswa->status == 'nonaktif'));
                } else {
                $disabled = false;
                }
                @endphp
                <a class="btn btn-sm fs-5 fw-bold btn-primary {{ $disabled ? 'disabled' : '' }}" href="{{ $disabled ? '#' : route('create-tindakan') }}" @if($disabled) aria-disabled="true" @endif wire:navigate.prevent>
                    Tambah Tindakan
                </a>

            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg">
                <div class="row mb-3 align-items-end g-2">
                    <div class="col-md-3">
                        <label class="mb-1">Cari Pasien</label>
                        <div class="position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-2"></i>
                            <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid ps-10 border-primary border-3 text-primary" placeholder="Cari Nama / No Rekam Medis" wire:model.live.debounce.100ms="search" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="mb-1">Rentang Waktu Operasi</label>
                        <div class="d-flex gap-2">
                            <input type="date" id="tanggal_operasi_start" class="form-control" wire:model="tanggal_operasi_start" onchange="@this.set('tanggal_operasi_start', this.value)">
                            <span class="align-self-center">s/d</span>
                            <input type="date" id="tanggal_operasi_end" class="form-control" wire:model="tanggal_operasi_end" onchange="@this.set('tanggal_operasi_end', this.value)">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label>Divisi</label>
                        <select class="form-select" wire:model="selectedDivisi" onchange="@this.set('selectedDivisi', this.value)">
                            <option value="">Pilih Divisi</option>
                            <option value="Jantung Dewasa">Jantung Dewasa</option>
                            <option value="Jantung Pediatri & Kongengital">Jantung Pediatri & Kongengital</option>
                            <option value="Toraks">Toraks</option>
                            <option value="Vaskular">Vaskular</option>
                            <option value="Endovaskular">Endovaskular</option>
                        </select>
                    </div>
                    <!-- <div class="col-md-3 d-flex align-items-end gap-2">
                        
                        {{-- <button class="btn btn-sm fw-bold btn-success" onclick="exportToExcel()">Export EXCEL</button> --}}
                    </div> -->
                </div>

                <div class="main m-5 overflow-auto">
                    {{-- @php
                    $maxAsisten = $tindakans->map(fn($t) => $t->tindakanAsistens->where('tipe', 'asisten')->count())->max();
                    @endphp --}}
                    @php
                    $maxAsisten = $tindakans->map(function($t) {
                    return $t->tindakanAsistens->where('tipe', 'asisten')->count();
                    })->max();
                    @endphp

                    <div class="container">
                        <!-- <h2 class="mb-6">Data Tindakan</h2> -->
                        <div class="row g-6">
                            @forelse ($tindakans as $t)
                            @php
                            $asistens = $t->tindakanAsistens->where('tipe', 'asisten')->values();
                            $onloop = $t->tindakanAsistens->where('tipe', 'onloop')->first();
                            @endphp

                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm border rounded h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="card-title mb-0">Data Tindakan</h5>
                                            <span class="badge text-white {{ $t->verifikasi == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $t->verifikasi == 1 ? 'Terverifikasi' : 'Belum Verifikasi' }}
                                            </span>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-icon btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="w-100 fw-bold btn btn-warning text-dark" href="{{ route('edit-tindakan', ['id' => encrypt($t->id)]) }}">
                                                           <i class="fa-solid fa-pen-to-square text-dark"></i>
                                                            Edit
                                                        </a>
                                                    </li>
                                                    @if(Auth::user()->roles()->pluck('name')->first() == 'operator' || Auth::user()->roles()->pluck('name')->first() == 'admin')
                                                    <li>
                                                        <a class="w-100 fw-bold btn btn-danger text-white" href="#" wire:click="delete({{ $t->id }})">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Hapus
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- <div class="mb-2">
                                            <strong>{{ $t->nama_tindakan ?? '-' }}</strong>
                                        </div> -->
                                        <div class="mb-2">
                                            <strong>No Rekam Medis:</strong> {{ $t->pasien->nomor_rekam_medis ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Nama Pasien:</strong> {{ $t->pasien->nama ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>DPJP:</strong> {{ $t->dpjp->name ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Divis:</strong> {{ $t->divisi ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Diagnosa:</strong> {{ $t->diagnosa ?? '-' }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Tanggal Operasi:</strong> {{ \Carbon\Carbon::parse($t->tanggal_operasi)->format('d M Y') }}
                                        </div>

                                        <div class="mb-3">
                                            <strong>Asisten:</strong>
                                            @if($asistens->count())
                                            <ul class="list-unstyled ms-2 mb-0">
                                                @foreach ($asistens as $as)
                                                <li>
                                                    • {{ $as->user->name ?? '-' }} ({{ $as->role ?? '-' }})
                                                </li>
                                                @endforeach
                                            </ul>
                                            @else
                                            <div class="ms-2">-</div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <strong>On Loop:</strong>
                                            @if($onloop)
                                            <div class="ms-2">• {{ $onloop->user->name ?? '-' }} ({{ $onloop->role ?? '-' }})</div>
                                            @else
                                            <div class="ms-2">-</div>
                                            @endif
                                        </div>

                                        <div class="mt-auto d-flex justify-content-between">


                                            <button
                                                class="btn btn-sm btn-primary"
                                                wire:click="showFoto('{{ $t->foto_tindakan }}')"
                                                @if(empty($t->foto_tindakan)) disabled @endif
                                                >
                                                Lihat Foto
                                            </button>

                                            <a href="{{ route('detail-tindakan',encrypt($t->id)) }}" class="btn btn-sm btn-info">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center">
                                <p>Data Tidak Ditemukan</p>
                            </div>
                            @endforelse
                        </div>
                    </div>




                </div>

                @include('livewire.pages.admin.masterdata.tindakan.modal')
            </div>
        </div>
    </div>
</div>

@push('script')
<script data-navigate-once src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    // function exportToExcel() {
    //     var table = document.getElementById("table-responsive").cloneNode(true);

    //     var aksiIndexes = [];
    //     var ths = table.querySelectorAll('thead th');
    //     ths.forEach((th, idx) => {
    //         if (th.classList.contains('aksi')) aksiIndexes.push(idx);
    //     });

    //     table.querySelectorAll('tr').forEach(tr => {
    //         aksiIndexes.slice().reverse().forEach(idx => {
    //             if (tr.children[idx]) tr.removeChild(tr.children[idx]);
    //         });
    //     });

    //     var wb = XLSX.utils.table_to_book(table, {
    //         sheet: "Data Tindakan Pasien"
    //     });

    //     var ws = wb.Sheets["Data Tindakan Pasien"];
    //     var cols = [];
    //     var range = XLSX.utils.decode_range(ws["!ref"]);
    //     for (var C = range.s.c; C <= range.e.c; ++C) {
    //         var maxWidth = 10;
    //         for (var R = range.s.r; R <= range.e.r; ++R) {
    //             var cell = ws[XLSX.utils.encode_cell({
    //                 r: R
    //                 , c: C
    //             })];
    //             if (cell && cell.v) {
    //                 maxWidth = Math.max(maxWidth, cell.v.toString().length);
    //             }
    //         }
    //         cols.push({
    //             wch: maxWidth
    //         });
    //     }
    //     ws["!cols"] = cols;

    //     for (var R = range.s.r; R <= range.e.r; ++R) {
    //         for (var C = range.s.c; C <= range.e.c; ++C) {
    //             var cellAddress = XLSX.utils.encode_cell({
    //                 r: R
    //                 , c: C
    //             });
    //             if (!ws[cellAddress]) continue;
    //             if (!ws[cellAddress].s) ws[cellAddress].s = {};
    //             ws[cellAddress].s.alignment = {
    //                 horizontal: "center"
    //                 , vertical: "center"
    //             };
    //         }
    //     }

    //     var tanggal_operasi = document.getElementById("tanggal_operasi").value || "";
    //     XLSX.writeFile(wb, `Data Tindakan Pasien - ${tanggal_operasi}.xlsx`);
    // }

    // function exportToPDF() {
    //     var printContents = document.querySelector('.main').cloneNode(true);
    //     var originalContents = document.body.innerHTML;


    //     printContents.querySelectorAll('.aksi').forEach(el => el.remove());

    //     document.body.innerHTML = printContents.innerHTML;
    //     document.title = "Data Tindakan Pasien";
    //     window.print();
    //     document.body.innerHTML = originalContents;
    //     window.location.reload();
    // }
    $(function() {
        Livewire.on('show-modal', () => {
            var modalEl = document.getElementById('fotoModal');
            var existingModal = bootstrap.Modal.getInstance(modalEl);
            if (existingModal) {
                existingModal.dispose();
            }
            var myModal = new bootstrap.Modal(modalEl, {});
            myModal.show();
        });
        Livewire.on('hide-modal', () => {
            var modalEl = document.getElementById('fotoModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
                modal.dispose();
            }
            modalEl.style.display = 'none';
            modalEl.setAttribute('aria-hidden', 'true');
            modalEl.removeAttribute('aria-modal');
            modalEl.removeAttribute('role');
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
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
                    Livewire.dispatch('deleteTindakanConfirmed');
                } else {
                    Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                }
            });
        });


    });
</script>
@endpush