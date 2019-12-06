
$(() => {
    const host = 'https://phonebooklab.firebaseio.com/agenda/';
    const list = $('#agenda');
    const dateInput = $('#date');
    const timeInput = $('#time');
    const appointmentPlaceInput = $('#appointmentPlace');
    const eventNameInput = $('#eventName');
    const errorMsg = $('#error');

    // Attach event listeners to buttons
    $('#btnLoad').click(loadAgenda);
    $ ('#btnCreate').click(createAppointment);

    // loadContacts();

    // Implement loading of contact
    // -on lick send GET request
    // -display  data in list(clear list first)
    function loadAgenda(){
        list.empty();
        list.html('<li>Loading &hellip;</li>');
        $.ajax({
            url: host + '.json',
            success: loadSuccess
        })

        function loadSuccess(data) {
            list.empty();
            for (let key in data){
                let entry = data[key];
                appendAppointment(entry, key);
            }
        }
    }

    function appendAppointment(entry, key) {
        const appointment = $(`<article style="display: flex" data-id="${key}">

                    <table>
                      <caption>${entry.eventName}'s Termin</caption>
                        <thead>
                            <tr>
                                  <th scope="col">Date</th>
                                  <th scope="col">Time</th>
                                  <th scope="col">Appointment's Address</th>
                                  <th scope="col">EventName</th>
                                </tr>
                        </thead>
                          <tbody>
                                <tr>
                                  <td data-label="date">${entry.date}</td>
                                  <td data-label="time">${entry.time}</td>
                                  <td data-label="appointmentPlace">${entry.appointmentPlace}</td>
                                  <td data-label="eventName">${entry.eventName}</td>
                                </tr>
                          </tbody>
                        </table>


                            </article><hr/>`);
        const deleteButton = $('<button class="btn-danger" style="height: 50px; width: 100px; margin-top:75px; margin-left: 5px; border-radius: 5px 5px 5px 5px" ><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>');
        deleteButton.click(deleteAppointment);
        $(`<p></p>`).appendTo(appointment);
        appointment.append(deleteButton);
        list.append(appointment);
    }

    // Implement creating contacts
    // -take values from inputs
    // -make POST request with data
    // -(optinal) refresh GET request
    // -(OR) optimistic local update

    function createAppointment() {
        const date = dateInput.val();
        const time = timeInput.val();
        const appointmentPlace = appointmentPlaceInput.val();
        const eventName = eventNameInput.val();
        if(date.length < 1 || time.length < 1){
            errorMsg.text('Both Person and Phone are required.');
            return;
        }
        errorMsg.empty();
        $.ajax({
            url: host + '.json',
            method: 'POST',
            data: JSON.stringify({date, time, appointmentPlace, eventName}),
            success: createSuccess
        });

        function createSuccess(data) {
            const date = dateInput.val();
            const time = timeInput.val();
            const appointmentPlace = appointmentPlaceInput.val();
            const eventName = eventNameInput.val();
            dateInput.val('');
            timeInput.val('');
            appointmentPlaceInput.val('');
            eventNameInput.val('');
            appendAppointment({date, time, appointmentPlace, eventName});
            console.log(data);
        }
    }


    // Implement delete contact
    // -add delete button to list
    // -send DELETE request containing contact ID
    function deleteAppointment(id) {
        const item = $(this).parent();
        const key = item.attr('data-id');
        $.ajax({
            url: host + key + '.json',
            method: 'DELETE',
            success: deleteSuccess
        });
        function deleteSuccess() {
            item.remove();
        }
    }
});