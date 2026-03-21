<?php

namespace App\Modules\Telegram\Handlers;

use App\Modules\Telegram\Dtos\TextMessage;
use App\Modules\Telegram\Entities\Callback\Button;
use App\Modules\Telegram\Jobs\TgSendJob;
use App\Modules\Telegram\Repositories\ButtonRepository;
use App\Modules\Telegram\Services\AuthService;
use Illuminate\Support\Facades\App;

class LangHandler extends BaseHandler
{
    const string COMMAND = 'Select language';
    const array LANGUAGES = ['en' => 'EN', 'ru' => 'RU'];

    public function __construct(private readonly ButtonRepository $buttonRepository)
    {
    }

    public function index(): void
    {
        AuthService::$user->clearState();

        $message = new TextMessage(AuthService::$user->id, __('lang_handler.Select language'), true);
        $this->appendButtonsToMessage($message);
        TgSendJob::dispatch($message);
    }

    public function select(string $lang): void
    {
        if (!array_key_exists($lang, self::LANGUAGES)) {
            return;
        }

        AuthService::$user->changeLang($lang);
        App::setLocale($lang);

        $message = new TextMessage(AuthService::$user->id, __('lang_handler.Language changed'), false);

        $message->addColumnButton(0, Button::make(MainMenuHandler::command()));
        $message->addColumnButton(0, Button::make(LangHandler::command()));
        $message->addRowButton(Button::make(HelpHandler::command()));

        TgSendJob::dispatch($message);
    }

    private function appendButtonsToMessage(TextMessage $message): void
    {
        foreach (self::LANGUAGES as $key => $value) {
            $button = Button::make(__('lang_handler.' . $value))
                ->withHandler(self::command())
                ->withMethod('select')
                ->withParam('lang', $key);

            $this->buttonRepository->save($button);

            $message->addRowButton($button);
        }
    }
}
