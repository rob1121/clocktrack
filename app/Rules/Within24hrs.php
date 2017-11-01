<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
class Within24hrs implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $HOURS_IN_DAY = 24;
        $MINUTES_IN_HOUR = 60;

        $start = Carbon::parse($value['date_from']);
        $end = Carbon::parse($value['date_to']);

        return $start->diffInMinutes($end) < ($HOURS_IN_DAY * $MINUTES_IN_HOUR);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute dates range must be not more than 24 hrs.';
    }
}
