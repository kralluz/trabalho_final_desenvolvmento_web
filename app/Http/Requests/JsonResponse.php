<?php
class JsonResponse
{
    protected array $data;
    protected int   $status;

    public function __construct(array $data, int $status = 200)
    {
        $this->data   = $data;
        $this->status = $status;
    }

    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->data);
        exit;
    }
}
