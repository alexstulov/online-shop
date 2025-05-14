<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_group');
    }
    public function groupDrill()
    {
        $drill = $this->group->parents();
        array_unshift($drill, $this->group);
        return array_reverse($drill);
    }
    public function parentGroupIds()
    {
        $ids = $this->group->getParentsIds();
        array_unshift($ids, $this->group->id);
        return $ids;
    }
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'id_product', 'id');
    }
    public function currentPrice(): float
    {
        $price = $this->hasMany(Price::class, 'id_product', 'id')->orderBy('id', 'desc')->first();
        return $price ? $price->price : 0.0;
    }
}
