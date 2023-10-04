<?php

namespace App\Http\Resource\Traits;

trait Components
{
    /**
     * Forming select for HTML-page.
     *
     * @param mixed $entities
     * @param string $type
     * @param array $filters
     */
    public function customSelectData(mixed $entities, string $type, array $filters = [])
    {
        if (!is_array($entities)) {
            $entities = $entities->toArray();
        }
        $idEntity = 'id_' . $type;
        $entities = array_merge([0 => [$idEntity => 0, 'name' => trans('products.all')]], $entities);

        return view('components.select', compact('entities', 'idEntity', 'type', 'filters'));
    }
}
