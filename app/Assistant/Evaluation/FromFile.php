<?php
namespace App\Assistant\Evaluation;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Redis;

class FromFile
{
    const ACCEPTED_SENSORS = [
      'I06'
    ];

    public static function readFile()
    {
        $handle = fopen(__DIR__."/data/data", "r") or die("Unable to open file!");
        while (($line = fgets($handle)) !== false) {

            $exploded = explode("	", $line);

            if (!in_array($exploded[2], static::ACCEPTED_SENSORS)){
                continue;
            }

            $timestamp =  $exploded[0].' '.$exploded[1];
            try{
                $date = Carbon::createFromFormat('Y-m-d H:i:s.u', $timestamp);
            }
            catch (InvalidFormatException $e){
                continue;
            }
            echo $date;
            echo "\n";
        }
        fclose($handle);
    }
}
