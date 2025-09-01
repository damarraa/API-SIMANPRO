<?php

namespace App\Policies;

use App\Models\ToolRequisition;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ToolRequisitionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any::tool_requisition');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ToolRequisition $toolRequisition): bool
    {
        if ($user->can('view::tool_requisition')) {
            return true;
        }

        return $toolRequisition->project->team->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create::tool_requisition');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ToolRequisition $toolRequisition): bool
    {
        if ($user->can('update::tool_requisition')) {
            return true;
        }

        return $user->id === $toolRequisition->requested_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ToolRequisition $toolRequisition): bool
    {
        return $user->can('delete::tool_requisition');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ToolRequisition $toolRequisition): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ToolRequisition $toolRequisition): bool
    {
        return false;
    }
}
