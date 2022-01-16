<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneBox
{
  public $id;
  public $text;
  public $value;

  function __construct($id, $text, $value)
  {
    $this->id = $id;
    $this->text = $text;
    $this->value = $value;
  }

  public static function data()
  {
    $zone_box = array_fill(1, 6, 'Zone');

    $zone_box = array_map(function ($zone, $index) {
      return new ZoneBox($index, $zone . ' ' . $index, 1);
    }, $zone_box, array_keys($zone_box));

    array_push($zone_box, new ZoneBox(7, 'Backcourt', 7));
    array_push($zone_box, new ZoneBox(8, 'Transisi', 8));

    return $zone_box;
  }
}
