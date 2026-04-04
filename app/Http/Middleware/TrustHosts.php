<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        $appDomain = preg_quote((string) config('app.domain'), '/');
        $adminDomain = preg_quote((string) config('app.admin_subdomain'), '/');

        return array_values(array_filter([
            $this->allSubdomainsOfApplicationUrl(),
            '^' . $appDomain . '$',
            '^(.+\.)?' . $appDomain . '$',
            '^' . $adminDomain . '$',
            '^(.+\.)?' . $adminDomain . '$',
        ]));
    }
}
