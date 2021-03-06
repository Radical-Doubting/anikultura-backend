<?php

namespace App\Helpers;

class InsightsHelper
{
    public static function isInsightsEnabled(): bool
    {
        return config('influxdb.enabled');
    }

    public static function isObserverSaveMode(): bool
    {
        return self::getObserverMode() === 'save';
    }

    public static function isObserverCreateMode(): bool
    {
        return self::getObserverMode() === 'create';
    }

    public static function getObserverMode(): string
    {
        return config('influxdb.observerMode');
    }
}
