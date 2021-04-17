<?php
namespace App\Assistant;

class Policy
{
    const POLICY_FORMAT = [
        "multiple" => "Si {{ event }}, dois-je {{ action }} {{ actor1 }} ou {{ actor2 }}?",
        "single" => "Si {{ event }}, dois-je {{ action }} {{ actor }}?"
    ];

    public static function multiple()
    {
        return self::POLICY_FORMAT["multiple"];
    }

    public static function single()
    {
        return self::POLICY_FORMAT["single"];
    }
}
