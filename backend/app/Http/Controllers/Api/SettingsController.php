<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;

class SettingsController extends Controller
{
    /**
     * GET /api/settings
     * Returns all site settings as key-value pairs
     */
    public function index()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'settings' => $settings,
        ]);
    }
}
