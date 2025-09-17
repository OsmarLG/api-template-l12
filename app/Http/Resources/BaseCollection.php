<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'first' => $this->resource->url(1),
                'last' => $this->resource->url($this->resource->lastPage()),
                'prev' => $this->resource->previousPageUrl(),
                'next' => $this->resource->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->resource->currentPage(),
                'from' => $this->resource->firstItem(),
                'last_page' => $this->resource->lastPage(),
                'links' => $this->generatePaginationLinks(),
            ],
        ];
    }

    /**
     * Genera los enlaces de paginación con formato personalizado.
     *
     * @return array
     */
    private function generatePaginationLinks()
    {
        $links = [];

        // Enlace "Anterior"
        $links[] = [
            'url' => $this->resource->previousPageUrl(),
            'label' => 'pagination.previous',
            'active' => false
        ];

        // Enlaces de páginas individuales
        for ($page = 1; $page <= $this->resource->lastPage(); $page++) {
            $links[] = [
                'url' => $this->resource->url($page),
                'label' => (string) $page,
                'active' => $this->resource->currentPage() === $page
            ];
        }

        // Enlace "Siguiente"
        $links[] = [
            'url' => $this->resource->nextPageUrl(),
            'label' => 'pagination.next',
            'active' => false
        ];

        return $links;
    }
}
