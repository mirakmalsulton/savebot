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

        $command = [
            'yt-dlp',
            '--no-playlist',              // Важно: не качать весь канал
            '--no-warnings',
            '--socket-timeout', '20',
            // Формат: ищем лучшее видео (mp4) + лучшее аудио (m4a) и склеиваем в mp4
            // Если не склеивается, берем просто лучшее готовое mp4
            '-f', 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/best[ext=mp4]/best',
            '-o', $tempPath,
            $url
        ];

        $process = new Process($command);
        $process->setTimeout(300); // Даем до 5 минут (YouTube может быть тяжелым)
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error("Shorts Download Error: " . $process->getErrorOutput());
            throw new \Exception("Не удалось скачать Shorts.");
        }

        return $tempPath;
    }
}
