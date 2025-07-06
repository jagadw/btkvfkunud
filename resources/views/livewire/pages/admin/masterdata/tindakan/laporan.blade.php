<div>
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
    <div class="container">
        <button
            class="btn btn-md fw-bold btn-danger"
            wire:click="downloadPDF">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            Unduh Laporan
        </button>
        <div class="main-content row w-100 d-flex flex-column align-items-center">
            <div class="row">
                <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                    <div class="d-flex justify-content-center align-items-center w-100 mb-3">
                        <img src="{{asset('assets/media/logos/logo-unud.webp')}}" alt="" style="width:100px; height:auto; ">
                        <div class="d-flex flex-column text-center">
                            <p class="fw-bold mb-1">LAPORAN KEGIATAN</p>
                            <p class="fw-bold mb-1">PPDS BEDAH TORAKS KARDIAK DAN VASKULAR</p>
                            <p class="fw-bold mb-3">STASE BEDAH {{strtoupper( $tindakans->first()->divisi ?? '-')}}</p>
                            <p class="fw-bold mb-1">RSUP PROF DR I.G.N.G NGOERAH DENPASAR-BALI</p>
                            <p class="fw-bold mb-2">
                                Periode : {{ \Carbon\Carbon::parse($tanggal_operasi_start)->translatedFormat('d F Y') }} – {{ \Carbon\Carbon::parse($tanggal_operasi_end)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row w-100">
                <p class="text-center mb-2">A. LAPORAN KEGIATAN OK</p>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-items-center">NO</th>
                            <th class="text-center align-items-center">TANGGAL</th>
                            <th class="text-center align-items-center">IDENTITAS</th>
                            <th class="text-center align-items-center">DIAGNOSA</th>
                            <th class="text-center align-items-center">TINDAKAN</th>
                            <th class="text-center align-items-center">DPJP</th>
                            <th class="text-center align-items-center">KEGIATAN</th>
                            <th class="text-center align-items-center">PARAF</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tindakans as $index => $tindakan)
                        <tr>
                            <td class="text-center align-itemms-center">{{ $index + 1 }}</td>
                            <td class="text-center align-itemms-center">
                                @php
                                $tanggal = \Carbon\Carbon::parse($tindakan->tanggal_operasi);
                                @endphp
                                {{ $tanggal->format('d') }} {{ $tanggal->translatedFormat('F') }} {{ $tanggal->format('Y') }}
                            </td>
                            <td class="text-center align-itemms-center">
                                {{
                                    ($tindakan->pasien?->nama ?? '-') . ' / ' .
                                    ($tindakan->pasien?->jenis_kelamin ?? '-') . ' / ' .
                                    (
                                        $tindakan->pasien?->tanggal_lahir
                                            ? \Carbon\Carbon::parse($tindakan->pasien->tanggal_lahir)->age . ' th'
                                            : '-'
                                    ) . ' / ' .
                                    ($tindakan->pasien?->nomor_rekam_medis ?? '-')
                                }}
                            </td>
                            </td>
                            <td class="text-center align-itemms-center">{{ $tindakan->diagnosa }}</td>
                            <td class="text-center align-itemms-center">{{ $tindakan->nama_tindakan }}</td>
                            <td class="text-center align-itemms-center">{{ 'dr.' . $tindakan->dpjp?->dpjp?->inisial_residen }}</td>
                            <td class="text-center align-itemms-center">
                                {{
                                    optional(
                                        $tindakan->tindakanAsistens
                                            ->when(isset($selectedDokter) && $selectedDokter, function($query) use ($selectedDokter) {
                                                return $query->where('user_id', $selectedDokter);
                                            }, function($query) {
                                                return $query->where('user_id', Auth::id());
                                            })
                                            ->first()
                                    )->role ?? '-'
                                }}

                            </td>
                            <td class="text-center align-itemms-center">
                                @if($tindakan->dpjp?->dpjp?->ttd)
                                <img src="{{ asset('storage/' . $tindakan->dpjp?->dpjp?->ttd) }}" alt="Paraf" style="height:80px;">
                                @endif
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>


            </div>
            <div style="page-break-before: always;"></div>
            <div class="row d-flex justify-content-center flex-column w-100">
                <div class="col-12 d-flex flex-column justify-content-center align-items-center">
                    <div class="d-flex justify-content-center align-items-center w-100 mb-3">
                        <img src="{{asset('assets/media/logos/logo-unud.webp')}}" alt="" style="width:100px; height:auto; ">
                        <div class="d-flex flex-column text-center">
                            <p class="fw-bold mb-1">REKAP KEGIATAN</p>
                            <p class="fw-bold mb-1">PPDS BEDAH TORAKS KARDIAK DAN VASKULAR</p>
                            <p class="fw-bold mb-1">RSUP PROF DR I.G.N.G NGOERAH DENPASAR-BALI</p>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <div><strong>Periode</strong> : {{ \Carbon\Carbon::parse($tanggal_operasi_start)->translatedFormat('d F Y') }} – {{ \Carbon\Carbon::parse($tanggal_operasi_end)->translatedFormat('d F Y') }}</div>
                    @if(isset($selectedDokter))
                    <div><strong>Residen</strong> : {{ \App\Models\User::find($selectedDokter)?->mahasiswa?->nama ?? '-' }}</div>
                    @else
                    <div><strong>Residen</strong> : {{ Auth::user()->mahasiswa->nama }}</div>
                    @endif
                    <div><strong>Stase</strong> : Bedah {{ $tindakans->first()->divisi ?? '-' }}</div>
                    <div><strong>Jumlah Operasi</strong> :</div>
                </div>

                <table class="table table-bordered table-sm w-auto mb-4">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center align-items-center">Aktivitas</th>
                            <th class="text-center align-items-center">Jumlah</th>
                            <th class="text-center align-items-center">Penilaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center align-itemms-center">Asisten</td>
                            <td class="text-center align-itemms-center"></td>
                            <td class="text-center align-itemms-center"></td>
                        </tr>
                        <tr>
                            <td class="text-center align-itemms-center">Bimbingan</td>
                            <td class="text-center align-itemms-center"></td>
                            <td class="text-center align-itemms-center"></td>
                        </tr>
                        <tr>
                            <td class="text-center align-itemms-center">Mandiri</td>
                            <td class="text-center align-itemms-center"></td>
                            <td class="text-center align-itemms-center"></td>
                        </tr>
                        <tr>
                            <td class="text-center align-itemms-center"><strong>Total</strong></td>
                            <td class="text-center align-itemms-center"></td>
                            <td class="text-center align-itemms-center"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mb-2"><strong>Penilaian:</strong></div>
                <ol>
                    <li>Afektif :</li>
                    <li>Kognitif :</li>
                    <li>Psikomotor :</li>
                </ol>

                <div class="mt-5 mb-2 d-flex flex-column align-items-end text-right">
                    <div>Mengetahui</div>
                    <div>Koordinator Program Studi Spesialis Bedah Toraks Kardiak dan Vaskular</div>
                    <div>Fakultas Kedokteran Universitas Udayana</div>
                    <div>RSUP Prof. Dr. I.G.N.G Ngoerah Denpasar</div>
                </div>
                <br>
                <br>
                <br>
                <div class="mt-5 d-flex flex-column align-items-end text-right">
                    <strong>{{$tindakan->koordinator->name}}</strong>
                </div>
            </div>
        </div>

    </div>