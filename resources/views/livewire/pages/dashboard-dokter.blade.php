<x-slot:title>Dashboard</x-slot:title>

<div class="flex container flex-col">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard Dokter</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    {{-- <li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">Dashboards</li> --}}
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->

            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl" style="min-height: 100vh">
            <!--begin::Row-->
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <!--begin::Col-->
                <div class="col-xl-6">
                    <!--begin::Card widget 3-->
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #F1416C;background-image:url('assets/media/svg/shapes/wave-bg-red.svg')">
                        <!--begin::Header-->
                        <div class="card-header pt-5 mb-3">
                            <!--begin::Icon-->
                            <div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #F1416C">
                                <i class="ki-duotone ki-call text-white fs-2qx lh-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                    <span class="path7"></span>
                                    <span class="path8"></span>
                                </i>
                            </div>
                            <!--end::Icon-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex align-items-end mb-3">
                            <!--begin::Info-->
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fs-3hx text-white fw-bold me-6">{{ $dataJumlahPasienDitangani->count() }}</span>
                                {{-- <div class="fw-bold fs-6 text-white">
									<span class="d-block">This Month</span>
									<span class="">Income</span>
								</div> --}}
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer" style="border-top: 1px solid rgba(255, 255, 255, 0.3);background: rgba(0, 0, 0, 0.15);">
                            <!--begin::Progress-->
                            <div class="fw-bold text-white py-2">
                                <span class="fs-1 d-block">Pasien Di Tangani</span>
                                {{-- <span class="opacity-50">Income</span> --}}
                            </div>
                            <!--end::Progress-->
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card widget 3-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-xl-6">
                    <!--begin::Card widget 3-->
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #7239EA;background-image:url('assets/media/svg/shapes/wave-bg-purple.svg')">
                        <!--begin::Header-->
                        <div class="card-header pt-5 mb-3">
                            <!--begin::Icon-->
                            <div class="d-flex flex-center rounded-circle h-80px w-80px" style="border: 1px dashed rgba(255, 255, 255, 0.4);background-color: #7239EA">
                                <i class="ki-duotone ki-call text-white fs-2qx lh-0">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                    <span class="path7"></span>
                                    <span class="path8"></span>
                                </i>
                            </div>
                            <!--end::Icon-->
                        </div>
                        <!--end::Header-->
                        <!--begin::Card body-->
                        <div class="card-body d-flex align-items-end mb-3">
                            <!--begin::Info-->
                            <div class="d-flex align-items-center">
                                <span class="fs-3hx text-white fw-bold me-6">{{ $dataPasienDitanganiFilter->count() }}</span>
                                {{-- <div class="fw-bold fs-6 text-white">
									<span class="d-block">Outbound</span>
									<span class="">Calls</span>
								</div> --}}
                            </div>
                            <!--end::Info-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer" style="border-top: 1px solid rgba(255, 255, 255, 0.3);background: rgba(0, 0, 0, 0.15);">
                            <!--begin::Progress-->
                            <div class="fw-bold text-white py-2">
                                <span class="fs-1 d-block">Pasien Di Tangani</span>
                                <span class="opacity-50">Bulanan</span>
                            </div>
                            <!--end::Progress-->
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card widget 3-->
                </div>
                <!--end::Col-->
            </div>

            <!--end::Row-->

            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-12">
                    <div class="row mb-5 align-items-center">
                        <div class="col-md-auto mb-2 mb-md-0">
                            <label class="mb-1">Cari Nama Pasien</label>
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-12  border-primary border-3 text-primary" placeholder="Cari Nama Pasien" wire:model.live.debounce.100ms="search" />
                            </div>
                        </div>
                        <div class="col-md-auto mb-2 mb-md-0">
                            <label class="mb-1">Waktu Operasi</label>
                            <input type="month" class="form-control" wire:model="tanggal_operasi" onchange="@this.set('tanggal_operasi', this.value)">
                        </div>
                        <div class="col-md-auto mb-2 mb-md-0 d-flex align-items-center gap-2">
                            <button class="btn btn-sm fw-bold btn-danger" onclick="printToPDF()">Export To PDF</button>
                            <button class="btn btn-sm fw-bold btn-success" onclick="exportToExcel()">Export To EXCEL</button>
                        </div>
                        <div class="col-md-auto d-flex align-items-center">
                            <a class="btn btn-sm fw-bold btn-primary" href="{{route('create-tindakan')}}" wire:navigate>Tambah Tindakan</a>
                        </div>
                    </div>
                </div>
                <div class="main m-5">
                    <div class="table-responsive">
                        <table id="table-responsive" class="table table-row-bordered table-striped gy-5">
                            <thead>
                                <tr class="fw-semibold fs-6">
                                    <th>No</th>
                                    {{-- <th>Aksi</th> --}}
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
                                @forelse ($dataPasienDitanganiFilter as $index => $t)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
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
                    <td>{{ $t->pasien->nama ?? '-' }}</td>
                    <td>{{ $t->operator->name ?? '-' }}</td>
                    <td>{{ $t->asisten1->name ?? '-' }}</td>
                    <td>{{ $t->asisten2->name ?? '-' }}</td>
                    <td>{{ $t->onLoop->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_operasi)->format('d M Y H:i') }}</td>
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
            </div>
        </div>
    </div>
    <!--end::Content container-->
</div>

</div>
@push('script')
<script data-navigate-once src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    function exportToExcel() {
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

        var waktu_operasi_input = document.querySelector('input[type="month"][wire\\:model="tanggal_operasi"]');
        var waktu_operasi = waktu_operasi_input ? waktu_operasi_input.value : "";
        XLSX.writeFile(wb, `Data Tindakan Pasien - ${waktu_operasi}.xlsx`);
    }

    function printToPDF() {
        var printContents = document.querySelector('.main').cloneNode(true);
        var originalContents = document.body.innerHTML;


        printContents.querySelectorAll('.action').forEach(el => el.remove());

        document.body.innerHTML = printContents.innerHTML;
        document.title = "Data Tindakan Pasien";
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }

</script>
@endpush
