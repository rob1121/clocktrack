
<div class="panel panel-default">

    <div class="panel-body">

        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="early_in" {{old('early_in', $notif->early_in) ? 'checked' : '' }}>
            Early In
            </label>
            <br/>
            <small><i>Clocked in 15 minutes before scheduled start of shift.</i></small>
        </div>
        
        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="early_out" {{old('early_out', $notif->early_out) ? 'checked' : '' }}>
            Early Out
            </label>
            <br/>
            <small><i>Clocked in 15 minutes before scheduled start of shift.</i></small>
        </div>

        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="late_in" {{old('late_in', $notif->late_in) ? 'checked' : '' }}>
            Late In
            </label>
            <br/>
            <small><i>Clocked in 15 minutes after scheduled start of shift.</i></small>
        </div>
        
        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="late_out" {{old('late_out', $notif->late_out) ? 'checked' : '' }}>
            Late Out
            </label>
            <br/>
            <small><i>Clocked in 15 minutes after scheduled start of shift.</i></small>
        </div>



        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="missing_in" {{old('missing_in', $notif->missing_in) ? 'checked' : '' }}>
            Missing In
            </label>
            <br/>
            <small><i>Missing clock in; more than 4 hours have passed from scheduled start. For all-day shifts, after 6:30 pm local time and no clock-in has been received.</i></small>
        </div>
        
        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="missing_out" {{old('missing_out', $notif->missing_out) ? 'checked' : '' }}>
            Missing Out
            </label>
            <br/>
            <small><i>No clock out after more than 23 hours.
Note: shifts are still automatically ended after 23 hours even with this notification disabled.</i></small>
        </div>

        

        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="unscheduled_time" {{old('unscheduled_time', $notif->unscheduled_time) ? 'checked' : '' }}>
            Unscheduled Time 
            </label>
            <br/>
            <small><i>Clocked in but was not scheduled for the time and Job chosen.</i></small>
        </div>
        
        <div class="col-md-6 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="location_tampering" {{old('location_tampering', $notif->location_tampering) ? 'checked' : '' }}>
            Location Tampering 
            </label>
            <br/>
            <small><i>A Mock Location was detected while clocking in or out.</i></small>
        </div>


    </div>
</div>


<div class="panel panel-default">

    <div class="panel-body">
        <div class="row">
    <div class="col-md-12 text-left">
        <label class="control-label">
            <input type="checkbox" value="1" name="send_notification" {{old('send_notification', $notif->send_notification) ? 'checked' : '' }}>
            Send Notification Emails 
            </label>
            <br/>
            <small><i>Send selected recipients an email when any of the selected notifications above happen.</i></small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
        
        
        <label class="control-label">Notification Recipients:</label>
            @component('components.select2_multiple', [
                'name' => 'recipient', 
                'id' => 'select2_employees', 
                'value' => old('recipient', $notif->recipient),
                'options' => $employees
            ])
            @endcomponent
        </div>
    </div>
    </div>
</div>
