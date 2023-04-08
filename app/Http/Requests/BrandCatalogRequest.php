<?php

namespace App\Http\Requests;

class BrandCatalogRequest extends CatalogRequest
{

    /**
     * С какой сущностью сейчас работаем (бренд каталога)
     * @var array
     */
    protected array $entity = [
        'name' => 'brand',
        'table' => 'brands'
    ];

    /**
     * Объединяет дефолтные правила и правила, специфичные для бренда
     * для проверки данных при добавлении нового бренда
     * @return array
     */
    protected function createItem(): array
    {
        $rules = [];
        return array_merge(parent::createItem(), $rules);
    }

    /**
     * Объединяет дефолтные правила и правила, специфичные для бренда
     * для проверки данных при обновлении существующего бренда
     * @return array
     */
    protected function updateItem(): array
    {
        $rules = [];
        return array_merge(parent::updateItem(), $rules);
    }
}
