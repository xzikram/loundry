<?php

namespace App\Models\Central;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PlanFeature extends Pivot
{
    protected $table = 'plan_features';

    protected $fillable = [
        'plan_id',
        'feature_id',
        'value',
    ];
}
