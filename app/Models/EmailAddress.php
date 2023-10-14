<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class EmailAddress extends Model
{
    use HasTags;
    use HasFactory;

    protected $guarded = [];

    public function emails()
    {
        return $this->hasMany(Email::class);
    }
}
