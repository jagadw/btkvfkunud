<div class="d-flex flex-column flex-column-fluid">
    <x-slot:title>Manajemen Conference</x-slot:title>

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Conference</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
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
            <div class="card p-5">
                <div class="row mb-5 align-items-center">
                    <div class="col-md-auto mb-2 mb-md-0">
                        <label class="mb-1">Cari Nama Pasien</label>
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Nama Pasien" wire:model.live.debounce.100ms="search" />
                        </div>
                    </div>
                    <div class="col-md-auto mb-2 mb-md-0">
                        <label class="mb-1">Tanggal Conference</label>
                        <input type="month" id="tanggal_conference" class="form-control" wire:model="tanggal_conference" onchange="@this.set('tanggal_conference', this.value)">
                    </div>
                    <div class="col-md-auto mb-2 mb-md-0 d-flex align-items-center gap-2">
                        <button class="btn btn-sm fw-bold btn-danger" onclick="exportToPDF()">Export To PDF</button>
                        <button class="btn btn-sm fw-bold btn-success" onclick="exportToExcel()">Export To EXCEL</button>
                    </div>

                </div>

                <div class="main m-5">
                    <div class="table-responsive">
                        <table id="table-responsive" class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-semibold fs-6">
                                    <th>No</th>
                                    {{-- <th>Aksi</th> --}}
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Diagnosa</th>
                                    <th>Tanggal Conference</th>
                                    <th>Hasil Conference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($conference as $index => $c)
                                <tr>
                                    <td>{{ $conference->firstItem() + $index }}</td>
                                    {{-- <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Aksi
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" wire:click.prevent="edit({{ $t->id }})" data-bs-toggle="modal" data-bs-target="#tindakanModal">
                                    Edit
                                    </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" wire:click.prevent="deleteTindakan({{ $t->id }})" onclick="confirm('Apakah Anda yakin?') || event.stopImmediatePropagation()">
                                            Hapus
                                        </a>
                                    </li>
                                    </ul>
                    </div>
                    </td> --}}
                    <td>{{ $c->pasien_id ?? '-' }}</td>
                    <td>{{ $c->pasien_id ?? '-' }}</td>
                    <td>{{ $c->diagnosa ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($c->tanggal_conference)->format('d M Y H:i') }}</td>
                    <td>{{ $c->hasil_conference }}</td>
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

        var waktu_operasi = document.getElementById("waktu_operasi").value || "";
        XLSX.writeFile(wb, `Data Conference Pasien - ${waktu_operasi}.xlsx`);
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
                    Swal.fire("Canceled", "Action Canceled.", "info");
                }
            });
        });


    });

</script>
@endpush
