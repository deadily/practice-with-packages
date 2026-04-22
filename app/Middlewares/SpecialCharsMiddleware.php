<?php

namespace Middlewares;

use Src\Request;
use function Collect\collection; 

class SpecialCharsMiddleware
{
    public function handle(Request $request): Request
    {
        $data = $request->all();

        collection($data)
            ->each(function ($value, $key) use ($request) {
                if (is_string($value)) {
                    $request->set($key, htmlspecialchars($value));
                }
            });

        return $request;
    }
}