<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $table = 'groups';
    
    public function children(): HasMany
    {
        return $this->hasMany(Group::class, 'id_parent', 'id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_parent', 'id');
    }
    public function parents()
    {
        $parents = [];
        $parent = $this->parent;
        while($parent) {
            array_push($parents, $parent);
            $parent = $parent->parent;
        }
        return $parents;
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_group', 'id');
    }
    public function getParentsIds()
    {
        $ids = [];
        $parent = $this->parent;
        while($parent) {
            array_push($ids, $parent->id);
            $parent = $parent->parent;
        }
        return $ids;
    }
    public function getChildrenIds()
    {
        $ids = $this->children->pluck('id');

        $this->children->each(function($children) use(&$ids) {
            if ($children->children->count()) {
                $ids->push($children->getChildrenIds());
            }
        });

        return $ids->flatten()->toArray();
    }
    public function getBranchIds()
    {
        return array_merge($this->getParentsIds(), [$this->id], $this->children->pluck('id')->toArray());
    }
    public function getProductsCount()
    {
        $ids = $this->getChildrenIds();
        array_push($ids, $this->id);
        return Product::whereIn('id_group', $ids)->count();
    }
    public function getGroupUriName()
    {
        $prepared_string = preg_replace('/[ -]+/s', '-', trim(mb_strtolower($this->name))); 
        $prepared_string = transliterator_transliterate('Any-Latin; Latin-ASCII;', $prepared_string);
        $prepared_string = preg_replace('/[^a-zA-Z0-9_-]/', '', $prepared_string);
        return $prepared_string;
    }
}
