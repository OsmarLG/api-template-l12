<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class ApiController extends Controller
{
    /**
     * Handle API request with standardized response format
     *
     * @param callable $callback
     * @param string $successMessage
     * @param int $successStatusCode
     * @return JsonResponse
     */
    protected function handleRequest(callable $callback, string $successMessage = 'Operación exitosa', int $successStatusCode = 200): JsonResponse
    {
        try {
            $result = $callback();
            
            return $this->formatResponse($result, $successMessage, $successStatusCode);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [
                    config('app.debug') ? $e->getTraceAsString() : 'Error interno del servidor'
                ],
                'data' => null
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Format response based on data type
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function formatResponse($data, string $message = 'Operación exitosa', int $statusCode = 200): JsonResponse
    {
        // If data is already a JsonResource or ResourceCollection, return it directly
        if ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            return $data->response()->setStatusCode($statusCode);
        }

        // For other data types, wrap in standard format
        $response = [
            'status' => true,
            'message' => $message,
            'errors' => [],
            'data' => $this->transformData($data)
        ];

        // Add pagination meta for paginated results
        if ($data instanceof LengthAwarePaginator) {
            $response['meta'] = [
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                ]
            ];
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Transform data for response
     *
     * @param mixed $data
     * @return mixed
     */
    protected function transformData($data)
    {
        if ($data instanceof LengthAwarePaginator) {
            return $data->items();
        }

        if ($data instanceof Collection) {
            return $data->toArray();
        }

        if ($data instanceof Model) {
            return $data->toArray();
        }

        if (is_bool($data)) {
            return $data;
        }

        return $data;
    }
}
