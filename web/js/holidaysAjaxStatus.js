
$(() => {
    const host = 'https://phonebooklab.firebaseio.com/holidays/';
    const list = $('#holidays');
    const personInput = $('#person');
    const phoneInput = $('#phone');
    const emailInput = $('#email');
    const periodInput = $('#period');
    const daysInput = $('#days');
    const commentsInput = $('#comments');
    const errorMsg = $('#error');

    // Attach event listeners to buttons
    $('#btnLoad').click(loadContacts);
    $ ('#btnCreate').click(createContact);

    // loadContacts();
    // Implement loading of contact
    // -on lick send GET request
    // -display  data in list(clear list first)
    function loadContacts(){
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
                appendContact(entry, key);
            }
        }
    }

    function appendContact(entry, key) {
        const contact = $(`<article style="display: flex" data-id="${key}">

                    <table>
                      <caption>${entry.person}'s Holiday</caption>
                        <thead>
                            <tr>
                                  <th scope="col">Worker Name</th>
                                  <th scope="col">Phone</th>
                                  <th scope="col">Email</th>
                                  <th scope="col">Period</th>
                                  <th scope="col">Days</th>
                                  <th scope="col">Comments</th>
                                </tr>
                        </thead>
                          <tbody>
                                <tr>
                                  <td data-label="Person">${entry.person}</td>
                                  <td data-label="Phone">${entry.phone}</td>
                                  <td data-label="Email">${entry.email}</td>
                                  <td data-label="Period">${entry.period}</td>
                                  <td data-label="Days">${entry.days}</td>
                                  <td data-label="Comments">${entry.comments}</td>
                                </tr>
                          </tbody>
                        </table>


                            </article><hr/>`);
        const deleteButton = $('<button class="btn-danger" style="height: 50px; width: 100px; margin-top: 72px; margin-left: 5px; border-radius: 5px 5px 5px 5px" ><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>');
        deleteButton.click(deleteContact);
        $(`<p></p>`).appendTo(contact);
        contact.append(deleteButton);
        list.append(contact);
    }

    // Implement creating contacts
    // -take values from inputs
    // -make POST request with data
    // -(optinal) refresh GET request
    // -(OR) optimistic local update

    function createContact() {
        const person = personInput.val();
        const phone = phoneInput.val();
        const email = emailInput.val();
        const period = periodInput.val();
        const days = daysInput.val();
        const comments = commentsInput.val();
        if(person.length < 1 || phone.length < 1){
            errorMsg.text('Both Person and Phone are required.');
            return;
        }
        errorMsg.empty();
        $.ajax({
            url: host + '.json',
            method: 'POST',
            data: JSON.stringify({person, phone, email, period, days, comments}),
            success: createSuccess
        });

        function createSuccess(data) {
            const person = personInput.val();
            const phone = phoneInput.val();
            const email = emailInput.val();
            const period = periodInput.val();
            const days = daysInput.val();
            const comments = commentsInput.val();
            personInput.val('');
            phoneInput.val('');
            emailInput.val('');
            periodInput.val('');
            daysInput.val('');
            commentsInput.val('');
            appendContact({person, phone, email, period, days, comments});
            console.log(data);
        }
    }


    // Implement delete contact
    // -add delete button to list
    // -send DELETE request containing contact ID
    function deleteContact(id) {
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