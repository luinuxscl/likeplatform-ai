<?php

declare(strict_types=1);

namespace LikePlatform\AI\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use LikePlatform\AI\Models\PromptTemplate;

class PromptTemplateController extends Controller
{
    /**
     * Display a listing of prompt templates.
     */
    public function index(): View
    {
        $templates = PromptTemplate::latest()->get();

        return view('likeplatform-ai::templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new prompt template.
     */
    public function create(): View
    {
        return view('likeplatform-ai::templates.create');
    }

    /**
     * Store a newly created prompt template.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:ai_prompt_templates,key'],
            'name' => ['required', 'string', 'max:255'],
            'template' => ['required', 'string', 'max:10000'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        // Extract placeholders from template
        preg_match_all('/\{\{(\w+)\}\}/', $validated['template'], $matches);
        $placeholders = array_unique($matches[1] ?? []);

        PromptTemplate::create([
            'key' => $validated['key'],
            'name' => $validated['name'],
            'template' => $validated['template'],
            'placeholders' => $placeholders,
            'description' => $validated['description'],
        ]);

        return redirect()->route('ai.templates.index')
            ->with('success', __('likeplatform-ai::templates.created_successfully'));
    }

    /**
     * Show the form for editing the specified prompt template.
     */
    public function edit(int $id): View
    {
        $template = PromptTemplate::findOrFail($id);

        return view('likeplatform-ai::templates.edit', compact('template'));
    }

    /**
     * Update the specified prompt template.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $template = PromptTemplate::findOrFail($id);

        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:ai_prompt_templates,key,' . $id],
            'name' => ['required', 'string', 'max:255'],
            'template' => ['required', 'string', 'max:10000'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        preg_match_all('/\{\{(\w+)\}\}/', $validated['template'], $matches);
        $placeholders = array_unique($matches[1] ?? []);

        $template->update([
            'key' => $validated['key'],
            'name' => $validated['name'],
            'template' => $validated['template'],
            'placeholders' => $placeholders,
            'description' => $validated['description'],
        ]);

        return redirect()->route('ai.templates.index')
            ->with('success', __('likeplatform-ai::templates.updated_successfully'));
    }

    /**
     * Remove the specified prompt template.
     */
    public function destroy(int $id): RedirectResponse
    {
        $template = PromptTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('ai.templates.index')
            ->with('success', __('likeplatform-ai::templates.deleted_successfully'));
    }
}
