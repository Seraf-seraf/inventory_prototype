<?php

namespace App\Providers;

use App\Http\Requests\DocumentRequest;
use App\Services\DocumentService;
use Illuminate\Support\ServiceProvider;

class DocumentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(DocumentService::class, function ($app) {
            $request = $app->make(DocumentRequest::class);
            $data = $request->validated();
            return new DocumentService($data);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
