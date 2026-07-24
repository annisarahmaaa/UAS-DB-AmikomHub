<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instruksi Pembayaran - AmikomEventHub</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; margin: 0; padding: 40px 20px; color: #ffffff; }
        .container { max-width: 480px; margin: 0 auto; width: 100%; }
        .header-text { text-align: center; margin-bottom: 30px; }
        .header-text h1 { font-size: 26px; font-weight: 900; margin: 0 0 10px 0; color: #ffffff; }
        .header-text p { color: #94a3b8; margin: 0; font-size: 14px; }
        .card { background-color: #ffffff; color: #0f172a; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .card-header { background: linear-gradient(135deg, #4f46e5, #4338ca); padding: 30px; text-align: center; color: #ffffff; }
        .card-header p { color: #c7d2fe; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 8px 0; }
        .card-header h2 { font-size: 22px; font-weight: 800; margin: 0; }
        .card-body { padding: 30px; }
        .info-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 25px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .row:last-child { margin-bottom: 0; }
        .label { color: #64748b; font-weight: 600; }
        .value { color: #0f172a; font-weight: 800; }
        .price { color: #4f46e5; font-size: 22px; font-weight: 900; }
        .btn-pay { display: block; width: 100%; box-sizing: border-box; text-align: center; background-color: #4f46e5; color: #ffffff !important; font-weight: 800; font-size: 16px; padding: 18px; border-radius: 16px; text-decoration: none; shadow: 0 10px 20px rgba(79, 70, 229, 0.3); transition: background-color 0.2s; }
        .footer { text-align: center; padding: 25px; color: #64748b; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-text">
            <h1>Menunggu Pembayaran ⏳</h1>
            <p>Selesaikan pembayaran tiket Anda sebelum masa berlaku habis.</p>
        </div>

        <div class="card">
            <div class="card-header">
                <p>Instruksi Pembayaran</p>
                <h2>{{ $transaction->event->title }}</h2>
            </div>

            <div class="card-body">
                <p style="font-size: 15px; color: #334155; margin-top: 0; margin-bottom: 20px;">
                    Halo <strong>{{ $transaction->customer_name }}</strong>, pesanan tiket Anda telah berhasil dibuat. Silakan selesaikan pembayaran Anda:
                </p>

                <div class="info-box">
                    <div class="row">
                        <span class="label">Order ID</span>
                        <span class="value">{{ $transaction->order_id }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Tanggal Event</span>
                        <span class="value">{{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="row" style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #cbd5e1; align-items: center;">
                        <span class="label" style="font-size: 15px;">Total Tagihan</span>
                        <span class="price">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('checkout.payment', $transaction->order_id) }}" class="btn-pay">
                    Lanjutkan Pembayaran Sekarang &rarr;
                </a>
            </div>

            <div class="footer">
                <p>Apabila Anda sudah melakukan pembayaran, silakan abaikan email ini.</p>
                <p style="margin-top: 8px;">&copy; {{ date('Y') }} AmikomEventHub.</p>
            </div>
        </div>
    </div>
</body>
</html>
