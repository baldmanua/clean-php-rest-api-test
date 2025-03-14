<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ProductDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Type('numeric')]
        public float  $price,

        #[Assert\NotBlank]
        public string $category,

        #[Assert\Type('array')]
        public array  $attributes = []
    ) {}
}