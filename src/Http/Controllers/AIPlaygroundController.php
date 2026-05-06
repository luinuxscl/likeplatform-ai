<?php

declare(strict_types=1);

namespace LikePlatform\AI\Http\Controllers;

use Illuminate\View\View;

use Illuminate\Routing\Controller;

class AIPlaygroundController extends Controller
{
    /**
     * Show the AI playground page.
     */
    public function index(): View
    {
        return view('likeplatform-ai::playground.index');
    }
}
