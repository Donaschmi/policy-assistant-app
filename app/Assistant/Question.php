<?php
namespace App\Assistant;

use App\Models\User;

class Question
{
    const AMOUNT = 3;

    const QUESTION_FORMAT = [
        "multiple" => [
            "Si {{ event }}, dois-je {{ action }} {{ actor_1 }} ou {{ actor_2 }}?",
            "Dois-je {{ action }} {{ actor_1 }} ou {{ actor_2 }} si {{ event }}?"
        ],
        "single" => [
            "Si {{ event }}, dois-je {{ action }} {{ actor }}?",
            "Dois-je {{ action }} {{ actor }} si {{ event }}?"
        ]
    ];

    public static function multiple(): string
    {
        return self::QUESTION_FORMAT["multiple"][array_rand(self::QUESTION_FORMAT["multiple"])];
    }

    public static function single(): string
    {
        return self::QUESTION_FORMAT["single"][array_rand(self::QUESTION_FORMAT["single"])];
    }
}
