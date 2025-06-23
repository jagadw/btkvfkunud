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
                    <div class="col-md-4">
                        <label class="mb-1">Cari Pasien</label>
                        <div class="position-relative">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-2"></i>
                            <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid ps-10 border-primary border-3 text-primary" placeholder="Cari Nama / No Rekam Medis" wire:model.live.debounce.100ms="search" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="mb-1">Waktu Operasi</label>
                        <input type="month" id="tanggal_operasi" class="form-control" wire:model="tanggal_operasi" onchange="@this.set('tanggal_operasi', this.value)">
                    </div>
                    <div class="col-md-5 d-flex align-items-end gap-2">
                        <button class="btn btn-sm fw-bold btn-danger" onclick="exportToPDF()">Export PDF</button>
                        <button class="btn btn-sm fw-bold btn-success" onclick="exportToExcel()">Export EXCEL</button>
                    </div>
                </div>

                <div class="main m-5">

                    <table id="table-responsive" class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6">
                                <th>No</th>
                                <th class="aksi">Aksi</th>
                                <th>No Rekam Medis</th>
                                <th>Pasien</th>
                                <th>Operator</th>
                                <th>Asisten 1</th>
                                <th>Asisten 2</th>
                                <th>On Loop</th>
                                <th>Tanggal Operasi</th>
                                <th>Realisasi</th>
                                <th>Kesesuaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tindakans as $index => $t)
                            <tr>
                                <td>{{ $index + 1}}</td>
                                <td class="aksi">
                                    <div class="dropdown">
                                        <a href="#" class="btn-primary btn btn-sm btn-light btn-flex btn-center btn-primary fs-5" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            Aksi
                                            <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                        </a>
                                        <!--begin::Menu-->
                                        @php
                                        if(Auth::user()->roles->pluck('name')->first() == 'dokter') {
                                        $mahasiswa = Auth::user()->mahasiswa()->withTrashed()->first();
                                        $disabled = !$mahasiswa || ($mahasiswa && ($mahasiswa->deleted_at || $mahasiswa->status == 'nonaktif'));
                                        } else {
                                        $disabled = false;
                                        }
                                        @endphp
                                        @if(!$disabled)
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('edit-tindakan', ['id' => encrypt($t->id)]) }}" class="menu-link bg-warning text-dark px-3 w-100">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link bg-danger text-white px-3 w-100" wire:click="delete({{ $t->id }})">Hapus</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>

                                        @else
                                        <div class="menu-item px-3">

                                        </div>

                                        @endif
                                        <!--end::Menu-->
                                    </div>
                                </td>
                                <td>{{ $t->pasien->nomor_rekam_medis ?? '-' }}</td>
                                <td>{{ $t->pasien->nama ?? '-' }}</td>
                                <td>{{ $t->operator->name ?? '-' }}</td>
                                <td>{{ $t->asisten1->name ?? '-' }}</td>
                                <td>{{ $t->asisten2->name ?? '-' }}</td>
                                <td>{{ $t->onLoop->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal_operasi)->format('d M Y') }}</td>
                                <td>{{ $t->relealisasi_tindakan }}</td>
                                <td>{{ $t->kesesuaian }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Data Tidak Ditemukan</td>
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
    function exportToExcel() {
        document.querySelectorAll('.aksi').forEach(el => el.style.display = 'none');
        var table = document.getElementById("table-responsive");
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Data Tindakan Pasien"
        });


        var ws = wb.Sheets["Data Tindakan Pasien"];
        var cols = [];
        var range = XLSX.utils.decode_range(ws["!ref"]);
        for (var C = range.s.c; C <= range.e.c; ++C) {
            var maxWidth = 10;
            for (var R = range.s.r; R <= range.e.r; ++R) {
                var cell = ws[XLSX.utils.encode_cell({
                    r: R
                    , c: C
                })];
                if (cell && cell.v) {
                    maxWidth = Math.max(maxWidth, cell.v.toString().length);
                }
            }
            cols.push({
                wch: maxWidth
            });
        }
        ws["!cols"] = cols;


        for (var R = range.s.r; R <= range.e.r; ++R) {
            for (var C = range.s.c; C <= range.e.c; ++C) {
                var cellAddress = XLSX.utils.encode_cell({
                    r: R
                    , c: C
                });
                if (!ws[cellAddress]) continue;
                if (!ws[cellAddress].s) ws[cellAddress].s = {};
                ws[cellAddress].s.alignment = {
                    horizontal: "center"
                    , vertical: "center"
                };
            }
        }

        var tanggal_operasi = document.getElementById("tanggal_operasi").value || "";
        XLSX.writeFile(wb, `Data Tindakan Pasien - ${tanggal_operasi}.xlsx`);
    }

    function exportToPDF() {
        var printContents = document.querySelector('.main').cloneNode(true);
        var originalContents = document.body.innerHTML;


        printContents.querySelectorAll('.action').forEach(el => el.remove());

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
                title: message
                , showCancelButton: true
                , confirmButtonText: "Ya"
                , cancelButtonText: "Tidak"
                , icon: "warning"
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
