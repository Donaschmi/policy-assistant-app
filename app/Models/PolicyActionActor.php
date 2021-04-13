<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyActionActor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'policy_action_actor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'policy_id',
        'action_id',
        'actor_id',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('action', function($builder){
            $builder->with(['action']);
        });

        static::addGlobalScope('actor', function($builder){
            $builder->with(['actor']);
        });
    }

    /**
     * @return BelongsTo
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * @return BelongsTo
     */
    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class);
    }

    /**
     * @return BelongsTo
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(Actor::class);
    }
}
