<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\SupportStreamedResponse;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupController extends Controller
{
    // Menampilkan halaman backup & restore
    public function index()
    {
        // Mengambil daftar file backup yang tersimpan di storage/app/backups
        $backupDir = storage_path('app/backups');
        $files = [];

        if (file_exists($backupDir)) {
            $allFiles = scandir($backupDir, SCANDIR_SORT_DESCENDING);
            foreach ($allFiles as $file) {
                if (str_ends_with($file, '.sql')) {
                    $filePath = $backupDir . '/' . $file;
                    $files[] = [
                        'name' => $file,
                        'size' => round(filesize($filePath) / 1024, 2) . ' KB',
                        'date' => date('d F Y H:i:s', filemtime($filePath)),
                    ];
                }
            }
        }

        return view('backups.index', compact('files'));
    }

    // Proses Backup Manual
    public function backup()
    {
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');

        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $fileName = "backup-" . $dbName . "-" . date('Y-m-d_H-i-s') . ".sql";
        $filePath = $backupDir . '/' . $fileName;

        // JALUR OTOMATIS LARAGON: Menembak langsung ke file mysqldump bawaan Laragon
        // Menggunakan--password="" jika password kosong agar tidak error di Windows exec
        $mysqldumpPath = '"C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe"';

        // Jika versi MySQL Laragon Anda berbeda (misal versi 5.7), gunakan jalur alternatif di bawah ini:
        if (!file_exists(str_replace('"', '', $mysqldumpPath))) {
            $mysqldumpPath = 'mysqldump'; // fallback ke global path jika tidak ketemu
        }

        $command = sprintf(
            '%s --user="%s" %s --host="%s" "%s" > "%s"',
            $mysqldumpPath,
            $dbUser,
            $dbPass ? '--password="' . $dbPass . '"' : '',
            $dbHost,
            $dbName,
            $filePath
        );

        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return redirect()->back()->with('success', 'Database berhasil dibackup: ' . $fileName);
        } else {
            return redirect()->back()->with('error', 'Gagal melakukan backup database. Pastikan MySQL Laragon aktif.');
        }
    }

    // Proses Download File Backup
    public function download($fileName)
    {
        $filePath = storage_path('app/backups/' . $fileName);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }

    // Proses Restore Database
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required'
        ]);

        $fileName = $request->backup_file;
        $filePath = storage_path('app/backups/' . $fileName);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');

        // JALUR OTOMATIS LARAGON: Menembak langsung ke file mysql.exe bawaan Laragon
        $mysqlPath = '"C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe"';

        if (!file_exists(str_replace('"', '', $mysqlPath))) {
            $mysqlPath = 'mysql'; // fallback
        }

        $command = sprintf(
            '%s --user="%s" %s --host="%s" "%s" < "%s"',
            $mysqlPath,
            $dbUser,
            $dbPass ? '--password="' . $dbPass . '"' : '',
            $dbHost,
            $dbName,
            $filePath
        );

        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            return redirect()->back()->with('success', 'Database berhasil di-restore ke versi: ' . $fileName);
        } else {
            return redirect()->back()->with('error', 'Gagal melakukan restore database.');
        }
    }

    // Hapus File Backup
    public function destroy($fileName)
    {
        $filePath = storage_path('app/backups/' . $fileName);
        if (file_exists($filePath)) {
            unlink($filePath);
            return redirect()->back()->with('success', 'File backup berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus file.');
    }
}
