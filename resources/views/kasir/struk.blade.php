{{-- resources/views/kasir/struk.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi - {{ $transaction->invoice_code }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        body {
            background-color: #f8f9fc;
        }

        .struk-wrapper {
            max-width: 480px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 0.75rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .struk-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .struk-header h5 {
            margin-bottom: 0;
        }

        .struk-header small {
            display: block;
            color: #6c757d;
        }

        .struk-meta {
            font-size: 0.85rem;
        }

        .struk-meta td {
            padding: 2px 0;
        }

        .table-items th,
        .table-items td {
            font-size: 0.85rem;
            padding: 4px 0;
            border-top: none !important;
        }

        .struk-footer {
            font-size: 0.85rem;
            text-align: center;
            margin-top: 10px;
            border-top: 1px dashed #ccc;
            padding-top: 8px;
        }

        @media print {
            body {
                background-color: #ffffff;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .struk-wrapper {
                max-width: 100%;
                width: 100%;
                margin: 0;
                box-shadow: none;
                border-radius: 0;
                padding: 4mm; /* pakai mm biar proporsional di kertas kecil */
            }
        }
    </style>
</head>
<body>

{{-- Tombol di atas, hanya muncul di layar (bukan di print) --}}
<div class="no-print text-center mt-3">
    <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-secondary btn-sm">
        &larr; Kembali ke Transaksi
    </a>
    <button onclick="window.print()" class="btn btn-primary btn-sm">
        <i class="fas fa-print mr-1"></i> Print
    </button>
    <a href="{{ route('kasir.transaksi.struk.pdf', $transaction->invoice_code) }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-download mr-1"></i> Download PDF
    </a>
</div>

<div class="struk-wrapper mt-3">
    {{-- Header Toko --}}
    <div class="struk-header">
        <h5><strong>Sistem Kasir</strong></h5>
        <small>Garut, SMKN 1 Garut</small>
        <small>Telp: 0878-4441-7321</small>
    </div>

    <hr>

    {{-- Info Invoice --}}
    <table class="struk-meta w-100">
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

    {{-- Daftar Item --}}
    <table class="table table-sm table-items">
        <thead>
        <tr>
            <th>Barang</th>
            <th class="text-center" style="width: 20%;">Qty</th>
            <th class="text-right" style="width: 30%;">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transaction->details as $detail)
            <tr>
                <td>
                    {{ $detail->product->name ?? 'Produk #' . $detail->product_id }}<br>
                    <small>
                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                    </small>
                </td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-right">
                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <hr>

    {{-- Ringkasan Pembayaran --}}
    <table class="w-100 struk-meta">
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
                {{-- Tampilkan label lebih enak dibaca --}}
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

    {{-- Footer --}}
    <div class="struk-footer">
        Terima kasih telah berbelanja<br>
        <small>Barang yang sudah dibeli tidak dapat dikembalikan.</small>
    </div>
</div>

</body>
</html>
