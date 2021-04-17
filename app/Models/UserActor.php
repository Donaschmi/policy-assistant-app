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
class UserActor extends Model
{
    use HasFactory;

    protected $table = 'user_actors';

    protected $fillable = [
        'tenant_id',
        'actor_id',
        'fullname',
        'phone_number'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('actor', function($builder){
            $builder->with(['actor']);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(Actor::class);
    }

    public function sentence(): string
    {
        return $this->actor->sentence;
    }
}
