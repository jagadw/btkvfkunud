<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Semua Tindakan</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Semua Tindakan</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5  my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Semua Tindakan</li>
                </ul>
            </div>

            <!-- <div class="d-flex align-items-center gap-2 gap-lg-3">
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
            </div> -->
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg">
                <div class="row mb-3 align-items-end g-2">
                    <div class="col-md-4">
                        <label class="mb-1">Cari Pasien</label>
                        <div class="position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-2"></i>
                            <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid ps-10 border-primary border-3 text-primary" placeholder="Cari Nama / No Rekam Medis" wire:model.live.debounce.100ms="search" />
                        </div>
                    </div>
                    <div class="col-md-5">
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
                        <button 
                            class="btn btn-md fw-bold btn-danger" 
                            onclick="exportToPDF()" 
                            @if(
                                $tindakans->contains(fn($t) => $t->verifikasi === 0) || 
                                $tindakans->isEmpty() || 
                                empty($tanggal_operasi_start) || 
                                empty($tanggal_operasi_end)
                            ) 
                                disabled 
                            @endif
                        >
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                            Unduh Laporan
                        </button>
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

                    <table id="table-responsive" class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6 border-2 text-center align-middle">
                                <th rowspan="2" class="align-middle" style="min-width:40px; width: 50px;">No</th>
                                <!-- <th rowspan="2" class="aksi align-middle" style="min-width:80px; width: 90px;">Aksi</th> -->
                                <th rowspan="2" class="align-middle" style="min-width:120px; width: 140px;">No Rekam Medis</th>
                                <th rowspan="2" class="align-middle" style="min-width:150px; width: 180px;">Pasien</th>
                                <th rowspan="2" class="align-middle" style="min-width:120px; width: 140px;">DPJP</th>
                                <th rowspan="2" class="align-middle" style="min-width:160px; width: 200px;">Nama Tindakan</th>
                                <th rowspan="2" class="align-middle" style="min-width:120px; width: 150px;">Diagnosa</th>
                                <th rowspan="2" class="align-middle" style="min-width:120px; width: 140px;">Tanggal Operasi</th>
                                @for ($i = 1; $i <= $maxAsisten; $i++)
                                    <th colspan="3" class="text-center align-middle" style="min-width:240px; width: 260px;">Asisten {{ $i }}</th>
                                    @endfor
                                    <th colspan="3" class="text-center align-middle" style="min-width:240px; width: 260px;">On Loop</th>
                                    <th rowspan="2" class="align-middle" style="min-width:140px; width: 160px;">Tanggal Conference</th>
                                    <th rowspan="2" class="align-middle" style="min-width:140px; width: 160px;">Hasil Conference</th>
                                    <th rowspan="2" class="align-middle" style="min-width:110px; width: 120px;">Kesesuaian</th>
                                    <th rowspan="2" class="align-middle" style="min-width:140px; width: 160px;">Realisasi Tindakan</th>
                                    <th rowspan="2" class="align-middle" style="min-width:120px; width: 130px;">Foto Tindakan</th>
                                    <th rowspan="2" class="align-middle" style="min-width:140px; width: 160px;">Status Verifikasi</th>
                            </tr>
                            <tr class="fw-semibold fs-6 border-2 text-center align-middle">
                                {{-- Kolom Asisten --}}
                                @for ($i = 1; $i <= $maxAsisten; $i++)
                                    <th class="align-middle" style="min-width:80px; width: 90px;">Nama</th>
                                    <th class="align-middle" style="min-width:60px; width: 70px;">Role</th>
                                    <th class="align-middle" style="min-width:100px; width: 110px;">Deskripsi</th>
                                    @endfor
                                    <th class="align-middle" style="min-width:80px; width: 90px;">Nama</th>
                                    <th class="align-middle" style="min-width:60px; width: 70px;">Role</th>
                                    <th class="align-middle" style="min-width:100px; width: 110px;">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tindakans as $index => $t)
                            @php
                            $asistens = $t->tindakanAsistens->where('tipe', 'asisten')->values();
                            $onloop = $t->tindakanAsistens->where('tipe', 'onloop')->first();
                            @endphp
                            <tr>
                                <td class="text-center align-items-center">{{ $index + 1 }}</td>

                                <td class="text-center align-items-center">{{ $t->pasien->nomor_rekam_medis ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $t->pasien->nama ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $t->dpjp->name ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $t->nama_tindakan ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $t->diagnosa ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ \Carbon\Carbon::parse($t->tanggal_operasi)->format('d M Y') }}</td>

                                {{-- Kolom Asisten --}}
                                @foreach ($asistens as $as)
                                <td class="text-center align-items-center">{{ $as->user->name ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $as->role ?? '-' }}</td>
                                <td class="text-center align-items-center">
                                    @if ($as->deskripsi)
                                    <small>{{ $as->deskripsi }}</small>
                                    @else
                                    -
                                    @endif
                                </td>
                                @endforeach
                                {{-- Jika asisten kurang, tambah kolom kosong --}}
                                @for ($i = $asistens->count(); $i < $maxAsisten; $i++)
                                    <td class="text-center align-items-center">-</td>
                                    <td class="text-center align-items-center">-</td>
                                    <td class="text-center align-items-center">-</td>
                                    @endfor

                                    {{-- Kolom On Loop --}}
                                    @if ($onloop)
                                    <td class="text-center align-items-center">{{ $onloop->user->name ?? '-' }}</td>
                                    <td class="text-center align-items-center">{{ $onloop->role ?? '-' }}</td>
                                    <td class="text-center align-items-center">
                                        @if ($onloop->deskripsi)
                                        <small>{{ $onloop->deskripsi }}</small>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    @else
                                    <td class="text-center align-items-center">-</td>
                                    <td class="text-center align-items-center">-</td>
                                    <td class="text-center align-items-center">-</td>
                                    @endif

                                    <td class="text-center align-items-center">{{ $t->conference?->tanggal_conference ? \Carbon\Carbon::parse($t->conference->tanggal_conference)->format('d M Y') : '-' }}</td>
                                    <td class="text-center align-items-center">{{ $t->conference?->hasil_conference ?? '-' }}</td>
                                    <td class="text-center align-items-center">
                                        @if ($t->conference)
                                        @if ($t->conference?->kesesuaian)
                                        <span class="badge bg-success text-white">Ya</span>
                                        @else
                                        <span class="badge bg-danger text-white">Tidak</span>
                                        @endif
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-center align-items-center">
                                        {{ $t->conference?->realisasi_tindakan ?? '-' }}
                                    </td>
                                    <td class="text-center align-items-center">
                                        @if ($t->foto_tindakan)
                                        <button class="btn btn-sm btn-primary" wire:click="showFoto('{{ $t->foto_tindakan }}')">Lihat Foto</button>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-center align-items-center">
                                        @if ($t->verifikasi == 1)
                                        <span class="badge text-white bg-success text-white">Sudah <br>Di Verifikasi</span>
                                        @else
                                        <span class="badge text-white bg-danger text-white">Belum <br> Diverifikasi</span>
                                        @endif
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ 9 + ($maxAsisten * 3) + 3 }}" class="text-center">Data Tidak Ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>



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

    function exportToPDF() {
        var printContents = document.querySelector('.main').cloneNode(true);
        var originalContents = document.body.innerHTML;


        printContents.querySelectorAll('.aksi').forEach(el => el.remove());

        document.body.innerHTML = printContents.innerHTML;
        document.title = "Data Tindakan Pasien";
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
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