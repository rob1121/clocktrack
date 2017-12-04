<form class="form-horizontal" method="POST" action="{{route('notification.update', ['notification'=> $notif->id])}}">
                {{ csrf_field() }}
                {{method_field('PUT')}}
            <div class="panel panel-default">

                <div class="panel-body">

                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Early In
                      </label>
                      <br/>
                      <small><i>Clocked in 15 minutes before scheduled start of shift.</i></small>
                  </div>
                  
                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Early Out
                      </label>
                      <br/>
                      <small><i>Clocked in 15 minutes before scheduled start of shift.</i></small>
                  </div>

                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Late In
                      </label>
                      <br/>
                      <small><i>Clocked in 15 minutes after scheduled start of shift.</i></small>
                  </div>
                  
                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Late Out
                      </label>
                      <br/>
                      <small><i>Clocked in 15 minutes after scheduled start of shift.</i></small>
                  </div>



                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Missing In
                      </label>
                      <br/>
                      <small><i>Missing clock in; more than 4 hours have passed from scheduled start. For all-day shifts, after 6:30 pm local time and no clock-in has been received.</i></small>
                  </div>
                  
                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Missing Out
                      </label>
                      <br/>
                      <small><i>No clock out after more than 23 hours.
Note: shifts are still automatically ended after 23 hours even with this notification disabled.</i></small>
                  </div>

                  

                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Unscheduled Time 
                      </label>
                      <br/>
                      <small><i>Clocked in but was not scheduled for the time and Job chosen.</i></small>
                  </div>
                  
                  <div class="col-md-6 text-left">
                    <label class="control-label">
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
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
                        <input type="checkbox" name="monday" {{old('monday', $notif->monday) ? 'checked' : '' }}>
                        Send Notification Emails 
                      </label>
                      <br/>
                      <small><i>Send selected recipients an email when any of the selected notifications above happen.</i></small>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    
                  
                 <label class="control-label">Notification Recipients: </label>
                @component('components.select2_multiple', [
                  'name' => 'employees', 
                  'id' => 'select2_employees', 
                  'value' => old('employees'),
                  'options' => $employees
                ])
                @endcomponent
                  </div>
                </div>
                </div>
            </div>
                        
            <div class="form-group">
                <div class=" col-md-12">
                    <button type="submit" class="btn btn-primary">
                        Update Settings
                    </button>
                </div>
            </div>
        </form>
