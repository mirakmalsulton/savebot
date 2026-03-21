<?php

namespace App\Modules\Telegram\Jobs;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Events\UserUploadedFileEvent;
use App\Modules\Telegram\Services\Sender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;

readonly class UploadFileJob implements ShouldQueue
{
    use Dispatchable;

    public function __construct(private int $userId, private string $message, private string $fileId)
    {
    }

    public function handle(Sender $sender, FilesystemManager $storage): void
    {
        $path = $this->generateFilePath($this->fileId);

        if ($storage->exists($path)) {
            event(new UserUploadedFileEvent(
                $this->userId,
                $this->message,
                $storage->path($path)
            ));
            return;
        }

        try {
            $content = $sender->getFileContent($this->fileId);
            $storage->put($path, $content);

            event(new UserUploadedFileEvent(
                $this->userId,
                $this->message,
                $storage->path($path)
            ));
        } catch (Throwable) {
            $message = new TextMessage($this->userId, __('misc.An error occurred while uploading the file. Please try again.'), false);
            $sender->sendTextMessage($message);
        }
    }

    private function generateFilePath($fileId): string
    {
        return 'gpt/' . $this->userId . '/source_' . md5($fileId) . '.txt';
    }
}
