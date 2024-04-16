<?php

use App\Enums\MatchModeEnum;
use App\Enums\StatusEnum;
use Illuminate\Support\Str;

if (! function_exists('buildQuery')){
    function buildQuery($query, $params, $fieldMappings = [])
    {
        // Apply Sorting Logic
        $query = applySorting($query, $params['multiSortMeta'] ?? []);

        // Apply Filtering Logic
        return applyFilters($query, $params['filters'] ?? [], $fieldMappings);
    }
}

if (! function_exists('applySorting')) {
    function applySorting($query, $sortMeta)
    {
        foreach ($sortMeta as $sort) {
            $order = $sort['order'] === 1 ? 'asc' : 'desc';
            $field = $sort['field'];
            if (in_array($field, ['attachments', 'tags', 'roles'])) {
                $query->withCount($field)->orderBy($field . '_count', $order);
            } else {
                $query->orderBy($field, $order);
            }
        }
        return $query;
    }
}

if (! function_exists('applyFilters')) {
    function applyFilters($query, $filters, $fieldMappings = [])
    {
        if (!empty($filters)) {
            foreach ($filters as $field => $filter) {
                $value = $filter['value'];
                $matchMode = $filter['matchMode'];

                if (!isset($value) || $value === '') {
                    continue;
                }

                if ($field === 'global') {
                    applySearchFilter($query, $value, $fieldMappings);
                    continue;
                }

                // Applying different match modes
                switch ($matchMode) {
                    case MatchModeEnum::STARTS_WITH->value:
                        $query->where($field, 'like', "$value%");
                        break;
                    case MatchModeEnum::CONTAINS->value:
                        $query->where($field, 'like', "%$value%");
                        break;
                    case MatchModeEnum::NOT_CONTAINS->value:
                        $query->where($field, 'not like', "%$value%");
                        break;
                    case MatchModeEnum::ENDS_WITH->value:
                        $query->where($field, 'like', "%$value");
                        break;
                    case MatchModeEnum::EQUALS->value:
                        $query->where($field, '=', $value);
                        break;
                    case MatchModeEnum::NOT_EQUALS->value:
                        $query->where($field, '!=', $value);
                        break;
                    case MatchModeEnum::IN->value:
                        if (empty($value)) {
                            break;
                        }
                        if ($field === "tags") {
                            $query->whereHas($field, function ($q) use ($value) {
                                $q->whereIn('title', $value);
                            });
                            break;
                        }

                        if ($field === "roles"){
                            $query->whereHas($field, function ($q) use ($value) {
                                $q->whereIn('name', $value);
                            });
                            break;
                        }

                        $values = collect($value)->pluck('value')->toArray();
                        $query->whereIn($field, $values);
                        break;
                    case MatchModeEnum::LESS_THAN->value:
                        $query->where($field, '<', $value);
                        break;
                    case MatchModeEnum::LESS_THAN_OR_EQUAL_TO->value:
                        $query->where($field, '<=', $value);
                        break;
                    case MatchModeEnum::GREATER_THAN->value:
                        $query->where($field, '>', $value);
                        break;
                    case MatchModeEnum::GREATER_THAN_OR_EQUAL_TO->value:
                        $query->where($field, '>=', $value);
                        break;
                    case MatchModeEnum::BETWEEN->value:
                        $query->whereBetween($field, [$value]);
                        break;
                    case MatchModeEnum::DATE_IS->value:
                        $query->whereDate($field, $value);
                        break;
                    case MatchModeEnum::DATE_IS_NOT->value:
                        $query->whereDate($field, '!=', $value);
                        break;
                    case MatchModeEnum::DATE_BEFORE->value:
                        $query->whereDate($field, '<', $value);
                        break;
                    case MatchModeEnum::DATE_AFTER->value:
                        $query->whereDate($field, '>', $value);
                        break;
                }
            }
        }
        return $query;
    }
}

if (! function_exists('applySearchFilter')) {
    /*
     * @Deprecated: use applyFilters instead
     */
    function applySearchFilter($query, $search, $fieldMappings): void
    {
        $tagPattern = '/\[(.*?)]/';
        preg_match_all($tagPattern, $search, $tagMatches);

        $tags = $tagMatches[1];

        $searchWithoutTags = preg_replace($tagPattern, '', $search);

        $pattern = '/(?<key>\w+)\s*:\s*(?<value>.*?)(?=\w+\s*:|$)/';
        preg_match_all($pattern, $searchWithoutTags, $matches, PREG_SET_ORDER);
        $searchCriteria = [];

        foreach ($matches as $match) {
            $searchCriteria[$match['key']] = trim($match['value']);
        }
        if(empty($searchCriteria)) {
            $searchCriteria['full'] = trim($searchWithoutTags);
        }
        if($tags){
            $searchCriteria['tags'] = $tags;
        }

        $query->where(function ($query) use ($searchCriteria, $fieldMappings) {
            foreach ($searchCriteria as $key => $value) {
                if (array_key_exists($key, $fieldMappings)) {
                    $field = $fieldMappings[$key];
                    applyFilter($query, $field, $key, $value);
                }
            }
        });
    }
}

if (! function_exists('applyFilter')) {
    function applyFilter($query, $field, $key, $value): void
    {
        switch (true) {
            case in_array($key, ['open', 'resolved', 'in_progress', 'closed']):
                if ($value == 'no') {
                    $query->where($field, '<>', $key);
                } else {
                    $query->where($field, $key);
                }
                break;
            case $key == 'tags':
                $query->whereHas($field, function ($query) use ($key, $value) {
                    $query->where('title', $value);
                });
                break;
            case $key == 'roles':
                $query->whereHas($field, function ($query) use ($key, $value) {
                    $query->where('name', $value);
                });
                break;
            case $key == 'full':
                if (is_array($field) || is_object($field)) {
                    $query->where(function ($query) use ($key, $value, $field) {
                        foreach ($field as $k=>$v) {
                            applyFilter($query, $v, $v, $value);
                        }
                    });
                }
                break;
            default:
                if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
                    $value = trim($value, '"');
                    if ($key == "status"){
                        $value = StatusEnum::getValueFromLabel($value)->value;
                    }
                    $query->orWhere($field, $value);
                } else {
                    if ($key == "status"){
                        $value = StatusEnum::getValueFromLabel($value)->value;
                    }
                    $query->orWhere($field, 'LIKE', "%{$value}%");
                }
        }
    }
}
