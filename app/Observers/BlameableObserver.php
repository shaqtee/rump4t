<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class BlameableObserver
{
    //Logic From Container Port
    // public function creating(Model $model)
    // {
    //     $column = $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'created_by');
    //     if ($column) {
    //         $model->created_by = Auth::user()->id ?? 0;
    //     }
    // }

    // public function updating(Model $model)
    // {
    //     $update = $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'updated_by');
    //     $modified = $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'modified_by');
    //     if ($update) {
    //         $model->updated_by = Auth::user()->id ?? 0;
    //     }

    //     if ($modified) {
    //         $model->modified_by = Auth::user()->id ?? 0;
    //     }
    // }

    public function creating(Model $model)
    {
        $this->setCreatedBy($model);
    }

    public function updating(Model $model)
    {
        $this->setUpdatedBy($model);
    }

    protected function setCreatedBy(Model $model)
    {
        if (!isset($model->created_by) || isset($model->created_by)) {
            $model->created_by = Auth::id();
        }
    }

    protected function setUpdatedBy(Model $model)
    {
        if (!isset($model->updated_by) || isset($model->updated_by)) {
            $model->updated_by = Auth::id();
        }
    }
}
