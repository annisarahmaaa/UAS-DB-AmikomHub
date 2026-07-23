<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Certificate - AmikomEventHub</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px; color: #334155; }
        .container { max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #4f46e5; padding: 30px 20px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .body { padding: 30px; text-align: center; }
        .body p { line-height: 1.6; margin-bottom: 20px; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Terima Kasih Atas Partisipasi Anda!</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $transaction->customer_name }}</strong>,</p>
            <p>Terima kasih telah hadir dan berpartisipasi dalam acara <strong>{{ $transaction->event->title }}</strong>.</p>
            <p>Sebagai bentuk apresiasi kami atas kehadiran Anda, kami lampirkan E-Certificate resmi pada email ini.</p>
            <p>Semoga ilmu dan pengalaman yang didapatkan bermanfaat. Sampai jumpa di acara selanjutnya!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} AmikomEventHub. All rights reserved.
        </div>
    </div>
</body>
</html>
