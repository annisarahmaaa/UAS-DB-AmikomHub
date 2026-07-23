<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Certificate</title>
    <style>
        @page {
            margin: 0; /* Menghilangkan margin bawaan PDF agar border bisa full page */
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #fcfbf9; /* Warna kertas sertifikat */
            color: #2c3e50;
        }
        /* Border Emas Luar */
        .border-outer {
            position: absolute;
            top: 20px;
            bottom: 20px;
            left: 20px;
            right: 20px;
            border: 15px solid #b8860b; /* Warna Emas Gelap */
            border-radius: 10px;
        }
        /* Border Emas Dalam */
        .border-inner {
            position: absolute;
            top: 40px;
            bottom: 40px;
            left: 40px;
            right: 40px;
            border: 4px double #daa520; /* Warna Emas Terang */
            border-radius: 5px;
            padding: 50px;
            text-align: center;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #b8860b;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
        }
        
        .title {
            font-size: 58px;
            font-weight: normal;
            color: #2c3e50;
            margin: 10px 0;
            letter-spacing: 5px;
            font-style: italic;
            border-bottom: 1px solid #b8860b;
            display: inline-block;
            padding-bottom: 10px;
        }

        .subtitle {
            font-size: 18px;
            font-family: 'Arial', sans-serif;
            color: #7f8c8d;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 50px;
        }

        .presented-text {
            font-size: 18px;
            font-style: italic;
            color: #34495e;
            margin-bottom: 15px;
        }

        .name {
            font-size: 50px;
            font-weight: bold;
            color: #b8860b; /* Emas */
            margin-bottom: 30px;
            text-transform: capitalize;
            font-style: italic;
            padding-bottom: 5px;
        }

        .name-underline {
            width: 60%;
            margin: 0 auto;
            border-bottom: 2px solid #b8860b;
            margin-bottom: 30px;
        }

        .reason {
            font-size: 18px;
            color: #34495e;
            line-height: 1.6;
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .event-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 15px 0;
            font-family: 'Arial', sans-serif;
        }

        .date-location {
            font-size: 16px;
            color: #7f8c8d;
            font-style: italic;
        }

        /* Tanda Tangan */
        .signatures {
            position: absolute;
            bottom: 60px;
            left: 80px;
            right: 80px;
            text-align: center;
        }

        .sign-left {
            float: left;
            width: 250px;
        }

        .sign-right {
            float: right;
            width: 250px;
        }

        .sign-line {
            border-bottom: 1px solid #2c3e50;
            margin-top: 60px; /* Jarak untuk ruang tanda tangan/stempel */
            margin-bottom: 10px;
        }

        .sign-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .sign-title {
            font-size: 14px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        .badge {
            position: absolute;
            bottom: 50px;
            left: 50%;
            margin-left: -50px; /* Setengah dari width */
            width: 100px;
            height: 100px;
            background-color: #b8860b;
            border-radius: 50%;
            border: 4px dashed #fff;
            box-shadow: 0 0 0 4px #b8860b;
            color: white;
            text-align: center;
            line-height: 100px;
            font-weight: bold;
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="border-outer"></div>
    <div class="border-inner">
        <div class="logo-text">AmikomEventHub</div>
        
        <div class="title">Certificate</div>
        <div class="subtitle">of Attendance</div>
        
        <div class="presented-text">This is proudly presented to</div>
        
        <div class="name">{{ $transaction->customer_name }}</div>
        <div class="name-underline"></div>
        
        <div class="reason">
            For outstanding participation and successful attendance at the
            <div class="event-title">"{{ $transaction->event->title }}"</div>
            event, demonstrating enthusiasm and commitment.
        </div>
        
        <div class="date-location">
            Held on {{ \Carbon\Carbon::parse($transaction->event->date)->format('F d, Y') }} at {{ $transaction->event->location }}
        </div>
        
        <!-- Tanda Tangan -->
        <div class="signatures">
            <div class="sign-left">
                <div class="sign-line"></div>
                <div class="sign-name">Event Organizer</div>
                <div class="sign-title">Authorized Signature</div>
            </div>
            
            <!-- Elemen Stempel/Medali di Tengah Bawah -->
            <div class="badge">OFFICIAL</div>
            
            <div class="sign-right">
                <div class="sign-line"></div>
                <div class="sign-name">{{ \Carbon\Carbon::parse($transaction->event->date)->format('F d, Y') }}</div>
                <div class="sign-title">Date</div>
            </div>
            
            <!-- Clearfix untuk float -->
            <div style="clear: both;"></div>
        </div>
    </div>
</body>
</html>
