<?php 
namespace CleanArchitecture\Application\Helpers;

trait HttpStatusHelper
{
     /**
     * Success
     *
     * @param mixed $data
     * @return string|array
     */
    public function ok($data): array
    {
        return [
            'statusCode' => 200,
            'body' => $data
        ];
    }

    /**
     * Created
     *
     * @param mixed $data
     * @return string|array
     */
    public function created($data): array
    {
        return [
            'statusCode' => 201,
            'body' => $data
        ];
    }

    /**
     * Error
     *
     * @param mixed $data
     * @return string|array
     */
    public function forbidden($data): array
    {
        return [
            'statusCode' => 403,
            'body' => ["message" => $data]
        ];
    }

    /**
     * Error
     *
     * @param mixed $data
     * @return string|array
     */
    public function badRequest($data): array
    {
        return [
            'statusCode' => 400,
            'body' => ["message" => $data]
        ];
    }

    /**
     * Server Error
     *
     * @param mixed $data
     * @return string|array
     */
    public function serverError($data = null): array
    {
        return [
            'statusCode' => 500,
            'body' => $data ??= ["message" => "Server Error"]
        ];
    }
}