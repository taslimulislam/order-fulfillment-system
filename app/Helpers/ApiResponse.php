<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace App\Helpers;

class ApiResponse
{
    /**
     * Success response.
     *
     * @param mixed $data Response payload.
     * @param string $message Success message.
     * @param int $status HTTP status code.
     * @return \Illuminate\Http\JsonResponse JSON success response.
     */
    public static function success($data = [], $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Error response.
     *
     * @param string $message Error message.
     * @param int $status HTTP status code.
     * @param array $errors Optional validation or error details.
     * @return \Illuminate\Http\JsonResponse JSON error response.
     */
    public static function error($message = 'Error', $status = 500, $errors = [])
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
