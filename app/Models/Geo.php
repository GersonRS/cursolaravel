<?php

namespace Ecommerce\Models;

use Illuminate\Contracts\Support\Jsonable;

class Geo implements Jsonable
{
    public $lat;
    public $long;

    public function toJson($options = 0)
    {
      return json_encode([
        'lat' => $this->lat,
        'long' => $this->long
      ]);
    }
}
