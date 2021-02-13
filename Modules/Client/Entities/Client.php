<?php

namespace Modules\Client\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the client_images for the Client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function client_images(): HasMany
    {
        return $this->hasMany(ClientImage::class);
    }

}
