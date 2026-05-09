<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    public function logActivity(string $action, ?string $description = null)
    {
        if (!Auth::check()) {
            return;
        }

        $module = $this->getActivityModule();
        $description = $description ?? $this->getActivityDescription($action);

        Activity::create([
            'user_id' => Auth::id(),
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'meta' => $this->getActivityMeta($action),
        ]);
    }

    protected function getActivityModule(): string
    {
        if (isset($this->activityModule)) {
            return $this->activityModule;
        }

        return strtolower(class_basename($this));
    }

    protected function getActivityDescription(string $action): string
    {
        $name = $this->name ?? $this->full_name ?? $this->title ?? 'Record';
        $module = $this->getActivityModule();

        return "{$action} {$module}: {$name}";
    }

    protected function getActivityMeta(string $action): array
    {
        if ($action === 'updated') {
            return [
                'old' => array_intersect_key($this->getOriginal(), $this->getDirty()),
                'new' => $this->getDirty(),
            ];
        }

        return [];
    }
}
