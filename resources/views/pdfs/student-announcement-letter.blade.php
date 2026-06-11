<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Pengumuman Peminatan</title>
    <style>
        @page {
            margin: 12px 60px 24px;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111827;
            font-size: 14px;
            margin: 0;
        }

        .page {
            width: 100%;
        }

        .kop {
            width: 100%;
            margin-bottom: 6px;
        }

        .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: -14px;
            width: 100%;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            margin: 0 0 2px;
        }

        .number {
            text-align: center;
            margin: 0 0 10px;
            font-size: 12px;
        }

        p {
            margin: 0 0 7px;
            text-align: justify;
            line-height: 1.05;
        }

        .recipient {
            margin-bottom: 15px;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            margin: 15px 0 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table {
            margin-bottom: 7px;
        }

        .data-table td {
            padding: .8px 0;
            vertical-align: top;
            line-height: 1.05;
        }

        .label {
            width: 170px;
        }

        .colon {
            width: 14px;
        }

        .result-table {
            margin: 4px 0 7px;
            font-weight: bold;
        }

        .result-table td {
            padding: 1px 0;
            vertical-align: top;
            line-height: 1.05;
        }

        .signature-table {
            margin-top: 18px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature {
            position: relative;
            text-align: left;
            padding-left: 64px;
        }

        .signature-space {
            height: 72px;
        }

        .stamp {
            position: absolute;
            left: 18px;
            top: 42px;
            width: 128px;
            opacity: .88;
            z-index: 1;
        }

        .signature-img {
            position: absolute;
            left: 72px;
            top: 56px;
            width: 150px;
            z-index: 2;
        }

        .principal-name {
            position: relative;
            font-weight: bold;
            text-decoration: underline;
            z-index: 3;
        }
    </style>
</head>

<body>
    @php
        $biodata = $student->biodata;
        $testResult = $student->result;
        $psychologyScores = collect($testResult?->psychology_scores ?? []);
        $topPsychologyScore = $psychologyScores->isNotEmpty() ? $psychologyScores->max() : null;
        $parentNames = collect([$biodata?->father_name, $biodata?->mother_name])
            ->filter()
            ->implode(' / ');
        $psychologyResult = $testResult?->recommendedPackage?->name
            ?: ($topPsychologyScore !== null ? 'Skor tertinggi: ' . $topPsychologyScore : '-');
        $finalPackage = $classStudent->package?->name
            ?: $testResult?->finalPackage?->name
            ?: '-';
    @endphp

    <div class="page">
        @if($kopDataUri)
            <img src="{{ $kopDataUri }}" alt="Kop surat" class="kop">
        @endif

        <div class="title">SURAT PENGUMUMAN</div>
        <div class="number">Nomor: {{ $letterNumber }}</div>

        <div class="recipient">
            <div>Kepada Yth.</div>
            <div>Bapak/Ibu Orang Tua/Wali Siswa</div>
            <div>di Tempat</div>
        </div>

        <p>Assalamu'alaikum Wr. Wb, Shalom, Om Swastiastu, Namo Buddhaya, Salam Kebajikan.</p>
        <br>
        <p>Dengan hormat,</p>
        <br>
        <p>
            Berdasarkan hasil evaluasi akademik, psikotes, minat, bakat, serta pertimbangan kemampuan peserta didik
            selama menempuh pendidikan di kelas X, maka {{ $schoolName }} menetapkan pemilihan paket peminatan untuk
            peserta didik yang akan melanjutkan ke kelas XI Tahun Pelajaran 2026/2027.
        </p>

        <p>Adapun data peserta didik sebagai berikut:</p>

        <div class="section-title">DATA PESERTA DIDIK</div>

        <table class="data-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="colon">:</td>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <td class="label">Kelas Asal</td>
                <td class="colon">:</td>
                <td>{{ $student->origin_class ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIS</td>
                <td class="colon">:</td>
                <td>{{ $student->nis ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td class="colon">:</td>
                <td>{{ $student->nisn ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Orang Tua/Wali</td>
                <td class="colon">:</td>
                <td>{{ $parentNames ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Lengkap</td>
                <td class="colon">:</td>
                <td>{{ $biodata?->address ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Hasil Tes Psikotes</td>
                <td class="colon">:</td>
                <td>{{ $psychologyResult }}</td>
            </tr>
            <tr>
                <td class="label">Status Tes</td>
                <td class="colon">:</td>
                <td>Selesai</td>
            </tr>
        </table>

        <div class="section-title">HASIL PENETAPAN PEMINATAN</div>

        <p>Berdasarkan hasil pertimbangan sekolah, peserta didik tersebut dinyatakan:</p>

        <table class="result-table">
            <tr>
                <td class="label">TERPILIH PADA PAKET</td>
                <td class="colon">:</td>
                <td>{{ $finalPackage }}</td>
            </tr>
            <tr>
                <td class="label">KELAS TERPILIH</td>
                <td class="colon">:</td>
                <td>{{ $classStudent->classGroup?->name ?: '-' }}</td>
            </tr>
        </table>
        <br>

        <p>
            Keputusan ini diharapkan dapat menjadi dasar dalam pengembangan potensi akademik serta minat dan bakat
            peserta didik selama menempuh pendidikan di kelas XI.
        </p>

        <p>
            Demikian surat pengumuman ini disampaikan. Atas perhatian dan kerja sama Bapak/Ibu Orang Tua/Wali, kami
            ucapkan terima kasih.
        </p>

        <p>Wassalamu'alaikum Wr. Wb, Shalom, Om Shanti Shanti Om, Namo Buddhaya, Salam Kebajikan.</p>

        <table class="signature-table">
            <tr>
                <td></td>
                <td class="signature">
                    <div>Subang, {{ $issuedDate }}</div>
                    <div>{{ $schoolName }}</div>
                    <div>Kepala Sekolah,</div>
                    @if($stampDataUri)
                        <img src="{{ $stampDataUri }}" alt="Cap sekolah" class="stamp">
                    @endif
                    @if($signatureDataUri)
                        <img src="{{ $signatureDataUri }}" alt="Tanda tangan kepala sekolah" class="signature-img">
                    @endif
                    <div class="signature-space"></div>
                    <div class="principal-name">{{ $principalName }}</div>
                    <div>{{ $principalIdentity }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if($footerDataUri)
        <img src="{{ $footerDataUri }}" alt="Footer surat" class="footer">
    @endif
</body>

</html>
