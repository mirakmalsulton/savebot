<?php

namespace App\Modules\Downloader;

use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class Instagram implements VideoProviderInterface
{
    public function supports(string $url): bool
    {
        return (bool) preg_match('/(instagram\.com|instagr\.am)/i', $url);
    }

    public function download(string $url): string
    {
        $fileName = 'insta_' . time() . '_' . uniqid() . '.mp4';
        $tempPath = storage_path('app/public/temp/' . $fileName);

        // Создаем папку, если её нет
        if (!file_exists(storage_path('app/public/temp'))) {
            mkdir(storage_path('app/public/temp'), 0777, true);
        }

        $command = [
            'yt-dlp',
            '--no-playlist',
            '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            '--add-header', 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            '-f', 'best[ext=mp4]',
            '-o', $tempPath,
            $url
        ];

        $process = new Process($command);
        $process->setTimeout(180); // Инста иногда отдает медленно, даем 3 минуты
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error("Instagram Download Error: " . $process->getErrorOutput());
            throw new \Exception("Не удалось скачать видео из Instagram.");
        }

        return $tempPath;
    }
}
