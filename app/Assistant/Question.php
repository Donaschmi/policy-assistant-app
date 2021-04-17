<?php
namespace App\Assistant;

use App\Models\User;

class Question
{
    const AMOUNT = 3;

    const QUESTION_FORMAT = [
        "multiple" => "Si {{ event }}, dois-je {{ action }} {{ actor_1 }} ou {{ actor_2 }}?",
        "single" => "Si {{ event }}, dois-je {{ action }} {{ actor }}?"
    ];

    public static function multiple(): string
    {
        return self::QUESTION_FORMAT["multiple"];
    }

    public static function single(): string
    {
        return self::QUESTION_FORMAT["single"];
    }
}
