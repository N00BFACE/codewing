window.onload = function() {
    var btn = document.getElementById('submitbtn');
    var name = document.getElementById('fullname');
    var name_err = document.getElementById('name_err');
    var email = document.getElementById('email');
    var email_err = document.getElementById('email_err');
    var contact = document.getElementById('contact');
    var con_err = document.getElementById('con_err');
    var subject = document.getElementById('subject');
    var subject_err = document.getElementById('subject_err');
    var msg = document.getElementById('message');
    var msg_err = document.getElementById('msg_err');

    btn.onclick = function() {
        var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var validemail = pattern.test(email.value);
        if(name.value == '') {
            name_err.innerHTML = "Name is required";
        }

        if(email.value == '') {
            email_err.innerHTML = "Email is required";
        }
        else if(!validemail) {
            email_err.innerHTML = ("Please enter a valid email");
        }

        if(contact.value == '') {
            con_err.innerHTML = "Contact is required";
        }
        else if(isNaN(contact.value)) {
            con_err.innerHTML = ("Contact Number should be numeric");
        }
        else if(contact.value.length != 10) {
            con_err.innerHTML = ("Contact Number should be of 10 digits");
        }

        if(subject.value == '') {
            subject_err.innerHTML = "Subject is required";
        }

        if(msg.value== '') {
            msg_err.innerHTML = "Message is required";
        }
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    
        $(document).ready(function() {
            $('.button').click(function() {
                var form = $('#myform')[0];
                var formData = new FormData(form);
                $.ajax({
                    type: "POST",
                    url: "../contact-form/mail-function.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(data) {
                        var jData = JSON.parse(data);
                        $('#name').val(jData[0].name);
                        $('#email').val(jData[0].email);
                        $('#contact').val(jData[0].contact);
                        $('#tdesc').val(jData[0].tdesc);
                        $('#message').val(jData[0].message);
                        alert("Mail sent successfully");
                        window.location.reload();
                    },
                    error: function() {
                        alert("Mail not sent");
                    }
                });
            });
        });
    }
}