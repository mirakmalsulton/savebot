<?php

namespace App\Modules\Telegram\Entities\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property State $state
 * @property string $lang
 * @property int|null $balance
 */
class TgUser extends Model
{
    public $incrementing = false;
    protected $table = '_tg_users';

    protected $casts = ['state' => 'array'];

    public static function make(int $id): self
    {
        $entity = new self();
        $entity->id = $id;
        $entity->lang = 0;
        $entity->balance = 0;
        return $entity;
    }

    public function withBalance(int $amount): self
    {
        $this->balance = $amount;
        return $this;
    }

    public function withLang(string $lang): self
    {
        $this->lang = $lang;
        return $this;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function decreaseBalance(int $amount): void
    {
        $this->balance -= $amount;
        if ($this->balance < 0) {
            $this->balance = 0;
        }
        $this->save();
    }

    public function changeState(State $state): void
    {
        $this->state = $state;
        $this->save();
    }

    public function clearState(): void
    {
        $this->state = null;
        $this->save();
    }

    public function changeLang(string $lang): void
    {
        $this->lang = $lang;
        $this->save();
    }

    protected function state(): Attribute
    {
        return Attribute::make(
            get: fn(?string $state) => $state ? new State(json_decode($state, true)) : null,
            set: fn(?State $state) => $state ? json_encode($state->toArray()) : null
        );
    }
}
