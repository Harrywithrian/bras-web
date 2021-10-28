<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position
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

  public static function data() {
    return [
      new Position(1, 'Trail', 1),
      new Position(2, 'Center', 1),
      new Position(3, 'Lead', 1),
    ];
  }
}
