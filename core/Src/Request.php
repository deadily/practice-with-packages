<?php

namespace Src;

use Error;

class Request
{
    protected array $body;
    public string $method;
    public array $headers;
    private array $attributes = [];
    private array $data = [];

    public function __construct()
    {
        $this->body = $_REQUEST;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders() ?? [];
        $this->data = array_merge($_GET, $_POST);
    }

    public function all(): array
    {
        return $this->body + $this->files();
        return $this->data;
    }

    public function set(string $key, $value):void
    {
        $this->body[$field] = $value;
        $this->data[$key] = $value;
    }

    // public function get(string $field, $default = null)
    // {
    //     return $this->body[$field] ?? $default;
    //     return $this->data[$key] ?? $default;
    // }

    public function get(string $field = '', string $key = ''){
        if(isset($field)!==''){
            return $this->body[$field] ?? null;
        }else{
            return $this->data[$key] ?? null;
        }
    }

    public function files(): array
    {
        return $_FILES;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->body)) {
            return $this->body[$key];
        }
        throw new Error('Accessing a non-existent property');
    }
    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
    public function getAttribute(string $key)
    {
        return $this->attributes[$key] ?? null;
    }
}