<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | RATE LIMITER EMAIL
        |--------------------------------------------------------------------------
        |
        | Mailtrap dapat menolak pengiriman jika terlalu banyak email
        | dikirim dalam waktu yang sangat berdekatan.
        |
        | Kita membatasi proses email melalui queue.
        |
        | Maksimal 20 email per menit.
        |
        */

        RateLimiter::for('mailtrap', function ($job) {
            return Limit::perMinute(20);
        });

        /*
        |--------------------------------------------------------------------------
        | GOOGLE DRIVE STORAGE
        |--------------------------------------------------------------------------
        */

        Storage::extend('google', function ($app, $config) {

            /*
            |------------------------------------------------------------------
            | Google Client
            |------------------------------------------------------------------
            */

            $client = new \Google\Client();

            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);

            $client->refreshToken($config['refreshToken']);

            /*
            |------------------------------------------------------------------
            | Google Drive Service
            |------------------------------------------------------------------
            */

            $service = new \Google\Service\Drive($client);

            /*
            |------------------------------------------------------------------
            | Google Drive Folder
            |------------------------------------------------------------------
            |
            | folderId berisi ID folder Google Drive.
            |
            | useDisplayPaths = false digunakan supaya folderId
            | diperlakukan sebagai ID folder, bukan nama/path.
            |
            */

            $folderId = $config['folderId'] ?? null;

            /*
            |------------------------------------------------------------------
            | Adapter Options
            |------------------------------------------------------------------
            */

            $options = [
                'useDisplayPaths' => false,
            ];

            /*
            |------------------------------------------------------------------
            | Shared Folder Support
            |------------------------------------------------------------------
            */

            if (!empty($config['sharedFolderId'])) {

                $options['sharedFolderId'] = $config['sharedFolderId'];

                /*
                 * Untuk Shared Drive, aktifkan team drive support
                 * jika teamDriveId tersedia.
                 */
                if (!empty($config['teamDriveId'])) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }
            }

            /*
            |------------------------------------------------------------------
            | Google Drive Adapter
            |------------------------------------------------------------------
            */

            $adapter = new GoogleDriveAdapter(
                $service,
                $folderId,
                $options
            );

            /*
            |------------------------------------------------------------------
            | Flysystem Filesystem
            |------------------------------------------------------------------
            */

            $filesystem = new Filesystem($adapter);

            /*
            |------------------------------------------------------------------
            | Laravel Filesystem Adapter
            |------------------------------------------------------------------
            */

            return new FilesystemAdapter(
                $filesystem,
                $adapter,
                $config
            );
        });

        /*
        |--------------------------------------------------------------------------
        | SLOW QUERY LOGGING
        |--------------------------------------------------------------------------
        |
        | Hanya aktif ketika APP_DEBUG=true.
        |
        | Query lebih dari 500ms akan dicatat ke log.
        |
        */

        if (config('app.debug')) {
            DB::listen(function ($query) {

                if ($query->time > 500) {

                    Log::warning('Slow query detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }
    }
}