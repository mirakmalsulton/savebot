<?php

namespace App\Modules\Downloader;

interface VideoProviderInterface
{
    public function supports(string $url): bool;
    public function download(string $url): string;
}
