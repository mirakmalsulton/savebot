<?php

namespace App\Modules\Telegram\Handlers;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Entities\User\State;
use App\Modules\Telegram\Jobs\UploadFileJob;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Services\AuthService;
use DomainException;

class DocumentHandler extends BaseHandler
{
    public function index(array $document): void
    {
        AuthService::$user->clearState();

        try{
            $this->validateBalance();
            $this->validateFile($document);
        } catch (DomainException $e) {
            TgSendJob::dispatch(new TextMessage(AuthService::$user->id, $e->getMessage(), false));
            return;
        }

        TgSendJob::dispatch(new TextMessage(
            AuthService::$user->id,
            __('document_handler.File uploaded! Please provide further instructions.'),
            false
        ));

        $state = new State([
            'handler' => DocumentHandler::class,
            'method' => 'process',
            'params' => ['document' => $document],
        ]);

        AuthService::$user->changeState($state);
    }

    public function process(string $message): void
    {
        TgSendJob::dispatch(new TextMessage(
            AuthService::$user->id,
            __('document_handler.Processing your file... This may take a few seconds. Please wait.'),
            false
        ));

        $document = AuthService::$user->state->getParam('document');

        UploadFileJob::dispatch(AuthService::$user->id, $message, $document['file_id']);
    }

    private function validateBalance(): void
    {
        if (AuthService::$user->balance < 1) {
            throw new DomainException(__('document_handler.Not enough balance to process the file.'));
        }
    }

    private function validateFile(array $document): void
    {
        if ($document['file_size'] > 20 * 1024 * 1024) {
            throw new DomainException(__('document_handler.File is too big, max file size is 20MB.'));
        }
    }
}
