<?php

namespace Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Product extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
    'name',
    'category_id',
    'description',
    'price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::Class, 'category_id', 'id');
    }

}
