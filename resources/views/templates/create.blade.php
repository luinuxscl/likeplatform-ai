{{-- AI Templates — Create/Edit Form --}}
@php
    $isEdit = isset($template);
    $route = $isEdit ? route('ai.templates.update', $template->id) : route('ai.templates.store');
    $method = $isEdit ? 'PUT' : 'POST';
@endphp

@extends('layouts.app')

@section('title', $isEdit ? __('likeplatform-ai::templates.title_edit', ['name' => $template->name]) : __('likeplatform-ai::templates.title_create'))

@section('breadcrumbs')
    <li class="flex items-center gap-1">
        <a href="{{ route('ai.templates.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            {{ __('likeplatform-ai::templates.title_index') }}
        </a>
        <x-ui.icon name="chevron-right" class="size-3 text-gray-400" />
    </li>
    <li class="flex items-center gap-1">
        <span class="text-gray-900 dark:text-gray-100">{{ $isEdit ? __('likeplatform-ai::templates.title_edit', ['name' => $template->name]) : __('likeplatform-ai::templates.title_create') }}</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto max-w-2xl">
        <x-ui.card :header="$isEdit ? __('likeplatform-ai::templates.title_edit', ['name' => $template->name]) : __('likeplatform-ai::templates.create_new')">
            <form method="POST" action="{{ $route }}">
                @csrf
                @method($method)

                <div class="space-y-5">
                    <x-ui.input
                        name="key"
                        :label="__('likeplatform-ai::templates.form_key')"
                        :placeholder="__('likeplatform-ai::templates.form_key_placeholder')"
                        :value="old('key', $template->key ?? '')"
                        required
                        maxlength="255"
                        autofocus
                    />
                    <x-ui.input
                        name="name"
                        :label="__('likeplatform-ai::templates.form_name')"
                        :placeholder="__('likeplatform-ai::templates.form_name_placeholder')"
                        :value="old('name', $template->name ?? '')"
                        required
                        maxlength="255"
                    />
                    <x-ui.input
                        name="description"
                        :label="__('likeplatform-ai::templates.form_description')"
                        :value="old('description', $template->description ?? '')"
                        maxlength="500"
                    />
                    <x-ui.textarea
                        name="template"
                        :label="__('likeplatform-ai::templates.form_template')"
                        :placeholder="__('likeplatform-ai::templates.form_template_placeholder')"
                        :value="old('template', $template->template ?? '')"
                        required
                        rows="8"
                    />
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <a href="{{ route('ai.templates.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800">
                        {{ __('common.cancel') }}
                    </a>
                    <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        {{ $isEdit ? __('common.save') : __('common.create') }}
                    </button>
                </div>
            </form>
        </x-ui.card>
    </div>
@endsection
