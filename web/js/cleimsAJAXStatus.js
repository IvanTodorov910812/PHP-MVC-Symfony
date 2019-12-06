
$(() => {
    // const host = 'https://phonebooklab.firebaseio.com/phonebook/';
    const host = 'https://phonebooklab.firebaseio.com/wasteproducts/';
    const list = $('#wasteproducts');
    const productInput = $('#productName');
    const supplierInput = $('#supplierName');
    const incomeDayInput = $('#incomeDay');
    const quantityInput = $('#quantity');
    const descriptionInput = $('#description');
    const errorMsg = $('#error');

    // Attach event listeners to buttons
    $('#btnLoad').click(loadWasteProducts);
    $ ('#btnCreate').click(createClaim);

    // loadWasteProducts();
    // Implement loading of contact
    // -on lick send GET request
    // -display  data in list(clear list first)
    function loadWasteProducts(){
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
                appendClaim(entry, key);
            }
        }
    }

    function appendClaim(entry, key) {
        const product = $(`<article style="display: flex" data-id="${key}">

                    <table>
                      <caption>${entry.product}'s Claim</caption>
                        <thead>
                            <tr>
                                  <th scope="col">Product</th>
                                  <th scope="col">Supplier</th>
                                  <th scope="col">IncomeDay</th>
                                  <th scope="col">Quantity</th>
                                  <th scope="col">Description</th>
                                </tr>
                        </thead>
                          <tbody>
                                <tr>
                                  <td data-label="productName">${entry.product}</td>
                                  <td data-label="supplierName">${entry.supplier}</td>
                                  <td data-label="incomeDay">${entry.incomeDay}</td>
                                  <td data-label="quantity">${entry.quantity}</td>
                                  <td data-label="description">${entry.description}</td>
                                </tr>
                          </tbody>
                        </table>


                            </article><hr/>`);
        const deleteButton = $('<button class="btn-danger" style="height: 50px; width: 100px; margin-top:75px; margin-left: 5px; border-radius: 5px 5px 5px 5px" ><i class="fa fa-trash" aria-hidden="true"></i> Remove</button>');
        deleteButton.click(deleteClaim);
        $(`<p></p>`).appendTo(product);
        product.append(deleteButton);
        list.append(product);
    }

    // Implement creating contacts
    // -take values from inputs
    // -make POST request with data
    // -(optinal) refresh GET request
    // -(OR) optimistic local update

    function createClaim() {
        const product = productInput.val();
        const supplier = supplierInput.val();
        const incomeDay = incomeDayInput.val();
        const quantity = quantityInput.val();
        const description = descriptionInput.val();
        if(product.length < 1 || supplier.length < 1){
            errorMsg.text('Both Person and Phone are required.');
            return;
        }
        errorMsg.empty();
        $.ajax({
            url: host + '.json',
            method: 'POST',
            data: JSON.stringify({product, supplier, incomeDay, quantity, description}),
            success: createSuccess
        });

        function createSuccess(data) {
            const product = productInput.val();
            const supplier = supplierInput.val();
            const incomeDay = incomeDayInput.val();
            const quantity = quantityInput.val();
            const description = descriptionInput.val();
            productInput.val('');
            supplierInput.val('');
            incomeDayInput.val('');
            quantityInput.val('');
            descriptionInput.val('');
            appendClaim({product, supplier, incomeDay, quantity, description});
            console.log(data);
        }
    }


    // Implement delete contact
    // -add delete button to list
    // -send DELETE request containing contact ID
    function deleteClaim(id) {
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