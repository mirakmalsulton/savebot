<?php

namespace App\Modules\Downloader;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class Shorts implements VideoProviderInterface
{
    public function supports(string $url): bool
    {
        return (bool) preg_match('/(youtube\.com\/shorts|youtu\.be)/i', $url);
    }

    public function download(string $url): string
    {
        $fileName = 'shorts_' . time() . '_' . uniqid() . '.mp4';
        $tempPath = storage_path('app/public/temp/' . $fileName);

        // Создаем папку temp, если её еще нет
        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0777, true);
        }

        // путь к cookies.txt
        $cookiesPath = storage_path('app/private/cookies.txt');

        $command = [
            'yt-dlp',
            '--js-runtimes', 'node',
            '--remote-components', 'ejs:github',
            '--cookies', $cookiesPath,
            '--no-playlist',
            '--no-warnings',
            '--socket-timeout', '20',
            '-f', '136/134/18',
            '-o', $tempPath,
            $url
        ];

        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error("Shorts Download Error: " . $process->getErrorOutput());
            throw new \Exception("Не удалось скачать Shorts.");
        }

        return $tempPath;
    }
}
