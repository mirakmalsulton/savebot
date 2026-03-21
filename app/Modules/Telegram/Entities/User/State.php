<?php

namespace App\Modules\Telegram\Entities\User;

use Webmozart\Assert\Assert;

class State
{
    private string $handler;
    private string $method;
    private array $params;

    public function __construct(array $data)
    {
        Assert::keyExists($data, 'handler');
        Assert::keyExists($data, 'method');

        $this->handler = $data['handler'];
        $this->method = $data['method'];
        $this->params = $data['params'] ?? [];
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function addParam(string $key, string $value): void
    {
        $this->params[$key] = $value;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    public function toArray(): array
    {
        return [
            'handler' => $this->handler,
            'method' => $this->method,
            'params' => $this->params,
        ];
    }
}
