<?php

namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

/**
 * App\Models\Basket
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Product> $products
 * @property-read int|null $products_count
 * @method static Builder|Basket newModelQuery()
 * @method static Builder|Basket newQuery()
 * @method static Builder|Basket query()
 * @method static Builder|Basket whereCreatedAt($value)
 * @method static Builder|Basket whereId($value)
 * @method static Builder|Basket whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Basket extends Model
{

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function increase($id, $count = 1): void
    {
        $this->change($id, $count);
    }

    public function decrease($id, $count = 1): void
    {
        $this->change($id, -1 * $count);
    }

    private function change($id, $count = 0): void
    {
        if ($count == 0) {
            return;
        }
        if ($this->products->contains($id)) {
            $pivotRow = $this->products()->where('product_id', $id)->first()->pivot;
            $quantity = $pivotRow->quantity + $count;
            if ($quantity > 0) {
                $pivotRow->update(['quantity' => $quantity]);
            } else {
                $pivotRow->delete();
            }
        } elseif ($count > 0) {
            $this->products()->attach($id, ['quantity' => $count]);
        }
        $this->touch();
    }

    public static function getCount(): int
    {
        $basket_id = request()->cookie('basket_id');
        if (empty($basket_id)) {
            return 0;
        }
        return self::getBasket()->products->count();
    }

    public function remove($id): void
    {
        $this->products()->detach($id);
        $this->touch();
    }

    public static function getBasket(): mixed
    {
        $basket_id = (int)request()->cookie('basket_id');
        if (!empty($basket_id)) {
            try {
                $basket = Basket::findOrFail($basket_id);
            } catch (ModelNotFoundException $e) {
                $basket = Basket::create();
            }
        } else {
            $basket = Basket::create();
        }
        Cookie::queue('basket_id', $basket->id, 525600);
        return $basket;
    }
}
