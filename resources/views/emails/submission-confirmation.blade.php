<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengajuan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #2563eb;
            margin: 0;
            font-size: 24px;
        }
        .tracking-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .tracking-number {
            font-size: 24px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 1px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table td:first-child {
            font-weight: 600;
            color: #6b7280;
            width: 40%;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #1d4ed8;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .note {
            background-color: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Konfirmasi Pengajuan</h1>
            <p>Layanan Administrasi FSTI</p>
        </div>

        <p>Yth. Bapak/Ibu,</p>
        
        <p>Pengajuan Anda telah berhasil diterima. Berikut adalah detail pengajuan Anda:</p>

        <div class="tracking-box">
            <p style="margin: 0 0 10px 0; color: #6b7280;">Nomor Tracking</p>
            <div class="tracking-number">{{ $trackingNumber }}</div>
        </div>

        <table class="info-table">
            <tr>
                <td>Layanan</td>
                <td>{{ $form->title ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $submission->email }}</td>
            </tr>
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>{{ $submission->created_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Status</td>
                <td><strong>{{ $submission->status_label }}</strong></td>
            </tr>
        </table>

        <div class="note">
            <strong>Penting:</strong> Simpan nomor tracking di atas untuk memantau status pengajuan Anda.
        </div>

        <p style="text-align: center;">
            <a href="{{ $trackingUrl }}" class="btn">Cek Status Pengajuan</a>
        </p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} FSTI - Fakultas Sains dan Teknologi Informasi</p>
        </div>
    </div>
</body>
</html>
