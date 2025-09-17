<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::tag(function (IncomingEntry $entry) {
            switch ($entry->type) {
                // ——— HTTP REQUEST ———
                case 'request':
                    $uri    = $entry->content['uri'] ?? null;
                    $status = $entry->content['response_status'] ?? null;
                    $action = $entry->content['controller_action'] ?? null;

                    return array_filter([
                        $status ? "status:{$status}" : null,
                        $uri    ? "uri:" . trim($uri, '/') : null,
                        $action ? "controller:" . class_basename($action) : null,
                    ]);

                    // ——— DATABASE QUERY ———
                case 'query':
                    $conn = $entry->content['connection'] ?? null;
                    $slow = ! empty($entry->content['slow']) ? 'slow' : null;

                    return array_filter([
                        $conn ? "db:{$conn}" : null,
                        $slow ? "query:slow" : null,
                    ]);

                    // ——— AUTHORIZATION (GATE) ———
                case 'gate':
                    $ability = $entry->content['ability'] ?? null;
                    $result  = $entry->content['result'] ?? null;

                    return $ability
                        ? ["gate:{$ability}:{$result}"]
                        : [];

                    // ——— VIEW RENDER ———
                case 'view':
                    $view = $entry->content['name'] ?? null;

                    return $view
                        ? ["view:" . str_replace('.', '_', $view)]
                        : [];

                    // ——— CACHE OPERATIONS ———
                case 'cache':
                    $type = $entry->content['type'] ?? null;
                    $key  = $entry->content['key']  ?? null;

                    return array_filter([
                        $type ? "cache:{$type}" : null,
                        $key  ? "cache_key:" . Str::limit($key, 20) : null,
                    ]);

                    // ——— HTTP CLIENT REQUEST ———
                case 'client_request':
                    $method = $entry->content['method'] ?? null;
                    $uri    = $entry->content['uri'] ?? null;
                    $status = $entry->content['response_status'] ?? null;

                    return array_filter([
                        $method ? "out:{$method}" : null,
                        $uri    ? "out_uri:" . trim(parse_url($uri, PHP_URL_PATH), '/') : null,
                        $status ? "out_status:{$status}" : null,
                    ]);

                    // ——— ELOQUENT MODEL ———
                case 'model':
                    $model  = $entry->content['model']  ?? null;
                    $action = $entry->content['action'] ?? null;

                    return $model
                        ? ["model:" . class_basename($model) . ":{$action}"]
                        : [];

                    // ——— EVENT LISTENER ———
                case 'event':
                    $event = $entry->content['name'] ?? null;

                    return $event
                        ? ["event:" . class_basename($event)]
                        : [];

                default:
                    return [];
            }
        });

        $isLocal = $this->app->environment('local');

        Telescope::filter(function (IncomingEntry $entry) use ($isLocal) {
            return $isLocal ||
                $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
