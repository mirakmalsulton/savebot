<?php

namespace App\Modules\Downloader;

class Manager
{
    public function __construct(protected array $strategyClasses)
    {
    }

    public function getProvider(string $url): ?VideoProviderInterface
    {
        foreach ($this->strategyClasses as $class) {
            $instance = resolve($class);

            if ($instance->supports($url)) {
                return $instance;
            }
        }

        return null;
    }
}
