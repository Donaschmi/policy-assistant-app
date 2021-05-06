<?php
namespace App\Assistant;

use Illuminate\Support\Facades\Redis;

class Monitoring
{
    public static function registerEvent(int $eventId, int $occurrence = 1)
    {
        return Redis::incr('event:counter:'.$eventId, $occurrence);
    }
}
