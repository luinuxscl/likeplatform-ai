{{-- AI Playground — Page --}}
@extends('layouts.app')

@section('title', __('likeplatform-ai::templates.playground'))

@section('breadcrumbs')
    <li class="flex items-center gap-1">
        <span class="text-gray-900 dark:text-gray-100">{{ __('likeplatform-ai::templates.playground') }}</span>
    </li>
@endsection

@section('content')
    <div class="mx-auto max-w-4xl">
        @livewire('ai-playground')
    </div>
@endsection
