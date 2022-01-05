<?php

namespace App\Policies;

use App\Interfaces\VisitRepositoryInterface;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VisitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Visit $visit)
    {
        if ($user->type_id == 1) {
            //return true;
            return Response::allow();
        } else {
            if ($visit->confirm_visit) {
                $startTime = Carbon::parse($visit->updated_at)->timezone('Europe/Warsaw');
                $finishTime = Carbon::now()->timezone('Europe/Warsaw');
                $totalDuration = $finishTime->diffInMinutes($startTime);

                if ($totalDuration < VisitRepositoryInterface::MAX_TIME_TO_EDIT) {
                    return Response::allow();
                } else {
                    return Response::deny('Brak uprawnień do teh akcji!');
                }
            } else {
                //return true;
                return Response::allow();
            }
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Visit $visit)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Visit $visit)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Visit $visit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Visit $visit)
    {
        //
    }
}
