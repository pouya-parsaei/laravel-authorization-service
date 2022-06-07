<?php

namespace App\Services\Traits;

use App\Models\Permission;
use Illuminate\Support\Arr;

trait HasPermissions
{

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionsTo(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);

        $this->permissions()->syncWithoutDetaching($permissions);

        return $this;
    }

    private function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('name', Arr::flatten($permissions))->get();
    }

    public function withdrawPermissions(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);

        $this->permissions()->detach($permissions);

        return $this;
    }

    public function refreshPermissions(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);

        $this->permissions()->sync($permissions);

        return $this;
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasPermissionsThroughRole($permission) || $this->permissions->contains($permission);
    }

    private function hasPermissionsThroughRole(Permission $permission)
    {
        foreach($permission->roles as $role){
            if($this->roles->contains($role)) return true;
        }

        return false;
    }

}
