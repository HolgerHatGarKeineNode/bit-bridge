<?php

namespace App\Models;

class Tag extends \Spatie\Tags\Tag
{
    public function email_addresses()
    {
        return $this->morphedByMany(EmailAddress::class, 'taggable');
    }
}
