<?php

namespace App\Helpers;

class CustomUrl
{
    public static function url(...$arguments)
    {
        $segments = $arguments;

        $isAbsolute = false;
        if ('/' == substr($segments[0], 0, 1)) {
            $segments[0] = ltrim($segments[0], '/');
            $isAbsolute = true;
        }

        if(empty($segments[0])) {
            array_shift($segments);
        }

        if(app()->getLocale() !== config('app.fallback_locale')) {
            array_unshift($segments, app()->getLocale());
        }

        return ($isAbsolute ? '/': '') . implode('/', $segments);
    }

    public static function getLocalizedCurrentPage(string $local) {
        $currentUrl = request()->path();
        $currentParams = request()->getQueryString();
        $segments = explode('/', $currentUrl);
        if(in_array($segments[0], config('app.locales'))) {
            array_shift($segments);
        }

        array_unshift($segments, $local);

        return '/'.implode('/', $segments ) . ($currentParams ? '?'.$currentParams : '');
    }
}
