<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class GetImagesDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Url(
            protocols: ['http', 'https'],
            normalizer: 'trim',
            requireTld: true,
        )]
        public string $url,
    ) {
    }
}