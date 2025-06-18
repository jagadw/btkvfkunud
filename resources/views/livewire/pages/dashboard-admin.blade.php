<x-slot:title>Dashboard Admin</x-slot:title>

<div class="container flex flex-col">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard Admin</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl" style="min-height: 100vh">
            <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                <div class="col-xl-6">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #F1416C;">
                        <div class="card-header pt-5 mb-3">
                            <div class="d-flex flex-center rounded-circle h-80px w-80px bg-danger">
                                <i class="ki-duotone ki-stethoscope text-white fs-2qx lh-0"></i>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end mb-3">
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fs-3hx text-white fw-bold me-6">{{ $JumlahDokter }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="fs-1 text-white">Jumlah Dokter</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #7239EA;">
                        <div class="card-header pt-5 mb-3">
                            <div class="d-flex flex-center rounded-circle h-80px w-80px bg-primary">
                                <i class="ki-duotone ki-hospital text-white fs-2qx lh-0"></i>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end mb-3">
                            <div class="d-flex align-items-center">
                                <span class="fs-3hx text-white fw-bold me-6">{{ $JumlahPasien }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="fs-1 text-white">Jumlah Pasien</span>
                        </div>
                    </div>
                </div>
                                <div class="col-xl-6">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #F1416C;">
                        <div class="card-header pt-5 mb-3">
                            <div class="d-flex flex-center rounded-circle h-80px w-80px bg-danger">
                                <i class="ki-duotone ki-user text-white fs-2qx lh-0"></i>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end mb-3">
                            <div class="d-flex justify-content-center flex-column">
                                <span class="fs-3hx text-white fw-bold me-6">{{ $JumlahTindakan }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="fs-1 text-white">Jumlah Tindakan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>