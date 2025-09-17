<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider; 
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityRequirement;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Routing\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\Eloquent\EloquentUserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar el esquema HTTPS
        URL::forceScheme('https');

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->components->securitySchemes['bearer'] = SecurityScheme::http('bearer');

                $openApi->security[] = new SecurityRequirement([
                    'bearer' => [],
                ]);
            })
            ->routes(function (Route $route) {
                return Str::startsWith($route->uri(), 'api/v1/');
            })
            ->expose(
                ui: '/docs/v1/api',
                document: '/docs/v1/openapi.json',
            );

        Scramble::registerApi('v2', [
            'info' => ['version' => '2.0.0'],
            'description' => 'API documentation for the Chinmex application',
            'servers' => [
                'Live Server' => 'api/v2',
            ],
        ])
            ->routes(function (Route $route) {
                return Str::startsWith($route->uri(), 'api/v2/');
            })
            ->expose(
                ui: '/docs/v2/api',
                document: '/docs/v2/openapi.json',
            );
    }
}
