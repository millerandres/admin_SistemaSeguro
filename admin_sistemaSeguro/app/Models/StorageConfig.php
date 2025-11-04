<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageConfig extends Model
{
    protected $fillable = ['config_key', 'config_value'];

    public static function getGlobalQuota()
    {
        return self::where('config_key', 'global_quota')->first()?->config_value ?? 10 * 1024 * 1024;
    }

    public static function getForbiddenExtensions()
    {
        $config = self::where('config_key', 'forbidden_extensions')->first();
        return $config ? json_decode($config->config_value, true) : [];
    }
}
