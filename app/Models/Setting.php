<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value', 'type', 'description'])]
class Setting extends Model
{
    protected function casts(): array
    {
        return [
            'value' => 'string',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return static::getCached()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value, string $type = 'string', ?string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'description' => $description],
        );

        static::flushCache();
    }

    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $data) {
            static::updateOrCreate(
                ['key' => $key],
                [
                    'value' => (string) ($data['value'] ?? ''),
                    'type' => $data['type'] ?? 'string',
                    'description' => $data['description'] ?? null,
                ],
            );
        }

        static::flushCache();
    }

    public static function getCached(): array
    {
        return Cache::rememberForever('settings.all', function () {
            return static::pluck('value', 'key')->all();
        });
    }

    public static function flushCache(): void
    {
        Cache::forget('settings.all');
    }
}
