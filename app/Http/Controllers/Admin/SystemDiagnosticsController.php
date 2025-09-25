<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class SystemDiagnosticsController extends Controller
{
    public function index()
    {
        $diagnostics = $this->runDiagnostics();
        return view('admin.diagnostics', compact('diagnostics'));
    }

    public function fixStorage()
    {
        try {
            // Run the storage link command
            Artisan::call('storage:link-force', ['--force' => true]);
            
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Storage link fixed successfully',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fix storage link: ' . $e->getMessage()
            ]);
        }
    }

    private function runDiagnostics()
    {
        $publicStoragePath = public_path('storage');
        $storageAppPublicPath = storage_path('app/public');
        
        return [
            'storage' => [
                'public_storage_exists' => file_exists($publicStoragePath),
                'public_storage_is_link' => is_link($publicStoragePath),
                'public_storage_is_directory' => is_dir($publicStoragePath),
                'app_public_exists' => is_dir($storageAppPublicPath),
                'app_public_writable' => is_writable($storageAppPublicPath),
                'public_storage_path' => $publicStoragePath,
                'app_public_path' => $storageAppPublicPath,
            ],
            'urls' => [
                'app_url' => config('app.url'),
                'asset_url' => asset(''),
                'storage_url' => asset('storage/'),
                'test_image_url' => formatImageUrl('test.jpg'),
            ],
            'directories' => [
                'subcategories' => is_dir($storageAppPublicPath . '/subcategories'),
                'products' => is_dir($storageAppPublicPath . '/products'),
                'categories' => is_dir($storageAppPublicPath . '/categories'),
            ],
            'permissions' => [
                'storage_writable' => is_writable(storage_path()),
                'public_writable' => is_writable(public_path()),
                'storage_app_writable' => is_writable(storage_path('app')),
            ],
            'environment' => [
                'app_env' => config('app.env'),
                'app_debug' => config('app.debug'),
                'php_version' => phpversion(),
                'laravel_version' => app()->version(),
            ]
        ];
    }
}
