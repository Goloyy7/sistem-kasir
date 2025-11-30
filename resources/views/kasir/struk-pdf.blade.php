{{-- resources/views/kasir/struk-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_code }}</title>
    <style>
        /**
         * Di PDF kita pakai styling sederhana.
         * Ukuran kertas diatur di controller (strukPdf) dengan customPaper 58mm.
         */

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 4px 6px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .w-100 { width: 100%; }

        hr {
            border: 0;
            border-top: 1px dashed #999;
            margin: 6px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .meta-table td {
            padding: 1px  0;
        }

        .table-items th,
        .table-items td {
            padding: 2px 0;
            font-size: 9px;
        }

        .table-items th {
            border-bottom: 1px solid #ddd;
        }

        .table-summary td {
            padding: 2px 0;
            font-size: 9px;
        }
    </style>
</head>
<body>

{{-- Header toko di bagian atas struk --}}
<div class="text-center" style="margin-bottom: 6px;">
    <strong>Sistem Kasir</strong><br>
    <span>Garut, SMKN 1 Garut</span><br>
    <span>Telp: 0878-4441-7321</span>
</div>

<hr>

{{-- Informasi dasar struk --}}
<table class="w-100" style="font-size: 10px;">
    <tr>
        <td>Invoice</td>
        <td class="text-right">{{ $transaction->invoice_code }}</td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td class="text-right">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <td>Kasir</td>
        <td class="text-right">{{ $transaction->user->name ?? '-' }}</td>
    </tr>
</table>

<hr>

{{-- Daftar barang --}}
<table class="w-100 table-items">
    <thead>
    <tr>
        <th align="left">Barang</th>
        <th align="center" style="width: 15%;">Qty</th>
        <th align="right" style="width: 30%;">Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($transaction->details as $detail)
        <tr>
            <td>
                {{ $detail->product->name ?? 'Produk #' . $detail->product_id }}<br>
                <span style="font-size: 9px;">
                    Rp {{ number_format($detail->price, 0, ',', '.') }}
                </span>
            </td>
            <td align="center">{{ $detail->quantity }}</td>
            <td align="right">
                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<hr>

{{-- Ringkasan pembayaran --}}
<table class="w-100" style="font-size: 10px;">
    <tr>
        <td>Total</td>
        <td class="text-right">
            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
        </td>
    </tr>
    <tr>
        <td>Bayar</td>
        <td class="text-right">
            Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}
        </td>
    </tr>
    <tr>
        <td>Kembalian</td>
        <td class="text-right">
            Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}
        </td>
    </tr>
    <tr>
        <td>Metode</td>
        <td class="text-right">
            @php
                $method = $transaction->payment_method;
                $label = [
                    'cash'   => 'Cash',
                    'debit'  => 'Kartu Debit',
                    'credit' => 'Kartu Kredit',
                    'ewallet'=> 'E-Wallet / QRIS',
                ][$method] ?? strtoupper($method);
            @endphp
            {{ $label }}
        </td>
    </tr>
</table>

<hr>

{{-- Footer / catatan --}}
<div class="text-center" style="font-size: 9px; margin-top: 4px;">
    Terima kasih telah berbelanja<br>
    <span>Barang yang sudah dibeli tidak dapat dikembalikan.</span>
</div>

</body>
</html>
