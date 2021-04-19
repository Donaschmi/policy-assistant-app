<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserActor
 * @package App\Models
 *
 * @property-read string $sentence
 */
class Actor extends Model
{
    use HasFactory;

    protected $table = 'actors';

    protected $fillable = [
        'tenant_id',
        'actor_type_id',
        'fullname',
        'phone_number'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('actorType', function($builder){
            $builder->with(['actorType']);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function actorType(): BelongsTo
    {
        return $this->belongsTo(ActorType::class);
    }

    public function sentence(): string
    {
        return $this->actorType->sentence;
    }
}
