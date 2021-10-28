<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallAnalysis
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
      new Position(1, 'Correct Call', 5),
      new Position(2, 'Incorrect Call', 3),
      new Position(3, 'Incorrect Non Call', 1),
      new Position(4, 'Debrief', 0),
    ];
  }
}
