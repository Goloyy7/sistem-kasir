<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    // Halaman daftar transaksi untuk admin
    public function adminIndex(Request $request)
    {
        $query = Transaction::with('user')->latest();

        // Search umum: invoice_code atau nama kasir
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'like', '%' . $search . '%')
                ->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(10)->appends($request->query());

        // Mapping label & badge class di sini, bukan di Blade
        foreach ($transactions as $transaction) {
            $method = $transaction->payment_method;

            $labelMap = [
                'cash'   => 'Cash',
                'debit'  => 'Kartu Debit',
                'credit' => 'Kartu Kredit',
                'ewallet'=> 'E-Wallet / QRIS',
            ];

            $badgeMap = [
                'cash'   => 'badge-success',
                'debit'  => 'badge-info',
                'credit' => 'badge-primary',
                'ewallet'=> 'badge-warning',
            ];

            $transaction->payment_label = $labelMap[$method] ?? strtoupper($method);
            $transaction->payment_badge_class = $badgeMap[$method] ?? 'badge-secondary';
        }

        return view('transactions.index', compact('transactions'));
    }

    // Halaman transaksi kasir
    public function index(Request $request)
    {
        // Ambil cart dari session (kalau belum ada, isi dengan array kosong)
        $cart = $request->session()->get('cart', []);

        // Hitung total harga dari cart
        $totalPrice = $this->calculateTotalPrice($cart);

        $products = Product::orderBy('kode_barang')->get();
        return view('kasir.transaksi', compact('cart', 'totalPrice', 'products'));
    }

    // Tambah Produk ke cart (store)
    public function addToCart(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'kode_barang' => 'nullable|string',
            'kode_barang_select' => 'nullable|string',
            'qty'         => 'required|integer|min:1',
        ]);

        $qtyInput   = $request->qty;

        if ($request->filled('kode_barang_select')) {
            // Kasir memilih produk dari dropdown
            $inputKode = trim($request->kode_barang_select);
        } else {
            // Kasir mengetik manual di input teks
            $inputKode = trim($request->kode_barang);
        }

        if ($inputKode === '') {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Silakan input kode barang atau pilih dari daftar produk.');
        }

        // Normalisasi kode_barang (misal: hapus spasi, uppercase, dsb
        if (ctype_digit($inputKode)) {
            // Jika hanya angka, tambahkan prefix 'PRD-'
            $kodeBarang = 'PRD-' . str_pad($inputKode, 5, '0', STR_PAD_LEFT);
        } else {
            $kodeBarang = $inputKode;
        }


        // Cari produk berdasarkan kode_barang
        $product = Product::where('kode_barang', $kodeBarang)->first();

        // Jika produk tidak ditemukan di database
        if (!$product) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Kode barang tidak ditemukan.');
        }

        // Jika produk ditemukan tetapi stok-nya 0
        if ($product->stock <= 0) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Stok produk ini sudah habis.');
        }

        // Ambil cart saat ini dari session
        $cart = $request->session()->get('cart', []);

        $productId = $product->id;

        // Hitung total qty yang akan ada di cart (qty lama + qty baru)
        $qtyExisting = $cart[$productId]['qty'] ?? 0;
        $qtyTotal    = $qtyExisting + $qtyInput;

        // Jika total qty melebihi stok tersedia
        if ($qtyTotal > $product->stock) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Jika produk sudah ada di cart, tambahkan qty-nya
        if (isset($cart[$productId])) {
            $cart[$productId]['qty']      = $qtyTotal;
            $cart[$productId]['subtotal'] = $cart[$productId]['qty'] * $cart[$productId]['price'];
        } else {
            // Jika belum ada di cart, buat item baru
            $cart[$productId] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $product->price,
                'qty'          => $qtyInput,
                'subtotal'     => $product->price * $qtyInput,
            ];
        }

        // Simpan kembali cart ke session
        $request->session()->put('cart', $cart);

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Update qty produk yang sudah di cart
    public function updateCart(Request $request, $productId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = $request->session()->get('cart', []);

        // Jika item tidak ditemukan di cart
        if (!isset($cart[$productId])) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        // Ambil produk dari database untuk cek stok
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Data produk tidak ditemukan di database.');
        }

        // Jika qty baru melebihi stok
        if ($request->qty > $product->stock) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $product->stock);
        }

        // Update qty dan subtotal
        $cart[$productId]['qty']      = $request->qty;
        $cart[$productId]['subtotal'] = $cart[$productId]['qty'] * $cart[$productId]['price'];

        // Simpan kembali ke session
        $request->session()->put('cart', $cart);

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Jumlah produk berhasil diubah.');
    }

    // Hapus item dari cart
    public function removeFromCart(Request $request, $productId)
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Produk dihapus dari keranjang.');
    }

    // Hapus semua item dari cart
    public function clearCart(Request $request)
    {
        $request->session()->forget('cart');

        return redirect()->route('kasir.transaksi.index')
            ->with('success', 'Keranjang telah dikosongkan.');
    }

    /**
     * Proses transaksi:
     * - simpan ke tabel transactions
     * - simpan detail ke transaction_details
     * - kurangi stok produk
     * - kosongkan cart
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'pay_amount'     => 'required|integer|min:0',
        ]);

        $cart = $request->session()->get('cart', []);

        // Kalau cart kosong, tidak bisa checkout
        if (empty($cart)) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $totalPrice = $this->calculateTotalPrice($cart);

        // Validasi uang bayar cukup
        if ($request->pay_amount < $totalPrice) {
            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Uang yang dibayarkan kurang.');
        }

        DB::beginTransaction();

        try {
            // Buat transaksi utama
            $transaction = Transaction::create([
                'user_id'        => auth('user')->id(),          // kasir yang sedang login
                'total_price'    => $totalPrice,
                'pay_amount'     => $request->pay_amount,
                'change_amount'  => $request->pay_amount - $totalPrice,
                'payment_method' => $request->payment_method,
                // invoice_code otomatis dibuat di model (booted)
            ]);

            // Simpan detail transaksi dan update stok produk
            foreach ($cart as $item) {
                // Simpan ke transaction_details
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'price'          => $item['price'],
                    'quantity'            => $item['qty'],
                    'subtotal'       => $item['subtotal'],
                ]);

                // Kurangi stok produk
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->stock -= $item['qty'];

                    // Jangan sampai stok minus
                    if ($product->stock < 0) {
                        $product->stock = 0;
                    }

                    $product->save();
                }
            }

            DB::commit();

            // Kosongkan cart setelah transaksi berhasil
            $request->session()->forget('cart');

            // Redirect ke halaman struk pakai invoice_code
            return redirect()->route('kasir.transaksi.struk', $transaction->invoice_code)
                ->with('success', 'Transaksi berhasil diproses.');
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            // Bisa kamu log kalau mau debug
            // logger()->error($e->getMessage());

            return redirect()->route('kasir.transaksi.index')
                ->with('error', 'Terjadi kesalahan saat memproses transaksi.');
        }
    }

    // Menampilkan struk transaksi berdasarkan invoice_code
    public function struk($invoiceCode)
    {
        // Cari transaksi berdasarkan invoice_code
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->with(['user', 'details.product'])
            ->firstOrFail();

        return view('kasir.struk', compact('transaction'));
    }

    // Download struk pdf sebagai kasir
    public function strukPdf($invoiceCode)
    {
        $transaction = Transaction::where('invoice_code', $invoiceCode)
            ->with(['user', 'details.product'])
            ->firstOrFail();

        $customPaper = [0, 0, 200, 600];

        // View khusus PDF, misal: resources/views/kasir/struk-pdf.blade.php
        $pdf = Pdf::loadView('kasir.struk-pdf', compact('transaction'))
            ->setPaper($customPaper, 'portrait');

        $fileName = 'struk-' . $transaction->invoice_code . '.pdf';

        // Jika yang login adalah ADMIN → tampilkan di browser (stream)
        if (Auth::guard('admin')->check()) {
            return $pdf->stream($fileName);
        }

        // Jika yang login adalah USER / KASIR → paksa download
        if (Auth::guard('user')->check()) {
            return $pdf->download($fileName);
        }

        // Fallback (kalau tidak jelas guard-nya), aman-aman saja paksa download
        return $pdf->download($fileName);
    }

    // Fungsi untuk menghitung total harga dari cart
    private function calculateTotalPrice(array $cart): int
    {
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['subtotal'];
        }

        return $total;
    }
}