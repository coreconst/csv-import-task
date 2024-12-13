<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductData extends Model
{
    protected $table = 'tblProductData';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'strProductName',
        'strProductDesc',
        'strProductCode',
        'intStock',
        'decPrice',
        'dtmAdded',
        'dtmDiscontinued'
    ];
}
