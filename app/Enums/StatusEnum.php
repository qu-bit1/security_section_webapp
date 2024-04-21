<?php

namespace App\Enums;

enum StatusEnum: string {
    // used for report status
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';
    case NORMAL = 'normal';

    // used for approval status
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case DRAFT = 'draft'; // report is created but not submitted for approval

    case UNKNOWN = 'unknown';

    public static function getValues(): array
    {
        return [
            self::OPEN->value,
            self::IN_PROGRESS->value,
            self::RESOLVED->value,
            self::CLOSED->value,
            self::NORMAL->value
        ];
    }

    public static function getApproveValues(): array
    {
        return [
            self::PENDING->value,
            self::APPROVED->value,
            self::REJECTED->value,
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
