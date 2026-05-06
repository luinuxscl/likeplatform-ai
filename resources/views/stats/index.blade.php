{{-- AI Stats — Dashboard --}}
@extends('layouts.app')

@section('title', __('likeplatform-ai::stats.title'))

@section('breadcrumbs')
    <li class="flex items-center gap-1">
        <span class="text-gray-900 dark:text-gray-100">{{ __('likeplatform-ai::stats.title') }}</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        {{-- Period selector --}}
        <div class="flex items-center gap-2">
            @foreach(['day' => __('likeplatform-ai::stats.day'), 'week' => __('likeplatform-ai::stats.week'), 'month' => __('likeplatform-ai::stats.month'), 'year' => __('likeplatform-ai::stats.year')] as $key => $label)
                <a href="{{ route('ai.stats', ['period' => $key]) }}"
                   class="rounded-lg px-3 py-1.5 text-sm font-medium {{ $period === $key ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-3 gap-6">
            <x-ui.card>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ __('likeplatform-ai::stats.total_requests') }}
                </p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalRequests) }}</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ __('likeplatform-ai::stats.total_tokens') }}
                </p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalTokens) }}</p>
            </x-ui.card>
            <x-ui.card>
                <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    {{ __('likeplatform-ai::stats.total_cost') }}
                </p>
                <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">${{ number_format($totalCost, 4) }}</p>
            </x-ui.card>
        </div>

        {{-- Recent Logs --}}
        <x-ui.card :header="__('likeplatform-ai::stats.recent_logs')">
            @if($recentLogs->isEmpty())
                <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('likeplatform-ai::stats.no_logs') }}
                </p>
            @else
                <div class="-mx-6 -mb-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::stats.user') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::stats.provider') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::stats.model') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::stats.tokens') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::stats.cost') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::stats.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach($recentLogs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $log->user?->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->provider }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->model }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($log->tokens) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                        ${{ number_format($log->cost, 4) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.card>
    </div>
@endsection
