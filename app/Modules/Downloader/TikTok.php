<?php

namespace App\Modules\Downloader;

class TikTok implements VideoProviderInterface
{
    public function supports(string $url): bool
    {
        return preg_match('/(tiktok\.com|vt\.tiktok|vm\.tiktok)/i', $url);
    }

    public function download(string $url): string
    {
        // 1. Генерируем уникальное имя файла в storage
        $fileName = 'video_' . time() . '_' . uniqid() . '.mp4';
        $tempPath = storage_path('app/public/temp/' . $fileName);

        // 2. Формируем команду для yt-dlp
        // --no-playlist: качаем только одно видео
        // -f 'best': выбираем лучшее качество (обычно mp4)
        // -o: путь сохранения
        $command = [
            'python3', '-m', 'yt_dlp',
            '--no-playlist',
            '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            '--add-header', 'Referer: https://www.tiktok.com/',
            '--add-header', 'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            '--socket-timeout', '20',
            '--no-check-certificate',
            // Заменяем "-f best" на "-f b", как просит сам yt-dlp, чтобы не было ворнингов
            '-f', 'b',
            '-o', $tempPath,
            $url
        ];

        // 3. Запускаем процесс
        $process = new \Symfony\Component\Process\Process($command);
        $process->setTimeout(120); // Даем 2 минуты на скачивание
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception("Ошибка yt-dlp: " . $process->getErrorOutput());
        }

        return $tempPath;
    }
}
