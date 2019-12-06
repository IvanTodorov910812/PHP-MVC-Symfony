// language=HTML
let form = $(`
    <div class="container body-content span=8 offset=2">
        <div class="well">
            <form class="form-horizontal" method="post">
                <fieldset>
                    <legend>Create New Appointment</legend>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="agenda_date">Date</label>
                        <div class="col-sm-4 ">
                            {{ form_widget(form.date) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="agenda_appointment">Appointment</label>
                        <div class="col-sm-4 ">
                            <input type="text" class="form-control" id="agenda_appointment" placeholder="Appointment" name="agenda[appointment]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="agenda_eventName">EventName</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="agenda_eventName" placeholder="EventName" name="agenda[eventName]" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                    {{  form_row(form._token) }}
                </fieldset>
            </form>
        </div>
    </div>
        `);

$('#btnNewAppointment').click(function (e) {
    e.preventDefault();
    $('#form').append(form);
    $('#form').toggle();
})