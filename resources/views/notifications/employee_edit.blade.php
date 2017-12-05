<div class="panel panel-default">
    <div class="panel-heading">Time / Day</div>

    <div class="panel-body">

        <div class="form-group{{ $errors->has('clock_in') ? ' has-error' : '' }}">
            <label for="clock_in" class="col-md-4 control-label">Send clock in reminder at:</label>

            <div class="col-md-6">
                <select name="clock_in" id="clock_in" class="form-control">
                    @foreach($times as $time)
                        <option value="{{$time->value}}" {{old('clock_in', $notif->clock_in) === $time->value ? 'selected' : ''}}>
                            {{$time->text}}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('clock_in'))
                    <span class="help-block">
                        <strong>{{ $errors->first('clock_in') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        
        <div class="form-group{{ $errors->has('clock_out') ? ' has-error' : '' }}">
            <label for="clock_out" class="col-md-4 control-label">Send clock out remoutder at:</label>

            <div class="col-md-6">
            <select name="clock_out" id="clock_out" class="form-control">
                @foreach($times as $time)
                    <option value="{{$time->value}}" {{old('clock_out', $notif->clock_out) === $time->value ? 'selected' : ''}}>
                        {{$time->text}}
                    </option>
                @endforeach
            </select>
                @if ($errors->has('clock_out'))
                    <span class="help-block">
                        <strong>{{ $errors->first('clock_out') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label">On these days:</label>
            <div class="col-md-6">
                <label class="control-label">
                    <input type="checkbox" value="1" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                    Mon
                </label>
                <label class="control-label">
                    <input type="checkbox" value="1" name="tuesday" {{old('tuesday', $notif->tuesday) ? 'checked' : '' }}>
                    Tue
                </label>
                <label class="control-label">
                    <input type="checkbox" value="1" name="wednesday" {{old('wednesday', $notif->wednesday) ? 'checked' : '' }}>
                    Wed
                </label>
                <label class="control-label">
                    <input type="checkbox" value="1" name="thursday" {{old('thursday', $notif->thursday) ? 'checked' : '' }}>
                    Thur
                </label>
                <label class="control-label">
                    <input type="checkbox" value="1" name="friday" {{old('friday', $notif->friday) ? 'checked' : '' }}>
                    Fri
                </label>
                <label class="control-label">
                    <input type="checkbox" value="1" name="saturday" {{old('saturday', $notif->saturday) ? 'checked' : '' }}>
                    Sat
                </label>
                <label class="control-label">
                    <input type="checkbox" value="1" name="sunday" {{old('sunday', $notif->sunday) ? 'checked' : '' }}>
                    Sun
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-6 col-md-offset-4">
                <label class="control-label">
                    <input type="checkbox" value="1" name="exclude_admin" {{old('exclude_admin', $notif->exclude_admin) ? 'checked' : '' }}>
                    Exclude Admins from these notifications
                </label>
            </div>
        </div>
</div>
</div>


<div class="panel panel-default">
<div class="panel-heading">Schedules</div>

<div class="panel-body">

        <div class="form-group{{ $errors->has('schedule_clock_in') ? ' has-error' : '' }}">
            <label for="schedule_clock_in" class="col-md-4 control-label">Send reminder to clock in:</label>

            <div class="col-md-6">
                <select name="schedule_clock_in" id="schedule_clock_in" class="form-control">
                    <option value="15">15 Minutes Before Shift Starts</option>
                    <option value="30">30 Minutes Before Shift Starts</option>
                    <option value="60">1 Hour Before Shift Starts</option>
                </select>

                @if ($errors->has('schedule_clock_in'))
                    <span class="help-block">
                        <strong>{{ $errors->first('schedule_clock_in') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('schedule_clock_out') ? ' has-error' : '' }}">
            <label for="schedule_clock_out" class="col-md-4 control-label">Send reminder to clock out:</label>

            <div class="col-md-6">
                <select name="schedule_clock_out" id="schedule_clock_out" class="form-control">
                    <option value="15">15 Minutes Before Shift Starts</option>
                    <option value="30">30 Minutes Before Shift Starts</option>
                    <option value="60">1 Hour Before Shift Starts</option>
                </select>

                @if ($errors->has('schedule_clock_out'))
                    <span class="help-block">
                        <strong>{{ $errors->first('schedule_clock_out') }}</strong>
                    </span>
                @endif
            </div>
        </div>


    </div>
</div>
