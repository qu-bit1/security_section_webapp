<?php

namespace App\Enums;

enum StatusEnum: string {
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case NORMAL = 'normal';

    case UNKNOWN = 'unknown';

    public static function getValues(): array
    {
        return [
            self::OPEN,
            self::IN_PROGRESS,
            self::RESOLVED,
            self::CLOSED,
            self::NORMAL
        ];
    }

    public static function getValueFromLabel(string $label): StatusEnum
    {
        $label = strtolower($label);
        return match ($label) {
            'open' => self::OPEN,
            "in_progress", 'in progress', 'in-progress', 'inprogress' => self::IN_PROGRESS,
            'resolved' => self::RESOLVED,
            'closed' => self::CLOSED,
            'normal' => self::NORMAL,
            default => self::UNKNOWN,
        };
    }
}
