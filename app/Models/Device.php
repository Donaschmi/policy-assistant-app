<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;

/**
 * Class Device
 * @package App\Models
 *
 * @property int id
 * @method static self first
 * @method static Builder whereName($value)
 */
class Device extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'devices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];


    /**
     * @return MorphMany
     */
    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'triggerable');
    }
}
