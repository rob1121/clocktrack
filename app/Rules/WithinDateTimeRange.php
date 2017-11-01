<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class WithinDateTimeRange implements Rule
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
        $isNotValidBreakTime = true;
        $isNotValidBreakTime = (is_null($value['break_in']) || is_null($value['break_in']));
        $start = Carbon::parse($value['date_range']['date_from']);
        $end = Carbon::parse($value['date_range']['date_to']);

        if($isNotValidBreakTime) return false;

        $breakTime = breakTimeFormat($value['break_in'], $value['break_out'], $start, $end);
        
        $isWithinDateTimeRange = (
            Carbon::parse($breakTime->in)->between($start, $end) &&
            Carbon::parse($breakTime->out)->between($start, $end)
        );

        return $isWithinDateTimeRange;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please enter a valid :attribute time range';
    }
}
