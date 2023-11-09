<?php

namespace App\Utils;

class TimeHelper
{
    public static function hoursMinutesToTimestamp(string $time): int
    {
        $_time = explode(':', $time);

        $hours = (int) ($_time[0] ?? 0);
        $minutes = (int) ($_time[1] ?? 0);

        if ($hours !== 0) {
            $hours = floor($hours * 3600);
        }

        if ($minutes !== 0) {
            $minutes = floor(($minutes % 3600) * 60);
        }

        return (int) $hours + $minutes;
    }

    public static function timestampToHoursMinutes(int $timestamp): string
    {
        if ($timestamp === 0) {
            return sprintf('%02d:%02d', 0, 0);
        }

        $hours   = floor($timestamp / 3600);
        $minutes = floor(($timestamp % 3600) / 60);

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
