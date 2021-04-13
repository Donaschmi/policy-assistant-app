<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Event extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'triggerable_type',
        'triggerable_id',
        'attribute',
        'operator',
        'value',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('triggerable', function($builder){
            $builder->with(['triggerable']);
        });
    }

    /**
     * @return MorphTo
     */
    public function triggerable(): MorphTo
    {
        return $this->morphTo();
    }
}
