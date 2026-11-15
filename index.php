<?php
// Fungsi untuk menghitung diskon berdasarkan total belanja
function hitungDiskon($totalBelanja) {
    $diskon = 0;

    if ($totalBelanja >= 100000) {
        $diskon = 0.10 * $totalBelanja;
    } elseif ($totalBelanja >= 50000) {
        $diskon = 0.05 * $totalBelanja;
    }

    return $diskon;
}

function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

$totalBelanja = '';
$diskon = null;
$totalBayar = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['totalBelanja'] ?? '';

    if ($input === '' || !is_numeric($input) || (float)$input < 0) {
        $error = 'Masukkan total belanja berupa angka nol atau lebih.';
    } else {
        $totalBelanja = (float)$input;
        $diskon = hitungDiskon($totalBelanja);
        $totalBayar = $totalBelanja - $diskon;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Diskon | Toko Sederhana</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            padding: 32px 16px;
            display: grid;
            place-items: center;
            background: #6c3d3d;
            color: #172033;
            font-family: Arial, Helvetica, sans-serif;
        }
        .app {
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 16px 45px rgba(15, 23, 42, .09);
        }
        header {
            padding: 26px 28px;
            color: white;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }
        .eyebrow {
            margin: 0 0 8px;
            font-size: 12px;
            letter-spacing: 1.5px;
            opacity: .85;
            text-transform: uppercase;
        }
        h1 { margin: 0; font-size: 25px; }
        header p:last-child { margin: 9px 0 0; color: #dbeafe; font-size: 14px; }
        main { padding: 26px 28px 28px; }
        label { display: block; margin-bottom: 9px; font-size: 14px; font-weight: 700; }
        .input-wrap { position: relative; }
        .currency {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-weight: 700;
        }
        input {
            width: 100%;
            padding: 14px 14px 14px 48px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            font-size: 16px;
        }
        input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px #dbeafe; }
        .hint { margin: 9px 0 20px; color: #64748b; font-size: 12px; line-height: 1.5; }
        button {
            width: 100%;
            padding: 14px;
            border: 0;
            border-radius: 10px;
            background: #2563eb;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        button:hover { background: #1d4ed8; }
        .error {
            margin-top: 16px;
            padding: 12px;
            border-radius: 9px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
        }
        .result {
            margin-top: 22px;
            padding: 18px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            background: #f8fbff;
        }
        .result h2 { margin: 0 0 16px; font-size: 17px; }
        .line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin: 13px 0;
            color: #475569;
            font-size: 14px;
        }
        .line strong { color: #172033; text-align: right; }
        .discount { color: #15803d !important; }
        .total {
            margin-top: 17px;
            padding-top: 16px;
            border-top: 1px dashed #cbd5e1;
            align-items: center;
        }
        .total span { color: #172033; font-weight: 700; }
        .total strong { color: #1d4ed8; font-size: 21px; }
        .note {
            margin: 15px 0 0;
            padding: 10px 12px;
            border-radius: 8px;
            background: #eff6ff;
            color: #1e40af;
            font-size: 12px;
            line-height: 1.5;
        }
        .rules {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
        }
        .rules h3 { margin: 0 0 10px; font-size: 13px; }
        .rules p { margin: 7px 0; color: #64748b; font-size: 12px; }
        footer { padding: 0 28px 22px; color: #94a3b8; text-align: center; font-size: 11px; }
    </style>
</head>
<body>
<div class="app">
    <header>
        <p class="eyebrow">Aplikasi sederhana</p>
        <h1>Kasir Diskon</h1>
        <p>Hitung potongan harga dan total pembayaran.</p>
    </header>

    <main>
        <form method="POST" action="">
            <label for="totalBelanja">Masukkan total belanja</label>
            <div class="input-wrap">
                <span class="currency">Rp</span>
                <input
                    type="number"
                    id="totalBelanja"
                    name="totalBelanja"
                    min="0"
                    step="1"
                    placeholder="Contoh: 120000"
                    value="<?= htmlspecialchars((string)$totalBelanja, ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>
            <p class="hint">Masukkan jumlah belanja sebelum diskon. Contoh: 120000.</p>
            <button type="submit">Hitung Pembayaran</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($diskon !== null && $totalBayar !== null): ?>
            <section class="result" aria-live="polite">
                <h2>Rincian pembayaran</h2>
                <div class="line">
                    <span>Total belanja</span>
                    <strong><?= rupiah($totalBelanja) ?></strong>
                </div>
                <div class="line">
                    <span>Diskon</span>
                    <strong class="discount">− <?= rupiah($diskon) ?></strong>
                </div>
                <div class="line total">
                    <span>Total yang dibayar</span>
                    <strong><?= rupiah($totalBayar) ?></strong>
                </div>
                <p class="note">
                    <?php if ($totalBelanja >= 100000): ?>
                        Kamu mendapatkan diskon 10%.
                    <?php elseif ($totalBelanja >= 50000): ?>
                        Kamu mendapatkan diskon 5%.
                    <?php else: ?>
                        Belanja di bawah Rp 50.000 belum mendapatkan diskon.
                    <?php endif; ?>
                </p>
            </section>
        <?php endif; ?>

        <section class="rules">
            <h3>Ketentuan diskon</h3>
            <p>Belanja ≥ Rp 100.000: diskon 10%</p>
            <p>Belanja ≥ Rp 50.000 dan &lt; Rp 100.000: diskon 5%</p>
            <p>Belanja &lt; Rp 50.000: tanpa diskon</p>
        </section>
    </main>
    <footer>Kasir Diskon · Contoh program PHP</footer>
</div>
</body>
</html>
