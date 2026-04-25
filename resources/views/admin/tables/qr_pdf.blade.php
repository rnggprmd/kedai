<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
            text-align: center;
        }
        .container {
            border: 1px solid #e2e8f0;
            border-radius: 40px;
            padding: 30px;
            position: relative;
        }
        .header-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: #3C096C;
        }
        .qr-wrapper {
            background-color: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 30px;
            padding: 30px;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .qr-image {
            width: 250px;
            height: 250px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
        }
        .table-name {
            font-size: 40px;
            font-weight: 900;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -1px;
        }
        .divider {
            height: 1px;
            background-color: #f1f5f9;
            width: 80%;
            margin: 20px auto;
        }
        .scan-text {
            font-size: 12px;
            font-weight: bold;
            color: #9D4EDD;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 20px;
        }
        .brand-text {
            font-size: 10px;
            color: #94a3b8;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-bar"></div>
        
        <div class="qr-wrapper">
            @php
                $qrUrl = "https://quickchart.io/qr?text=" . urlencode(route('customer.menu', $table->qr_token)) . "&size=350&margin=1&ecLevel=H";
                // DomPDF works better with base64 images if they are external
                $qrData = base64_encode(file_get_contents($qrUrl));
            @endphp
            <img src="data:image/png;base64,{{ $qrData }}" class="qr-image">
        </div>

        <div class="table-name">{{ $table->nama_meja }}</div>
        
        <div class="scan-text">Scan To Order</div>
        
        <div class="divider"></div>
        
        <div class="brand-text">KedaiPOS Digital Menu</div>
    </div>
</body>
</html>
