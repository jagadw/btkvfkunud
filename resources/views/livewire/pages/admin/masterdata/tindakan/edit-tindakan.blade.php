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
                    <li class="breadcrumb-item text-muted">Edit Tindakan</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- <a class="btn btn-sm fw-bold btn-primary" href="{{route('add-tindakan')}}">Tambah Tindakan</a> --}}
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card p-5 shadow-lg">

                {{-- DATA PASIEN --}}
                <h5 class="text-primary fw-bold mb-3">Data Pasien</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6" wire:ignore>
                            <label class="required">Nama Pasien</label>
                            <select class="form-select" wire:model="selectedPasien" data-control="select2" onchange="@this.set('selectedPasien', this.value)" disabled>
                                <option value="">-- Cari Pasien --</option>
                                <option value="manual">+ Tambah Pasien Baru</option>
                                @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->id }}">{{ $pasien->nama }} ({{ $pasien->nomor_rekam_medis }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($selectedPasien == 'manual')
                    <div class="card mt-3">
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <label class="required">No. Rekam Medis</label>
                                <input type="text" placeholder="No Rekam Medis" wire:model="nomor_rekam_medis" maxlength="8" class="form-control @error('nomor_rekam_medis') is-invalid @enderror" pattern="\d{1,8}" title="Maksimal 8 digit angka" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <div class="col-md-6">
                                <label class="required">Nama</label>
                                <input class="form-control" wire:model="nama" />
                            </div>
                            <div class="col-md-3">
                                <label class="required">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" class="form-control" wire:model="tanggal_lahir" onchange="Livewire.dispatch('updateUsia')">
                            </div>
                            <div class="col-md-3">
                                <label class="required">Usia</label>
                                <input type="text" class="form-control fw-bold" value="{{ $usia }}" disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="required">Jenis Kelamin</label>
                                <select class="form-select" wire:model="jenis_kelamin">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="required">Asal Rumah Sakit</label>
                                <input class="form-control" wire:model="asal_rumah_sakit" />
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <hr class="my-4 border-3 border-dark bg-dark">

                {{-- DATA TINDAKAN --}}
                <h5 class="text-primary fw-bold mb-3">Data Tindakan</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4" wire:ignore>
                            <label class="required">DPJP</label>
                            <select class="form-select" data-control="select2" onchange="@this.set('dpjp_id',this.value)" wire:model="dpjp_id" disabled>
                                <option value="">Pilih DPJP</option>
                                @foreach($dpjp as $dokter)
                                <option value="{{ $dokter->id }}">
                                    @if($dokter->dpjp)
                                    {{ $dokter->dpjp->nama . ' - ' . $dokter->dpjp->inisial_residen }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="required">Tanggal Operasi</label>
                            <input type="date" class="form-control" wire:model="tanggal_operasi">
                        </div>
                        <div class="col-md-4">
                            <label class="required">Divisi</label>
                            <select class="form-select" wire:model="divisi" onchange="Livewire.dispatch('divisiChanged')">
                                <option value="">Pilih Divisi</option>
                                <option value="Jantung Dewasa">Jantung Dewasa</option>
                                <option value="Jantung Pediatri & Kongengital">Jantung Pediatri & Kongengital</option>
                                <option value="Toraks">Toraks</option>
                                <option value="Vaskular">Vaskular</option>
                                <option value="Endovaskular">Endovaskular</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="required">Diagnosa</label>
                            <textarea class="form-control" wire:model="diagnosa" rows="2" style="min-height: 150px; resize: vertical;"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="required">Nama Tindakan</label>
                            <textarea class="form-control" wire:model="nama_tindakan" rows="2" style="min-height: 150px; resize: vertical;"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="required">Laporan Tindakan</label>
                            <textarea class="form-control" wire:model="laporan_tindakan" rows="2" style="min-height: 150px; resize: vertical;"></textarea>
                        </div>
                    </div>
                </div>
                {{-- DATA CONFERENCE (Hanya untuk divisi Jantung Dewasa & Jantung Pediatri & Kongengital) --}}
                @if($divisi == 'Jantung Dewasa' || $divisi == 'Jantung Pediatri & Kongengital')
                <hr class="my-4 border-3 border-dark bg-dark">
                <h5 class="text-primary fw-bold mb-3">Data Conference</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="required">Tanggal Conference</label>
                            <input type="date" class="form-control" wire:model="tanggal_conference">
                        </div>
                        <div class="col-md-6">
                            <label class="required">Kesesuaian</label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" id="kesesuaian-sesuai" value="1" wire:model="kesesuaian">
                                    <label class="fw-bold" for="kesesuaian-sesuai">
                                        Sesuai
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" id="kesesuaian-tidak" value="0" wire:model="kesesuaian">
                                    <label class="fw-bold" for="kesesuaian-tidak">
                                        Tidak Sesuai
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="required">Hasil Conference</label>
                            <textarea class="form-control" wire:model="hasil_conference" rows="1" style="height: 150px;"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="required">Realisasi Tindakan</label>
                            <textarea class="form-control" wire:model="realisasi_tindakan" rows="4" style="height: 150px; resize: vertical;"></textarea>
                        </div>
                    </div>
                </div>
                @endif
                <hr class="my-4 border-3 border-dark bg-dark">

                {{-- DATA ASISTEN DAN ON LOOP --}}
                <h5 class="text-primary fw-bold mb-3">Asisten & On Loop</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        {{-- Asisten --}}
                        @foreach($asistens as $index => $asisten)
                        <div class="col-md-3" wire:key="asisten-{{ $index }}">
                            <label class="required">Asisten {{ $index + 1 }}</label>
                            <div class="custom-select2" x-data="customSelect2({ options: [
                                @foreach($users->filter(fn($user) => !$user->roles->pluck('name')->contains('developer') && $user->mahasiswa != null) as $dokter)
                                    { value: '{{ $dokter->id }}', label: '{{ $dokter->mahasiswa->nama . ' - ' . $dokter->mahasiswa->inisial_residen }}' },
                                @endforeach
                            ], selected: @entangle('asistens.' . $index . '.user_id') })" x-init="init()" @click.away="open = false">
                                <div class="select2-display" @click="toggle()" :class="{ 'open': open }">
                                    <span x-text="selectedLabel() || 'Pilih Asisten'"></span>
                                    <svg class="select2-arrow" width="20" height="20" fill="none">
                                        <path d="M6 8l4 4 4-4" stroke="#888" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div class="select2-dropdown" x-show="open" x-transition>
                                    <input type="text" class="select2-search" placeholder="Cari asisten..." x-model="search" @keydown.enter.prevent>
                                    <div class="select2-options">
                                        <template x-for="option in filteredOptions()" :key="option.value">
                                            <div class="select2-option" :class="{ 'selected': option.value == selected }" @click="choose(option)">
                                                <span x-text="option.label"></span>
                                            </div>
                                        </template>
                                        <div class="select2-noresult" x-show="filteredOptions().length === 0">Tidak ditemukan</div>
                                    </div>
                                </div>
                            </div>

                            <style>
                                .custom-select2 {
                                    position: relative;
                                    width: 100%;
                                    font-family: 'Segoe UI', Arial, sans-serif;
                                }

                                .select2-display {
                                    background: #fff;
                                    border: 1.5px solid #b5b5c3;
                                    border-radius: 6px;
                                    padding: 8px 36px 8px 12px;
                                    cursor: pointer;
                                    min-height: 40px;
                                    display: flex;
                                    align-items: center;
                                    position: relative;
                                    transition: border-color 0.2s;
                                }

                                .select2-display.open,
                                .select2-display:focus {
                                    border-color: #009ef7;
                                    box-shadow: 0 0 0 2px #e3f2fd;
                                }

                                .select2-arrow {
                                    position: absolute;
                                    right: 12px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    pointer-events: none;
                                }

                                .select2-dropdown {
                                    position: absolute;
                                    top: 110%;
                                    left: 0;
                                    width: 100%;
                                    background: #fff;
                                    border: 1.5px solid #b5b5c3;
                                    border-radius: 6px;
                                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
                                    z-index: 99;
                                    padding: 8px 0 4px 0;
                                }

                                .select2-search {
                                    width: 94%;
                                    margin: 0 3%;
                                    padding: 7px 10px;
                                    border: 1px solid #e4e6ef;
                                    border-radius: 4px;
                                    outline: none;
                                    margin-bottom: 6px;
                                    font-size: 15px;
                                    background: #f9f9f9;
                                }

                                .select2-options {
                                    max-height: 180px;
                                    overflow-y: auto;
                                }

                                .select2-option {
                                    padding: 8px 16px;
                                    cursor: pointer;
                                    transition: background 0.15s;
                                    font-size: 15px;
                                }

                                .select2-option.selected,
                                .select2-option:hover {
                                    background: #e3f2fd;
                                    color: #009ef7;
                                }

                                .select2-noresult {
                                    padding: 8px 16px;
                                    color: #b5b5c3;
                                    font-size: 14px;
                                }
                            </style>

                            <script>
                                function customSelect2({
                                    options,
                                    selected
                                }) {
                                    return {
                                        open: false,
                                        search: '',
                                        options: options,
                                        selected: selected,
                                        init() {
                                            this.$watch('selected', value => {
                                                this.selected = value;
                                            });
                                        },
                                        toggle() {
                                            this.open = !this.open;
                                            if (this.open) {
                                                this.$nextTick(() => {
                                                    let input = this.$el.querySelector('.select2-search');
                                                    if (input) input.focus();
                                                });
                                            }
                                        },
                                        choose(option) {
                                            this.selected = option.value;
                                            this.open = false;
                                            this.search = '';
                                            this.$dispatch('input', option.value);
                                        },
                                        selectedLabel() {
                                            let found = this.options.find(opt => opt.value == this.selected);
                                            return found ? found.label : '';
                                        },
                                        filteredOptions() {
                                            if (!this.search) return this.options;
                                            return this.options.filter(opt =>
                                                opt.label.toLowerCase().includes(this.search.toLowerCase())
                                            );
                                        }
                                    }
                                }
                            </script>
                        </div>
                        <div class="col-md-2" wire:key="asisten-role-{{ $index }}">
                            <label class="required">Role</label>
                            <select class="form-select" wire:model="asistens.{{ $index }}.role" >
                                <option value="">Pilih Role</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="Bimbingan">Bimbingan</option>
                                <option value="Observer">Observer</option>
                                <option value="Asisten">Asisten</option>
                            </select>
                        </div>
                        <div class="col-md-5" wire:key="asisten-desc-{{ $index }}">
                            <label>Deskripsi</label>
                            <textarea class="form-control" wire:model="asistens.{{ $index }}.deskripsi" rows="1" style="height: 150px;"></textarea>
                        </div>
                        <div class="col-md-2 d-flex align-items-end" wire:key="asisten-action-{{ $index }}">
                            @if($index == 0)
                            <button type="button" class="btn btn-success" wire:click="addAsisten">Tambah Asisten</button>
                            @else
                            <button type="button" class="btn btn-danger" wire:click="removeAsisten({{ $index }})">Hapus</button>
                            @endif
                        </div>
                        @endforeach

                        {{-- On Loop --}}
                        <div class="col-md-3" wire:ignore>
                            <label class="required">On Loop</label>
                            <select class="form-select" wire:model="on_loop.user_id" data-control="select2" onchange="@this.set('on_loop.user_id', this.value)" disabled>
                                <option value="">Pilih On Loop</option>
                                @foreach($users->filter(fn($user) => !$user->roles->pluck('name')->contains('developer') && $user->mahasiswa != null) as $dokter)
                                <option value="{{ $dokter->id }}">
                                    @if($dokter->mahasiswa)
                                    {{ $dokter->mahasiswa->nama . ' - ' . $dokter->mahasiswa->inisial_residen }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="required">Role</label>
                            <select class="form-select" wire:model="on_loop.role" disabled>
                                <option value="">Pilih Role</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="Bimbingan">Bimbingan</option>
                                <option value="Observer" selected>Observer</option>
                                <option value="Asisten">Asisten</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Deskripsi</label>
                            <textarea class="form-control" wire:model="on_loop.deskripsi" rows="1" style="height: 150px;"></textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-3 border-dark bg-dark">

                {{-- FOTO TINDAKAN --}}
                <h5 class="text-primary fw-bold mb-3">Foto Tindakan (Opsional)</h5>
                <div class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label>Foto Tindakan</label>
                            <input type="file" class="form-control" wire:model="foto_tindakan" accept="image/*">
                            @if ($foto_tindakan)
                            <div class="mt-3">
                                <h6>Preview Foto Tindakan:</h6>
                                <div class="d-flex flex-column align-items-start">
                                    <img src="{{ $foto_tindakan->temporaryUrl() }}" class="img-fluid rounded" alt="Foto Tindakan" style="max-width: 200px; max-height: auto;">
                                    <!-- <button type="button" class="btn btn-danger btn-sm mt-2" wire:click="removeFoto">Hapus</button> -->
                                </div>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                <hr class="my-4 border-3 border-dark bg-dark">

                <div class="d-flex justify-content-end">
                    <a class="btn btn-danger me-2" href="{{ route('tindakan') }}" wire:navigate>Batal</a>
                    <button class="btn btn-primary" wire:click="update">{{ $idTindakan ? 'Update' : 'Simpan' }}</button>
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