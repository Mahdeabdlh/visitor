
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Laporan Kunjungan</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #222;
            background: #f1f5f9;
        }

        .report-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 35px;
            background: #fff;
            border: 1px solid #e2e8f0;
        }

        .toolbar {
            max-width: 1000px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-print {
            background: #166534;
        }

        .btn-back {
            background: #475569;
        }

        .report-header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 25px;
            border-bottom: 3px double #333;
        }

        .report-header h1 {
            margin: 0 0 8px;
            font-size: 22px;
            text-transform: uppercase;
        }

        .report-header p {
            margin: 5px 0;
            color: #475569;
        }

        .report-title {
            margin: 25px 0;
            text-align: center;
        }

        .report-title h2 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .report-title p {
            margin: 0;
            color: #475569;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 25px;
        }

        .summary-card {
            padding: 15px 10px;
            text-align: center;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }

        .summary-card .label {
            display: block;
            margin-bottom: 8px;
            color: #475569;
            font-size: 11px;
        }

        .summary-card .number {
            font-size: 23px;
            font-weight: bold;
        }

        .section-title {
            margin: 25px 0 12px;
            font-size: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 9px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        thead th {
            background: #e2e8f0;
            color: #1e293b;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .status {
            font-weight: bold;
        }

        .signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 45px;
        }

        .signature-content {
            width: 220px;
            text-align: center;
        }

        .signature-space {
            height: 75px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }

        @media (max-width: 650px) {
            body {
                padding: 10px;
            }

            .report-container {
                padding: 15px;
            }

            .summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-wrapper {
                overflow-x: auto;
            }
        }

        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        @media print {
            body {
                padding: 0;
                background: #fff;
                font-size: 10px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .toolbar {
                display: none !important;
            }

            .report-container {
                max-width: none;
                padding: 0;
                border: none;
            }

            .summary-card {
                break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tr {
                break-inside: avoid;
            }

            .footer {
                color: #333;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol hanya tampil di layar, tidak ikut tercetak --}}
    <div class="toolbar">
        <a href="{{ route('admin.laporan.index') }}" class="btn btn-back">
            Kembali ke Laporan
        </a>

        <button type="button" onclick="window.print()" class="btn btn-print">
            Cetak Laporan
        </button>
    </div>

    <main class="report-container">

        {{-- Identitas laporan --}}
        <header class="report-header">
            <h1>Laporan Kunjungan</h1>
            <p>Sistem Informasi Buku Tamu dan Jadwal Kunjungan</p>
            <p>Dokumen laporan administrasi kunjungan</p>
        </header>

        <section class="report-title">
            <h2>REKAPITULASI DATA KUNJUNGAN</h2>

            <p>
                Periode:
                {{ \Carbon\Carbon::parse($tanggal_awal)->format('d-m-Y') }}
                sampai
                {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d-m-Y') }}
            </p>

            <p>
                Tanggal cetak:
                {{ now()->format('d-m-Y H:i') }}
            </p>
        </section>

        {{-- Ringkasan statistik --}}
        <section class="summary">
            <div class="summary-card">
                <span class="label">Total Kunjungan</span>
                <span class="number">{{ $jumlah_kunjungan }}</span>
            </div>

            <div class="summary-card">
                <span class="label">Disetujui Admin</span>
                <span class="number">{{ $jumlah_disetujui }}</span>
            </div>

            <div class="summary-card">
                <span class="label">Ditolak Admin</span>
                <span class="number">{{ $jumlah_ditolak }}</span>
            </div>

            <div class="summary-card">
                <span class="label">Menunggu Konfirmasi</span>
                <span class="number">{{ $jumlah_menunggu }}</span>
            </div>
        </section>

        {{-- Tabel data kunjungan --}}
        <h3 class="section-title">Rincian Data Kunjungan</h3>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width: 35px;">No.</th>
                        <th>Nama Pemohon</th>
                        <th>Tujuan Kunjungan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Status Admin</th>
                        <th>Catatan Admin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kunjungans as $kunjungan)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $kunjungan->pemohon->nama ?? 'Data pemohon tidak tersedia' }}
                            </td>

                            <td>
                                {{ $kunjungan->tujuannya ?? '-' }}
                            </td>

                            <td>
                                {{ $kunjungan->tanggal_waktu
                                    ? \Carbon\Carbon::parse($kunjungan->tanggal_waktu)->format('d-m-Y H:i')
                                    : '-' }}
                            </td>

                            <td class="text-center">
                                @if ($kunjungan->konfrimasi_admin === 'Y')
                                    <span class="status">Disetujui</span>
                                @elseif ($kunjungan->konfrimasi_admin === 'T')
                                    <span class="status">Ditolak</span>
                                @else
                                    <span class="status">Menunggu</span>
                                @endif
                            </td>

                            <td>
                                {{ $kunjungan->catatan_admin ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Tidak ada data kunjungan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tanda tangan --}}
        <section class="signature">
            <div class="signature-content">
                <p>
                    Dicetak pada
                    {{ now()->format('d-m-Y') }}
                </p>

                <p>Administrator</p>

                <div class="signature-space"></div>

                <p><strong>( ................................ )</strong></p>
            </div>
        </section>

        <footer class="footer">
            Laporan ini dibuat secara otomatis melalui Sistem Informasi Buku Tamu dan Jadwal Kunjungan.
        </footer>

    </main>

</body>
</html>
