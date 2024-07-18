<?php

namespace PageviewCounter\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Pageview extends Model
{
    protected $guarded = [];

    public function scopeDaily(Builder $query): Builder
    {
        return $query->selectRaw('COUNT(*) views, path, DATE(created_at) date')
            ->groupBy(['date', 'path'])
            ->orderBy('date', 'desc');
    }

    public function scopeWithoutBots(Builder $query): Builder
    {
        return $query->where('useragent', 'not like', '%bot%')
            ->where('useragent', 'not like', '%python-requests%')
            ->where('useragent', 'not like', '%http%')
            ->where('useragent', 'not like', '%node-fetch%')
            ->where('useragent', 'not like', '%postman%')
            ->where('useragent', 'not like', '%curl%');
    }

    public function scopeUniqueVisitors(Builder $query): Builder
    {
        return $query->selectRaw('COUNT(DISTINCT visitorid) unique_visitors, DATE(created_at) date')
                ->groupBy(['date'])
                ->orderBy('date', 'desc');
    }
}