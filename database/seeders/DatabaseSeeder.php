<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Form;
use App\Models\Submission;
use App\Models\SubmissionHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin FSTI',
            'email' => 'admin@fsti.ac.id',
            'password' => Hash::make('password'),
        ]);

        // Create Categories for Mahasiswa
        $kategoriBebas = Category::create([
            'name' => 'Surat Keterangan',
            'description' => 'Berbagai surat keterangan untuk keperluan mahasiswa',
            'type' => 'mahasiswa',
            'icon' => '📄',
            'order' => 1,
            'is_active' => true,
        ]);

        $kategoriAkademik = Category::create([
            'name' => 'Akademik',
            'description' => 'Layanan terkait kegiatan akademik',
            'type' => 'mahasiswa',
            'icon' => '📚',
            'order' => 2,
            'is_active' => true,
        ]);

        $kategoriKeuangan = Category::create([
            'name' => 'Keuangan',
            'description' => 'Layanan terkait keuangan mahasiswa',
            'type' => 'mahasiswa',
            'icon' => '💰',
            'order' => 3,
            'is_active' => true,
        ]);

        // Create Categories for Dosen
        $kategoriDosenSurat = Category::create([
            'name' => 'Surat Tugas',
            'description' => 'Pengajuan surat tugas dosen',
            'type' => 'dosen',
            'icon' => '✈️',
            'order' => 1,
            'is_active' => true,
        ]);

        $kategoriDosenPenelitian = Category::create([
            'name' => 'Penelitian',
            'description' => 'Layanan terkait penelitian dosen',
            'type' => 'dosen',
            'icon' => '🔬',
            'order' => 2,
            'is_active' => true,
        ]);

        // Create Forms for Mahasiswa
        $formSKAktif = Form::create([
            'category_id' => $kategoriBebas->id,
            'title' => 'Surat Keterangan Aktif Kuliah',
            'slug' => 'sk-aktif-kuliah',
            'description' => 'Pengajuan surat keterangan aktif kuliah untuk berbagai keperluan',
            'schema' => [
                'fields' => [
                    ['id' => 'nama', 'type' => 'text', 'label' => 'Nama Lengkap', 'required' => true, 'placeholder' => 'Masukkan nama lengkap'],
                    ['id' => 'nim', 'type' => 'text', 'label' => 'NIM', 'required' => true, 'placeholder' => 'Masukkan NIM'],
                    ['id' => 'prodi', 'type' => 'select', 'label' => 'Program Studi', 'required' => true, 'options' => [
                        ['value' => 'ti', 'label' => 'Teknik Informatika'],
                        ['value' => 'si', 'label' => 'Sistem Informasi'],
                        ['value' => 'dkv', 'label' => 'Desain Komunikasi Visual'],
                    ]],
                    ['id' => 'semester', 'type' => 'number', 'label' => 'Semester', 'required' => true],
                    ['id' => 'keperluan', 'type' => 'textarea', 'label' => 'Keperluan', 'required' => true, 'placeholder' => 'Jelaskan keperluan surat'],
                ],
                'settings' => ['submitButtonText' => 'Ajukan Surat'],
            ],
            'is_active' => true,
        ]);

        $formSKLulus = Form::create([
            'category_id' => $kategoriBebas->id,
            'title' => 'Surat Keterangan Lulus',
            'slug' => 'sk-lulus',
            'description' => 'Pengajuan surat keterangan lulus sementara',
            'schema' => [
                'fields' => [
                    ['id' => 'nama', 'type' => 'text', 'label' => 'Nama Lengkap', 'required' => true],
                    ['id' => 'nim', 'type' => 'text', 'label' => 'NIM', 'required' => true],
                    ['id' => 'tanggal_sidang', 'type' => 'date', 'label' => 'Tanggal Sidang', 'required' => true],
                    ['id' => 'keperluan', 'type' => 'textarea', 'label' => 'Keperluan', 'required' => true],
                ],
                'settings' => ['submitButtonText' => 'Ajukan Surat'],
            ],
            'is_active' => true,
        ]);

        $formCuti = Form::create([
            'category_id' => $kategoriAkademik->id,
            'title' => 'Pengajuan Cuti Akademik',
            'slug' => 'cuti-akademik',
            'description' => 'Pengajuan cuti akademik untuk semester tertentu',
            'schema' => [
                'fields' => [
                    ['id' => 'nama', 'type' => 'text', 'label' => 'Nama Lengkap', 'required' => true],
                    ['id' => 'nim', 'type' => 'text', 'label' => 'NIM', 'required' => true],
                    ['id' => 'semester_cuti', 'type' => 'text', 'label' => 'Semester Cuti', 'required' => true],
                    ['id' => 'alasan', 'type' => 'textarea', 'label' => 'Alasan Cuti', 'required' => true],
                ],
                'settings' => ['submitButtonText' => 'Ajukan Cuti'],
            ],
            'is_active' => true,
        ]);

        $formBeasiswa = Form::create([
            'category_id' => $kategoriKeuangan->id,
            'title' => 'Pendaftaran Beasiswa',
            'slug' => 'pendaftaran-beasiswa',
            'description' => 'Pendaftaran beasiswa internal fakultas',
            'schema' => [
                'fields' => [
                    ['id' => 'nama', 'type' => 'text', 'label' => 'Nama Lengkap', 'required' => true],
                    ['id' => 'nim', 'type' => 'text', 'label' => 'NIM', 'required' => true],
                    ['id' => 'ipk', 'type' => 'number', 'label' => 'IPK Terakhir', 'required' => true],
                    ['id' => 'penghasilan_ortu', 'type' => 'number', 'label' => 'Penghasilan Orang Tua (per bulan)', 'required' => true],
                    ['id' => 'prestasi', 'type' => 'textarea', 'label' => 'Prestasi yang Dimiliki', 'required' => false],
                ],
                'settings' => ['submitButtonText' => 'Daftar Beasiswa'],
            ],
            'is_active' => true,
        ]);

        // Create Forms for Dosen
        $formSuratTugas = Form::create([
            'category_id' => $kategoriDosenSurat->id,
            'title' => 'Surat Tugas Perjalanan Dinas',
            'slug' => 'surat-tugas-dinas',
            'description' => 'Pengajuan surat tugas untuk perjalanan dinas',
            'schema' => [
                'fields' => [
                    ['id' => 'nama', 'type' => 'text', 'label' => 'Nama Dosen', 'required' => true],
                    ['id' => 'nidn', 'type' => 'text', 'label' => 'NIDN', 'required' => true],
                    ['id' => 'tujuan', 'type' => 'text', 'label' => 'Tujuan Perjalanan', 'required' => true],
                    ['id' => 'tanggal_mulai', 'type' => 'date', 'label' => 'Tanggal Mulai', 'required' => true],
                    ['id' => 'tanggal_selesai', 'type' => 'date', 'label' => 'Tanggal Selesai', 'required' => true],
                    ['id' => 'keperluan', 'type' => 'textarea', 'label' => 'Keperluan', 'required' => true],
                ],
                'settings' => ['submitButtonText' => 'Ajukan Surat Tugas'],
            ],
            'is_active' => true,
        ]);

        $formPenelitian = Form::create([
            'category_id' => $kategoriDosenPenelitian->id,
            'title' => 'Pengajuan Proposal Penelitian',
            'slug' => 'proposal-penelitian',
            'description' => 'Pengajuan proposal penelitian internal',
            'schema' => [
                'fields' => [
                    ['id' => 'nama', 'type' => 'text', 'label' => 'Nama Ketua Peneliti', 'required' => true],
                    ['id' => 'judul', 'type' => 'text', 'label' => 'Judul Penelitian', 'required' => true],
                    ['id' => 'anggaran', 'type' => 'number', 'label' => 'Estimasi Anggaran (Rp)', 'required' => true],
                    ['id' => 'abstrak', 'type' => 'textarea', 'label' => 'Abstrak Penelitian', 'required' => true],
                ],
                'settings' => ['submitButtonText' => 'Ajukan Proposal'],
            ],
            'is_active' => true,
        ]);

        // Create Submissions with various statuses spread over 30 days
        $forms = [$formSKAktif, $formSKLulus, $formCuti, $formBeasiswa, $formSuratTugas, $formPenelitian];
        $statuses = ['pending', 'in_review', 'needs_revision', 'approved', 'rejected', 'completed'];
        
        $names = ['Ahmad Fauzi', 'Budi Santoso', 'Citra Dewi', 'Dian Pratama', 'Eka Putri', 'Fajar Nugroho', 'Gita Sari', 'Hendra Wijaya', 'Indah Permata', 'Joko Susilo'];
        $nims = ['2021001', '2021002', '2021003', '2021004', '2021005', '2020001', '2020002', '2020003', '2019001', '2019002'];
        
        $submissionCount = 0;
        
        // Generate submissions for the last 30 days
        for ($day = 30; $day >= 0; $day--) {
            $date = Carbon::now()->subDays($day);
            
            // Random number of submissions per day (1-5)
            $dailySubmissions = rand(1, 5);
            
            for ($i = 0; $i < $dailySubmissions; $i++) {
                $form = $forms[array_rand($forms)];
                $nameIndex = array_rand($names);
                
                // Determine status based on age of submission
                if ($day > 20) {
                    // Older submissions are more likely to be completed
                    $status = $statuses[array_rand(['completed', 'completed', 'approved', 'rejected'])];
                } elseif ($day > 10) {
                    // Mid-age submissions have mixed statuses
                    $status = $statuses[array_rand($statuses)];
                } else {
                    // Recent submissions are more likely to be pending or in_review
                    $status = ['pending', 'pending', 'in_review', 'pending'][rand(0, 3)];
                }
                
                $submission = Submission::create([
                    'form_id' => $form->id,
                    'tracking_number' => 'FSTI-' . $date->format('Ymd') . '-' . strtoupper(substr(md5(rand()), 0, 5)),
                    'email' => strtolower(str_replace(' ', '.', $names[$nameIndex])) . '@student.ac.id',
                    'data' => [
                        'nama' => $names[$nameIndex],
                        'nim' => $nims[$nameIndex],
                        'prodi' => ['ti', 'si', 'dkv'][rand(0, 2)],
                        'semester' => rand(3, 8),
                        'keperluan' => 'Keperluan pengajuan ' . $form->title,
                    ],
                    'status' => $status,
                    'admin_notes' => $status === 'needs_revision' ? 'Mohon lengkapi dokumen pendukung' : null,
                    'created_at' => $date->copy()->addHours(rand(8, 17))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(8, 17))->addMinutes(rand(0, 59))->addDays(rand(0, min($day, 5))),
                ]);

                // Create history for non-pending submissions
                if ($status !== 'pending') {
                    SubmissionHistory::create([
                        'submission_id' => $submission->id,
                        'status' => 'pending',
                        'notes' => 'Pengajuan diterima',
                        'changed_by' => null,
                        'created_at' => $submission->created_at,
                    ]);

                    if (in_array($status, ['in_review', 'needs_revision', 'approved', 'rejected', 'completed'])) {
                        SubmissionHistory::create([
                            'submission_id' => $submission->id,
                            'status' => 'in_review',
                            'notes' => 'Sedang ditinjau oleh admin',
                            'changed_by' => $admin->id,
                            'created_at' => $submission->created_at->copy()->addHours(rand(1, 24)),
                        ]);
                    }

                    if ($status === 'needs_revision') {
                        SubmissionHistory::create([
                            'submission_id' => $submission->id,
                            'status' => 'needs_revision',
                            'notes' => 'Mohon lengkapi dokumen pendukung',
                            'changed_by' => $admin->id,
                            'created_at' => $submission->created_at->copy()->addDays(1),
                        ]);
                    }

                    if (in_array($status, ['approved', 'completed'])) {
                        SubmissionHistory::create([
                            'submission_id' => $submission->id,
                            'status' => 'approved',
                            'notes' => 'Pengajuan disetujui',
                            'changed_by' => $admin->id,
                            'created_at' => $submission->created_at->copy()->addDays(rand(1, 3)),
                        ]);
                    }

                    if ($status === 'completed') {
                        SubmissionHistory::create([
                            'submission_id' => $submission->id,
                            'status' => 'completed',
                            'notes' => 'Dokumen sudah selesai dan dapat diambil',
                            'changed_by' => $admin->id,
                            'created_at' => $submission->created_at->copy()->addDays(rand(2, 5)),
                        ]);
                    }

                    if ($status === 'rejected') {
                        SubmissionHistory::create([
                            'submission_id' => $submission->id,
                            'status' => 'rejected',
                            'notes' => 'Pengajuan ditolak karena tidak memenuhi syarat',
                            'changed_by' => $admin->id,
                            'created_at' => $submission->created_at->copy()->addDays(rand(1, 2)),
                        ]);
                    }
                }

                $submissionCount++;
            }
        }

        $this->command->info("Created {$submissionCount} submissions with various statuses");
    }
}
