<?php

namespace App\Modules\Telegram\Entities\Callback;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $key
 * @property string $text
 * @property string $handler
 * @property string $method
 * @property array $params
 */
class Button extends Model
{
    public $timestamps = false;

    protected $table = '_tg_buttons';

    protected $casts = ['params' => 'array'];

    public static function make(string $text): self
    {
        $entity = new self();
        $entity->key = $entity->generateKey();
        $entity->text = $text;
        return $entity;
    }

    public function withHandler(string $handler): self
    {
        $this->handler = $handler;
        $this->key = $this->generateKey();
        return $this;
    }

    public function withMethod(string $method): self
    {
        $this->method = $method;
        $this->key = $this->generateKey();
        return $this;
    }

    public function withParam(string $key, $value): self
    {
        if (empty($this->params)) {
            $this->params = [$key => $value];
        } else {
            $this->params = array_merge($this->params, [$key => $value]);
        }

        $this->key = $this->generateKey();
        return $this;
    }

    public function getParam(string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    private function generateKey(): string
    {
        return md5($this->text . $this->handler . $this->method . json_encode($this->params));
    }
}
