<?php

declare(strict_types=1);

namespace LikePlatform\AI\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AI prompt template with placeholder support.
 *
 * Templates use {{placeholder}} syntax and are rendered
 * at runtime with contextual values.
 */
class PromptTemplate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'key',
        'name',
        'template',
        'placeholders',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'placeholders' => 'array',
        ];
    }

    /**
     * Render the template by replacing placeholders with values.
     *
     * @param array<string, string> $values Map of placeholder => value
     */
    public function render(array $values): string
    {
        $rendered = $this->template;

        foreach ($values as $key => $value) {
            $rendered = str_replace('{{' . $key . '}}', $value, $rendered);
        }

        return $rendered;
    }

    /**
     * Get missing placeholders for the given values.
     *
     * @param array<string, string> $values
     * @return array<string>
     */
    public function getMissingPlaceholders(array $values): array
    {
        return array_diff($this->placeholders ?? [], array_keys($values));
    }
}
