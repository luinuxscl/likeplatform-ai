# Arquitectura — likeplatform/ai

## Propósito

Portal de IA para LikePlatform que envuelve el AI SDK nativo de Laravel 13 agregando funcionalidades específicas: plantillas de prompts, tracking de uso y reporting de costos.

## Estructura

```
likeplatform-ai/
├── src/
│   ├── Providers/AIServiceProvider.php
│   ├── Http/Controllers/     ← Controllers para gestión de prompts y panel
│   └── Models/               ← Modelos: PromptTemplate, AIUsageLog
├── routes/ai.php
├── database/migrations/      ← Migraciones para tablas de AI
├── config/ai.php
├── lang/en/ y lang/es/
└── tests/
```

## Relación con Laravel AI SDK Nativo

```
[likeplatform-ai]
       ↓ wraps
[Laravel AI SDK (nativo L13)]
       ↓ calls
[Providers: OpenAI, Anthropic, Gemini, ...]
```

**Regla**: NUNCA reimplementar llamadas directas a APIs de providers. Usar siempre el AI SDK nativo.

```php
// ✅ Correcto
$response = AI::provider('openai')->complete($prompt, $options);

// ❌ Incorrecto
$response = Http::withToken($key)->post('https://api.openai.com/...');
```

## Contratos Implementados

### AIProviderContract

Delega al AI SDK nativo para generación de completions:

- `complete(string $prompt, array $options): string`
- `getModels(): array`
- `calculateCost(string $model, int $inputTokens, int $outputTokens): float`
- `getProviderName(): string`

### PromptTemplateContract

CRUD de plantillas parametrizables con `{{placeholder}}`:

- `key(): string`
- `name(): string`
- `template(): string`
- `placeholders(): array`
- `render(array $values): string`

### AIUsageTrackerContract

Persistencia de métricas de uso:

- `track(string $provider, string $model, int $tokens, float $cost): void`
- `getUsage(string $period): array`

## Funcionalidades Objetivo (Fase 1)

### 1. Completions
- Usar providers configurados en `config/ai.php` de Laravel
- Opciones: model, temperature, max_tokens, etc.

### 2. Plantillas de Prompts
- CRUD de plantillas con placeholders
- Uso: `{{user_name}}`, `{{context}}`, etc.
- Renderizado: reemplazar placeholders con valores

### 3. Panel de Estadísticas
- Uso por período: día, semana, mes, año
- Desglose por usuario y provider/model
- Gráficos de tendencias

### 4. Costos
- Cálculo automático basado en precios por token de cada modelo
- Reportes por período
- Alertas de presupuesto (futuro)

## Registro en el Core

```php
// En el ServiceProvider, Fase 1:
$this->app->bind(AIProviderContract::class, AIProvider::class);
$this->app->bind(PromptTemplateContract::class, PromptTemplateManager::class);
$this->app->bind(AIUsageTrackerContract::class, AIUsageTracker::class);
```

## Estado Actual

**Sprint 0** — Estructura del package y ServiceProvider creados. Sin lógica de negocio.

---

*Like Innovación — Powered by LikePlatform*
