<?php
namespace App\Traits;

use App\Models\ChangeLog;
use Illuminate\Support\Facades\Auth;

trait LogsChanges
{
    public static function bootLogsChanges()
    {
        static::updating(function ($model) {
            $changes = $model->getDirty(); // الحقول التي تم تغييرها

            foreach ($changes as $field => $newValue) {
                $oldValue = $model->getOriginal($field);

                // لا تسجل لو القيمة لم تتغير فعلياً
                if ($oldValue == $newValue) continue;

                ChangeLog::create([
                    'model_type' => get_class($model),
                    'model_id'   => $model->getKey(),
                    'field_name' => $field,
                    'old_value'  => is_scalar($oldValue) ? $oldValue : json_encode($oldValue),
                    'new_value'  => is_scalar($newValue) ? $newValue : json_encode($newValue),
                    'user_id'    => Auth::id(),
                ]);
            }
        });
    }
}
