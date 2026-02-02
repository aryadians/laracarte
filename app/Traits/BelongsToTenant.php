<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    /**
     * Boot the BelongsToTenant trait for a model.
     *
     * @return void
     */
    public static function bootBelongsToTenant()
    {
        // Add global scope to filter by tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (auth()->check()) {
                // Super Admin can see EVERYTHING
                if (auth()->user()->hasRole(\App\Enums\UserRole::SUPER_ADMIN)) {
                    return;
                }
                $builder->where('tenant_id', auth()->user()->tenant_id);
            } else if (session()->has('tenant_id')) {
                // Fallback for guest (like login page or public menu if we tackle that later)
                $builder->where('tenant_id', session('tenant_id'));
            }
        });

        // Auto-assign tenant_id when creating
        static::creating(function ($model) {
            if (auth()->check() && !$model->tenant_id) {
                // Super Admin might not have a tenant_id, so they usually don't create business data
                // but if they do, we don't want to assign null if it's required.
                // For now, we just skip for Super Admin.
                if (auth()->user()->hasRole(\App\Enums\UserRole::SUPER_ADMIN)) {
                    return;
                }
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    /**
     * A model belongs to a tenant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
