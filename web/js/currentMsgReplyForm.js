let form = $(`
           <form method="post">
    <div class="container contact">
        <div class="row">
            <div class="col-md-3">
                <div class="contact-info">
                    <img src="https://image.ibb.co/kUASdV/contact-image.png" alt="image"/>
                    <h2>Send Message to your mate</h2>
                    <h4>We would love to hear from you !</h4>
                </div>
            </div>
            <div class="col-md-9">
                <div class="contact-form">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="email">About:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="message[about]" placeholder="About"><br />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="comment">Content:</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="10" name="message[content]"></textarea><br />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
        `);

$('#btnMessage').click(function (e) {
    e.preventDefault();
    $('#form').append(form);
    $('#form').toggle();
})