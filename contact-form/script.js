window.onload = function() {
    var btn = document.getElementById('submitbtn');
    var name = document.getElementById('fullname');
    var name_err = document.getElementById('name_err');
    var email = document.getElementById('email');
    var email_err = document.getElementById('email_err');
    var contact = document.getElementById('contact');
    var con_err = document.getElementById('con_err');
    var tdesc = document.getElementById('tdesc');
    var tdesc_err = document.getElementById('tdesc_err');
    var msg = document.getElementById('message');
    var msg_err = document.getElementById('msg_err');

    btn.onclick = function() {
        var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var validemail = pattern.test(email.value);
        if( name.value == ''  || email.value == '' || contact.value == '' || tdesc.value == '' || msg.value == '') {
            if(name.value == '') {
                name_err.innerHTML = "<span style='color:red;'>Name is required</span>";
            }

            if(email.value == '') {
                email_err.innerHTML = "<span style='color:red;'>Email is required</span>";
            }
            else if(!validemail) {
                email_err.innerHTML = ("<span style='color:red;'>Please enter a valid email</span>");
            }

            if(contact.value == '') {
                con_err.innerHTML = "<span style='color:red;'>Contact is required</span>";
            }
            else if(isNaN(contact.value)) {
                con_err.innerHTML = ("<span style='color:red;'>Contact Number should be numeric</span>");
            }
            else if(contact.value.length != 10) {
                con_err.innerHTML = ("<span style='color:red;'>Contact Number should be of 10 digits</span>");
            }

            if(tdesc.value == '') {
                tdesc_err.innerHTML = "<span style='color:red;'>Description is required</span>";
            }

            if(msg.value== '') {
                msg_err.innerHTML = "<span style='color:red;'>Message is required</span>";
            }
        }  
        setTimeout(function() {
            window.location.reload();
        }, 1000);
    };
}
