<?php
namespace App\LLM\Contracts;

interface LlmClient
{
    /** @return \Generator<string> streaming text chunks */
    public function stream(array $messages, array $options = []): \Generator;

    /** @return array{content:string, tokens_in:int, tokens_out:int} */
    public function complete(array $messages, array $options = []): array;

    /** @return array{embedding:array<float>} */
    public function embeddings(string $text, array $options = []): array;
}
