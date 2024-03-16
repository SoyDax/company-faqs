<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

//     public static function findById(int|string $id): self
// {
//     $department = static::find($id);

//     if (! $department) {
//         throw new \Exception("Department with id {$id} does not exist.");
//     }

//     return $department;
// }
}

