<?php

namespace App\Http\Controllers;

use App\Models\Upload;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleDriveDocumentController extends Controller
{
    /**
     * Menentukan konfigurasi Google Drive berdasarkan
     * tipe dokumen.
     */
    private function getDriveConfig(Upload $upload): ?array
    {
        return match ($upload->type) {

            'daftar_ulang' => [
                'folderId' => env('GOOGLE_DRIVE_FOLDER_DAFTAR_ULANG'),
            ],

            'pembayaran' => [
                'folderId' => env('GOOGLE_DRIVE_FOLDER_PEMBAYARAN'),
            ],

            'lomba' => [
                'folderId' => env('GOOGLE_DRIVE_FOLDER_KARYA'),
            ],

            default => null,
        };
    }


    /**
     * Membuat Google Drive Service.
     */
    private function getGoogleDriveService(): Drive
    {
        $client = new Client();

        $client->setClientId(
            env('GOOGLE_DRIVE_CLIENT_ID')
        );

        $client->setClientSecret(
            env('GOOGLE_DRIVE_CLIENT_SECRET')
        );

        $client->refreshToken(
            env('GOOGLE_DRIVE_REFRESH_TOKEN')
        );

        /*
         * Pastikan access token tersedia.
         */
        if (!$client->getAccessToken()) {
            throw new \RuntimeException(
                'Google Drive access token tidak tersedia.'
            );
        }

        return new Drive($client);
    }


    /**
     * User melihat dokumen.
     *
     * User hanya boleh melihat dokumen
     * milik unit yang sedang login.
     */
    public function userView(Upload $upload)
    {
        $unitId = session('unit_id');

        /*
         * Pastikan user sudah login.
         */
        if (!$unitId) {

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu.'
                );
        }


        /*
         * Pastikan dokumen milik unit
         * yang sedang login.
         */
        if ((int) $upload->unit_id !== (int) $unitId) {

            abort(
                403,
                'Anda tidak memiliki akses ke dokumen ini.'
            );
        }


        return $this->streamFromGoogleDrive($upload);
    }


    /**
     * Admin melihat dokumen.
     */
    public function adminView(Upload $upload)
    {
        return $this->streamFromGoogleDrive($upload);
    }


    /**
     * Mengambil file langsung dari Google Drive API.
     */
    private function streamFromGoogleDrive(Upload $upload)
    {
        /*
         * =========================================================
         * 1. VALIDASI FILE PATH
         * =========================================================
         */

        if (empty($upload->file_path)) {

            abort(
                404,
                'File dokumen tidak tersedia.'
            );
        }


        /*
         * =========================================================
         * 2. CEK PATH TRAVERSAL
         * =========================================================
         */
        if (!preg_match('/^[a-zA-Z0-9_\-\s\.\(\)]+$/', $upload->file_path)) {

            Log::warning(
                'Path traversal attempt detected.',
                [
                    'upload_id' => $upload->id,
                    'file_path' => $upload->file_path,
                    'unit_id'   => $upload->unit_id,
                ]
            );

            abort(
                400,
                'Nama file tidak valid.'
            );
        }


        /*
         * =========================================================
         * 3. AMBIL KONFIGURASI BERDASARKAN TIPE
         * =========================================================
         */

        $config = $this->getDriveConfig($upload);

        if (!$config) {

            abort(
                404,
                'Tipe dokumen tidak dikenali.'
            );
        }


        $folderId = $config['folderId'] ?? null;

        if (empty($folderId)) {

            abort(
                500,
                'Folder Google Drive belum dikonfigurasi.'
            );
        }


        /*
         * =========================================================
         * 4. GOOGLE DRIVE SERVICE
         * =========================================================
         */

        try {

            $service = $this->getGoogleDriveService();

        } catch (\Throwable $e) {

            Log::error(
                'Google Drive authentication error.',
                [
                    'upload_id' => $upload->id,
                    'message'   => $e->getMessage(),
                ]
            );

            abort(
                500,
                'Gagal terhubung ke Google Drive.'
            );
        }


        /*
         * =========================================================
         * 5. NAMA FILE
         * =========================================================
         */

        $fileName = basename(
            $upload->file_path
        );


        /*
         * =========================================================
         * 6. CARI FILE DI FOLDER GOOGLE DRIVE
         *    PRIORITAS:
         *    1) Cek drive_file_id di database (cache permanen)
         *    2) Cek Laravel Cache (cache sementara)
         *    3) Query API Google Drive
         * =========================================================
         */

        $driveFile = null;
        $driveFileId = null;

        // ---------------------------------------------------------
        // 6a. CEK DARI DATABASE (drive_file_id)
        // ---------------------------------------------------------
        if (!empty($upload->drive_file_id)) {

            try {

                $driveFile = $service->files->get(
                    $upload->drive_file_id,
                    [
                        'fields' => 'id,name,mimeType,size,parents,webViewLink,webContentLink'
                    ]
                );

                $driveFileId = $upload->drive_file_id;

            } catch (\Throwable $e) {

                // Jika file tidak ditemukan (mungkin sudah dihapus), hapus dari database
                Log::warning(
                    'Database drive_file_id not found in Google Drive, clearing.',
                    [
                        'upload_id' => $upload->id,
                        'drive_file_id' => $upload->drive_file_id,
                    ]
                );

                $upload->update(['drive_file_id' => null]);

                // Lanjut ke metode pencarian berikutnya
                $driveFile = null;
                $driveFileId = null;
            }
        }


        // ---------------------------------------------------------
        // 6b. JIKA TIDAK DITEMUKAN DI DATABASE, CEK LARAVEL CACHE
        // ---------------------------------------------------------
        if (!$driveFileId || !$driveFile) {

            $cacheKey = 'google_drive_file_id_' . $upload->id;

            $cachedFileId = Cache::get($cacheKey);

            if ($cachedFileId) {

                try {

                    $driveFile = $service->files->get(
                        $cachedFileId,
                        [
                            'fields' => 'id,name,mimeType,size,parents,webViewLink,webContentLink'
                        ]
                    );

                    $driveFileId = $cachedFileId;

                    // Simpan ke database agar tidak perlu cache lagi nanti
                    if (!$upload->drive_file_id) {
                        $upload->update(['drive_file_id' => $cachedFileId]);
                    }

                } catch (\Throwable $e) {

                    // Jika file tidak ditemukan, hapus cache
                    Log::warning(
                        'Cached drive_file_id not found in Google Drive, clearing cache.',
                        [
                            'upload_id' => $upload->id,
                            'drive_file_id' => $cachedFileId,
                        ]
                    );

                    Cache::forget($cacheKey);

                    $driveFile = null;
                    $driveFileId = null;
                }
            }
        }


        // ---------------------------------------------------------
        // 6c. JIKA TIDAK DITEMUKAN, QUERY API GOOGLE DRIVE
        // ---------------------------------------------------------
        if (!$driveFileId || !$driveFile) {

            try {

                /*
                 * Escape karakter yang memiliki arti khusus
                 * dalam Google Drive query.
                 */
                $escapedFileName = str_replace(
                    [
                        '\\',
                        "'",
                    ],
                    [
                        '\\\\',
                        "\\'",
                    ],
                    $fileName
                );


                $escapedFolderId = str_replace(
                    [
                        '\\',
                        "'",
                    ],
                    [
                        '\\\\',
                        "\\'",
                    ],
                    $folderId
                );


                $query =
                    "name = '" . $escapedFileName . "'" .
                    " and '" . $escapedFolderId . "' in parents" .
                    " and trashed = false";


                $response = $service->files->listFiles([
                    'q' => $query,

                    'pageSize' => 10,

                    'fields' =>
                        'files(id,name,mimeType,size,parents,webViewLink,webContentLink),nextPageToken',

                    'spaces' => 'drive',
                ]);


                $files = $response->getFiles();

            } catch (\Throwable $e) {

                Log::error(
                    'Google Drive file search error.',
                    [
                        'upload_id' => $upload->id,
                        'file_path' => $upload->file_path,
                        'folder_id' => $folderId,
                        'message'   => $e->getMessage(),
                    ]
                );

                abort(
                    500,
                    'Gagal mencari dokumen di Google Drive.'
                );
            }


            /*
             * ========================================================
             * FILE TIDAK DITEMUKAN
             * ========================================================
             */

            if (empty($files)) {

                Log::warning(
                    'Google Drive file not found.',
                    [
                        'upload_id' => $upload->id,
                        'file_path' => $fileName,
                        'folder_id' => $folderId,
                    ]
                );

                abort(
                    404,
                    'File tidak ditemukan di folder Google Drive.'
                );
            }


            /*
             * ========================================================
             * AMBIL FILE PERTAMA
             * ========================================================
             */

            /** @var DriveFile $driveFile */
            $driveFile = $files[0];

            $driveFileId = $driveFile->getId();


            if (!$driveFileId) {

                abort(
                    404,
                    'ID file Google Drive tidak ditemukan.'
                );
            }


            // =========================================================
            // SIMPAN KE DATABASE DAN CACHE
            // =========================================================
            // Simpan ke database untuk cache permanen
            if (!$upload->drive_file_id) {
                $upload->update(['drive_file_id' => $driveFileId]);
            }

            // Simpan ke Laravel Cache selama 1 jam
            Cache::put('google_drive_file_id_' . $upload->id, $driveFileId, 3600);
        }


        /*
         * =========================================================
         * 7. MIME TYPE
         * =========================================================
         */

        $mimeType = $driveFile->getMimeType();

        if (!$mimeType) {

            $mimeType = $this->getMimeTypeFromExtension(
                $fileName
            );
        }


        /*
         * =========================================================
         * 8. UKURAN FILE
         * =========================================================
         */

        $fileSize = $driveFile->getSize();


        /*
         * =========================================================
         * 9. DOWNLOAD FILE DARI GOOGLE DRIVE
         * =========================================================
         */

        try {

            $response = $service->files->get(
                $driveFileId,
                [
                    'alt' => 'media',
                ]
            );

            $body = $response->getBody();

        } catch (\Throwable $e) {

            Log::error(
                'Google Drive download error.',
                [
                    'upload_id'     => $upload->id,
                    'file_path'     => $fileName,
                    'drive_file_id' => $driveFileId,
                    'message'       => $e->getMessage(),
                ]
            );

            abort(
                500,
                'Gagal mengambil dokumen dari Google Drive.'
            );
        }


        /*
         * =========================================================
         * 10. VALIDASI BODY
         * =========================================================
         */

        if (!$body) {

            abort(
                404,
                'Isi file tidak dapat dibaca dari Google Drive.'
            );
        }


        /*
         * =========================================================
         * 11. RESPONSE HEADER
         * =========================================================
         */

        $headers = [

            'Content-Type' => $mimeType,

            'Content-Disposition' =>
                'inline; filename="' .
                addslashes($fileName) .
                '"',

            'Cache-Control' =>
                'private, no-store, no-cache, must-revalidate',

            'Pragma' => 'no-cache',

            'Expires' => '0',

            'X-Content-Type-Options' =>
                'nosniff',
        ];


        /*
         * Tambahkan Content-Length jika Google Drive
         * memberikan ukuran file.
         */
        if ($fileSize !== null) {

            $headers['Content-Length'] =
                (string) $fileSize;
        }


        /*
         * =========================================================
         * 12. STREAM KE BROWSER
         * =========================================================
         */

        return response()->stream(
            function () use ($body) {

                while (!$body->eof()) {

                    echo $body->read(8192);

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }

                    flush();
                }
            },

            200,

            $headers
        );
    }


    /**
     * Menentukan MIME type berdasarkan ekstensi.
     */
    private function getMimeTypeFromExtension(
        string $fileName
    ): string {

        $extension = strtolower(
            pathinfo(
                $fileName,
                PATHINFO_EXTENSION
            )
        );


        return match ($extension) {

            'pdf' =>
                'application/pdf',

            'jpg',
            'jpeg' =>
                'image/jpeg',

            'png' =>
                'image/png',

            'gif' =>
                'image/gif',

            'webp' =>
                'image/webp',

            'mp3' =>
                'audio/mpeg',

            'wav' =>
                'audio/wav',

            'ogg' =>
                'audio/ogg',

            default =>
                'application/octet-stream',
        };
    }
}