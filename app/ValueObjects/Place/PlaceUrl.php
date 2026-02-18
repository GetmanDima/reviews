<?php

declare(strict_types=1);

namespace App\ValueObjects\Place;

use InvalidArgumentException;

class PlaceUrl
{
    public const PATTERN = 'https:\/\/yandex\.ru\/maps\/org\/([^\/]+\/\d+)\/reviews\/?';

    public function __construct(
        public readonly string $value,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (!preg_match('/^'.self::PATTERN.'$/', $this->value)) {
            throw new InvalidArgumentException('Invalid Yandex Maps URL format.');
        }
    }

    public function getMapId(): string
    {
        $pattern = '/'.self::PATTERN.'/';
        preg_match($pattern, $this->value, $matches);

        return $matches[1] ?? '';
    }
}
