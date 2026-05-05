# LikePlatform AI

Portal de Inteligencia Artificial para LikePlatform. Wrapper sobre el AI SDK nativo de Laravel 13 que agrega plantillas de prompts, tracking de uso y reportes de costos.

## Instalación

```bash
composer require likeplatform/ai
```

## Características (Fase 1)

- Completar textos usando providers configurados en Laravel
- CRUD de plantillas de prompts con placeholders `{{variable}}`
- Panel de estadísticas de uso (por día, semana, mes, usuario)
- Desglose de costos por provider/modelo

## Contratos Implementados

| Contrato | Propósito |
|----------|-----------|
| `AIProviderContract` | Generación de completions (delega al AI SDK nativo) |
| `PromptTemplateContract` | Plantillas reutilizables de prompts |
| `AIUsageTrackerContract` | Tracking y reporting de uso/costos |

## Relación con Laravel AI SDK

Este package **no reimplementa** llamadas a APIs de providers. Delega al AI SDK nativo de Laravel 13 y agrega funcionalidades específicas de LikePlatform:

- **Plantillas**: prompts parametrizables con CRUD
- **Tracking**: persistencia de métricas de uso
- **Costos**: cálculo y reportes por período

## Arquitectura

Ver [ARCHITECTURE.md](./ARCHITECTURE.md) para detalles de diseño e integración con el AI SDK.

## Versión

**0.1.0-dev** — Sprint 0: Estructura inicial del package

---

*Like Innovación — Powered by LikePlatform*
