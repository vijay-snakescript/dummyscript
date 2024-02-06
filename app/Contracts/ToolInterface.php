<?php

namespace App\Contracts;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

interface ToolInterface
{
    /**
     * Get requirements of tool.
     *
     * @param Request $request
     *
     * @return bool
     */
    // public function validate(Request $request);

    /**
     * Get requirements of tool.
     *
     * @param Request $request
     *
     * @return View
     */
    public function render(Request $request, Tool $tool);

    /**
     * Get requirements of tool.
     *
     * @param Request $request
     *
     * @return View
     */
    public function handle(Request $request, Tool $tool);
}
