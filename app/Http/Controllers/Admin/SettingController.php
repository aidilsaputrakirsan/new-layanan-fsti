<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings page.
     */
    public function index(): Response
    {
        $settings = $this->settingService->getAllGrouped();

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update application settings.
     */
    public function updateApplication(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
        ]);

        $this->settingService->setMultiple($validated);

        return back()->with('success', 'Pengaturan aplikasi berhasil disimpan.');
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_from_name' => 'nullable|string|max:255',
            'mail_from_address' => 'nullable|email|max:255',
        ]);

        $this->settingService->setMultiple($validated);

        return back()->with('success', 'Pengaturan email berhasil disimpan.');
    }

    /**
     * Update file upload settings.
     */
    public function updateFileUpload(Request $request)
    {
        $validated = $request->validate([
            'max_file_size' => 'required|integer|min:100|max:51200', // 100KB - 50MB
            'allowed_file_types' => 'required|string',
        ]);

        $this->settingService->setMultiple($validated);

        return back()->with('success', 'Pengaturan upload file berhasil disimpan.');
    }
}
