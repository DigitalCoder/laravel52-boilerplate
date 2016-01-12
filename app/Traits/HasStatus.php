<?php
namespace App\Traits;

trait HasStatus
{
    public function scopeActive($query)
    {
        $tableName = property_exists($this, 'table') ? $this->table.'.' : '';
        return $query->where($tableName.'status', 'active');
    }

    public function scopeInactive($query)
    {
        $tableName = property_exists($this, 'table') ? $this->table.'.' : '';
        return $query->where($tableName.'status', 'inactive');
    }
}
