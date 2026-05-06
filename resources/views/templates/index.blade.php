{{-- AI Templates — Index --}}
@extends('layouts.app')

@section('title', __('likeplatform-ai::templates.title_index'))

@section('breadcrumbs')
    <li class="flex items-center gap-1">
        <span class="text-gray-900 dark:text-gray-100">{{ __('likeplatform-ai::templates.title_index') }}</span>
    </li>
@endsection

@section('topbar-actions')
    <a href="{{ route('ai.templates.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
        <x-ui.icon name="plus" class="size-4" />
        {{ __('likeplatform-ai::templates.create_new') }}
    </a>
@endsection

@section('content')
    <div class="mx-auto max-w-5xl">
        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center gap-3">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('likeplatform-ai::templates.title_index') }}
                    </h2>
                    @if($templates->isNotEmpty())
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            {{ $templates->count() }}
                        </span>
                    @endif
                </div>
            </x-slot:header>

            @if($templates->isEmpty())
                <div class="py-12 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('likeplatform-ai::templates.no_templates') }}
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('ai.templates.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
                            <x-ui.icon name="plus" class="size-4" />
                            {{ __('likeplatform-ai::templates.create_new') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="-mx-6 -mb-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::templates.key') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::templates.name') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::templates.placeholders') }}</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('likeplatform-ai::templates.description') }}</th>
                                <th scope="col" class="relative px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach($templates as $template)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="whitespace-nowrap px-6 py-3.5 font-mono text-sm font-medium text-primary-600 dark:text-primary-400">
                                        {{ $template->key }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $template->name }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($template->placeholders as $ph)
                                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400 font-mono">
                                                    {{ $ph }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 dark:text-gray-500">—</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ $template->description ?: '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-3.5 text-right text-sm">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('ai.templates.edit', $template->id) }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                {{ __('common.edit') }}
                                            </a>
                                            <form method="POST" action="{{ route('ai.templates.destroy', $template->id) }}" onsubmit="return confirm('{{ __('likeplatform-ai::templates.confirm_delete') }}')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                    {{ __('common.delete') }}
                                                </button>
                                            </form>
                                        </div>
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
