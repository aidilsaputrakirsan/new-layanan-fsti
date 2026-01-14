<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pengajuan</title>
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
            padding: 15px;
            text-align: center;
            margin: 20px 0;
        }
        .tracking-number {
            font-size: 20px;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 1px;
        }
        .status-change {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin: 25px 0;
            flex-wrap: wrap;
        }
        .status-badge {
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }
        .status-old {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .status-new {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-new.needs_revision {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-new.rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-new.completed {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .arrow {
            font-size: 24px;
            color: #9ca3af;
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
        .notes-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .notes-box h4 {
            margin: 0 0 10px 0;
            color: #374151;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Update Status Pengajuan</h1>
            <p>Layanan Administrasi FSTI</p>
        </div>

        <p>Yth. Bapak/Ibu,</p>
        
        <p>Status pengajuan Anda telah diperbarui. Berikut adalah detail perubahan:</p>

        <div class="tracking-box">
            <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 14px;">Nomor Tracking</p>
            <div class="tracking-number">{{ $trackingNumber }}</div>
        </div>

        <div class="status-change">
            <span class="status-badge status-old">{{ $oldStatus }}</span>
            <span class="arrow">→</span>
            <span class="status-badge status-new {{ $submission->status }}">{{ $newStatus }}</span>
        </div>

        <table class="info-table">
            <tr>
                <td>Layanan</td>
                <td>{{ $form->title ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Pengajuan</td>
                <td>{{ $submission->created_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Tanggal Update</td>
                <td>{{ now()->format('d F Y, H:i') }} WIB</td>
            </tr>
        </table>

        @if($notes)
        <div class="notes-box">
            <h4>Catatan dari Admin:</h4>
            <p style="margin: 0;">{{ $notes }}</p>
        </div>
        @endif

        @if($submission->status === 'needs_revision')
        <div style="background-color: #fef3c7; border: 1px solid #fcd34d; border-radius: 6px; padding: 15px; margin: 20px 0;">
            <strong>Perhatian:</strong> Pengajuan Anda memerlukan revisi. Silakan periksa catatan dari admin dan lakukan perbaikan yang diperlukan.
        </div>
        @endif

        @if($submission->status === 'completed')
        <div style="background-color: #dcfce7; border: 1px solid #86efac; border-radius: 6px; padding: 15px; margin: 20px 0;">
            <strong>Selamat!</strong> Pengajuan Anda telah selesai diproses. Silakan cek halaman tracking untuk informasi lebih lanjut.
        </div>
        @endif

        <p style="text-align: center;">
            <a href="{{ $trackingUrl }}" class="btn">Lihat Detail Pengajuan</a>
        </p>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} FSTI - Fakultas Sains dan Teknologi Informasi</p>
        </div>
    </div>
</body>
</html>
