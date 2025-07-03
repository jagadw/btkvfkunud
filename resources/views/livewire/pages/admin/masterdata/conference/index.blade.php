<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Conference</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-1 flex-column justify-content-center my-0">Manajemen Conference</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-5 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Conference</li>
                </ul>
            </div>
            {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a class="btn btn-sm fw-bold btn-primary" href="{{route('create-conference')}}" wire:navigate>Tambah Conference</a>
        </div> --}}
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card p-5 shadow-lg">
            <div class="row mb-4 align-items-end g-2">
                <div class="col-md-4">
                    <label class="mb-1">Cari Nama Pasien</label>
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-3 mt-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid ps-10 border-primary border-2 text-primary" placeholder="Cari Nama Pasien" wire:model.live.debounce.100ms="search" style="min-width: 180px;" />
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="mb-1">Tanggal Conference</label>
                    <input type="month" id="tanggal_conference" class="form-control" wire:model="tanggal_conference" onchange="@this.set('tanggal_conference', this.value)">
                </div>
                <!-- <div class="col-md-5 d-flex align-items-end justify-content-end gap-2">
                    <button class="btn btn-sm fw-bold btn-danger" onclick="exportToPDF()">Export To PDF</button>
                    <button class="btn btn-sm fw-bold btn-success" onclick="exportToExcel()">Export To EXCEL</button>
                </div> -->
            </div>

            <div class="main m-5">
                <div class="table-responsive">
                    <table id="table-responsive" class="table table-row-bordered table-striped gy-5">
                        <thead>
                            <tr class="fw-semibold fs-6">
                                <th class="text-center align-items-center">No</th>
                                <th class="text-center align-items-center">No Rekam Medis</th>
                                <th class="text-center align-items-center">Nama Pasien</th>
                                <th class="text-center align-items-center">Diagnosa</th>
                                <th class="text-center align-items-center">Hasil Conference</th>
                                <th class="text-center align-items-center">Realisasi Tindakan</th>
                                <th class="text-center align-items-center">Kesesuaian</th>
                                <th class="text-center align-items-center">Tanggal Conference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($conference as $index => $c)
                            <tr>
                                <td class="text-center align-items-center">{{ $index + 1 }}</td>
                                <td class="text-center align-items-center">{{ $c->pasien->nomor_rekam_medis ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $c->pasien->nama ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $c->diagnosa ?? '-' }}</td>
                                <td class="text-center align-items-center">{{ $c->hasil_conference }}</td>
                                <td class="text-center align-items-center">{{ $c->realisasi_tindakan }}</td>
                                <td class="text-center align-items-center">
                                    @if($c->kesesuaian == 1)
                                        <span class="badge text-white bg-success">Sesuai</span>
                                    @else
                                        <span class="badge text-white bg-danger">Tidak Sesuai</span>
                                    @endif
                                </td>
                                <td class="text-center align-items-center">{{ \Carbon\Carbon::parse($c->tanggal_conference)->format('d M Y ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Data Tidak Ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

            {{-- @include('livewire.pages.admin.masterdata.conference.modal') --}}
        </div>
    </div>
</div>
</div>

@push('script')
<script data-navigate-once src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    function exportToExcel() {
        var table = document.getElementById("table-responsive");
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Data Conference Pasien"
        });


        var ws = wb.Sheets["Data Conference Pasien"];
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

        var tanggal_conference = document.getElementById("tanggal_conference").value || "";
        XLSX.writeFile(wb, `Data Conference Pasien - ${tanggal_conference}.xlsx`);
    }

    function exportToPDF() {
        var printContents = document.querySelector('.main').cloneNode(true);
        var originalContents = document.body.innerHTML;


        printContents.querySelectorAll('.action').forEach(el => el.remove());

        document.body.innerHTML = printContents.innerHTML;
        document.title = "Data Conference Pasien";
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
    $(function() {
        Livewire.on('show-modal', () => {
            var modalEl = document.getElementById('mahasiswaModal');
            var existingModal = bootstrap.Modal.getInstance(modalEl);
            if (existingModal) {
                existingModal.dispose();
            }
            var myModal = new bootstrap.Modal(modalEl, {});
            myModal.show();
        });
        Livewire.on('hide-modal', () => {
            var modalEl = document.getElementById('mahasiswaModal');
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
                    Livewire.dispatch('deleteMahasiswaConfirmed');
                } else {
                    Swal.fire("DiBatalkan", "Aksi DiBatalkan.", "info");
                }
            });
        });


    });

</script>
@endpush
