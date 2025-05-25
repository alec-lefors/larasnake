<?php

declare(strict_types=1);

namespace App\Domains\Snake\Configuration;

readonly class Details
{
    public function __construct(
        public string $author,
        public HexColor $color,
        public string $head,
        public string $tail,
        public string $version,
        public string $apiVersion = "1",
    ) {
    }

    public function toArray(): array
    {
        return [
            'apiversion' => $this->apiVersion,
            'author' => $this->author,
            'color' => $this->color->hexColor,
            'head' => $this->head,
            'tail' => $this->tail,
            'version' => $this->version,
        ];
    }
}
