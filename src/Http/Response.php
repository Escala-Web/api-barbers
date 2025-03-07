<?php

namespace ApiBarbers\Http;

class Response
{
    private int $statusCode;
    private string $body;

    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    public function send(): void
    {
        http_response_code($this->statusCode);
        echo $this->body;
    }
}
?>