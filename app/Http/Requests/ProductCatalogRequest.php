<?php

namespace App\Http\Requests;

class ProductCatalogRequest extends CatalogRequest
{

    /**
     * С какой сущностью сейчас работаем (товар каталога)
     * @var array
     */
    protected array $entity = [
        'name' => 'product',
        'table' => 'products'
    ];

    /**
     * Объединяет дефолтные правила и правила, специфичные для товара
     * для проверки данных при добавлении нового товара
     * @return array
     */
    protected function createItem(): array
    {
        $rules = [
            'category_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'brand_id' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
        return array_merge(parent::createItem(), $rules);
    }

    /**
     * Объединяет дефолтные правила и правила, специфичные для товара
     * для проверки данных при обновлении существующего товара
     * @return array
     */
    protected function updateItem(): array
    {
        $rules = [
            'category_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'brand_id' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
        return array_merge(parent::updateItem(), $rules);
    }
}
