<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table td,
        table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }

        @media print {

            button {
                display: none !important;
            }
        }
    </style>
</head>

@php
    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
@endphp

<body>
    <h2>Laporan Keuangan</h2>
    <h2>Bulan {{ $months[request('m')] . ' ' . request('y') }}</h2>

    <table>
        <tr>
            <th>No.</th>
            <th>Deskripsi</th>
            <th>Tanggal</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Saldo</th>
        </tr>
        @php
            $balance = 0;
            $outcome = 0;
            $income = 0;
        @endphp
        @foreach ($cashes as $cash)
            @php
                $balance += $cash->nominal;
                if ($cash->nominal > 0) {
                    $income += $cash->nominal;
                } else {
                    $outcome += $cash->nominal;
                }
            @endphp
            <tr>
                <td>{{ $cash->number }}</td>
                <td>{{ $cash->description }}</td>
                <td>{{ $cash->date->format('d/m/Y') }}</td>
                <td align="right">{{ $cash->nominal > 0 ? number_format($cash->nominal) : '' }}</td>
                <td align="right">{{ $cash->nominal < 0 ? number_format($cash->nominal) : '' }}</td>
                <td align="right">{{ number_format($balance) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Total Pemasukan</th>
            <th align="right" colspan="3" style="text-align: right">{{ number_format($income) }}</th>
        </tr>
        <tr>
            <th colspan="3">Total Pengeluaran</th>
            <th align="right" colspan="3" style="text-align: right">{{ number_format(abs($outcome)) }}</th>
        </tr>
        <tr>
            <th colspan="3">Sisa Saldo</th>
            <th align="right" colspan="3" style="text-align: right">{{ number_format($balance) }}</th>
        </tr>

    </table>

    <button onclick="print()">PRINT</button>

    <script type="text/javascript">
        function print() {
            <
            !--
            window.print();
            //
            -- >
        }
    </script>
</body>

</html>
