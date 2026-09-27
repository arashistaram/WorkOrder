<?php

if (! function_exists('getInitials')) {
    function getInitials(string $name, string $separator = '.', int $maxChars = 3): string
    {
        $parts = preg_split('/\s+/', trim($name));

        return collect($parts)
            ->filter()
            ->take($maxChars)
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->implode($separator);
    }
}
