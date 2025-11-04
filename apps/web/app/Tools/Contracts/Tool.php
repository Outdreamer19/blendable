<?php

namespace App\Tools\Contracts;

interface Tool
{
    public function getName(): string;

    public function getDescription(): string;

    public function getParameters(): array;

    public function execute(array $parameters): array;
}
