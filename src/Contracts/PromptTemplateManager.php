<?php

declare(strict_types=1);

namespace LikePlatform\AI\Contracts;

use LikePlatform\AI\Models\PromptTemplate;
use LikePlatform\Contracts\AI\PromptTemplateContract;

/**
 * Implementation of a reusable prompt template.
 *
 * Templates contain {{placeholder}} syntax and are rendered
 * with runtime values to generate contextualized prompts.
 */
final readonly class PromptTemplateManager implements PromptTemplateContract
{
    public function __construct(
        private PromptTemplate $template,
    ) {}

    public function key(): string
    {
        return $this->template->key;
    }

    public function name(): string
    {
        return $this->template->name;
    }

    public function template(): string
    {
        return $this->template->template;
    }

    public function placeholders(): array
    {
        return $this->template->placeholders ?? [];
    }

    public function render(array $values): string
    {
        return $this->template->render($values);
    }
}
