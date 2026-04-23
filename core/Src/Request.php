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
        return $this->body;
    }

    public function set(string $key, $value): void
    {
        $this->body[$key] = $value;
        $this->data[$key] = $value;
    }

    /**
     * Получение параметра.
     * @param string $key Ключ параметра
     * @param mixed $default Значение по умолчанию
     */
    public function get(string $key, $default = null)
    {
        return $this->body[$key] ?? $default;
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