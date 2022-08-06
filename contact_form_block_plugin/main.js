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
    var tdesc = document.getElementById('tdesc');
    var tdesc_err = document.getElementById('tdesc_err');
    var error = document.getElementById('error');

    btn.addEventListener('click', function(e) {
        e.preventDefault();
        var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var validemail = pattern.test(email.value);
        if(document.body.contains(name)){
            if(name.value == ''){
                name_err.innerHTML = 'Name is required';
            }
            else {
                name_err.innerHTML = '';
            }
        }
        if(document.body.contains(email)){
            if(email.value == ''){
                email_err.innerHTML = 'Email is required';
            }
            else if(!validemail) {
                email_err.innerHTML = ("Please enter a valid email");
            }
            else {
                email_err.innerHTML = '';
            }
        }
        if(document.body.contains(subject)){
            if(subject.value == ''){
                subject_err.innerHTML = 'Subject is required';
            }
            else {
                subject_err.innerHTML = '';
            }
        }
        if(document.body.contains(msg)){
            if(msg.value == ''){
                msg_err.innerHTML = 'Message is required';
            }
            else {
                msg_err.innerHTML = '';
            }
        }
        if(document.body.contains(contact)){
            if(contact.value == ''){
                con_err.innerHTML = 'Contact is required';
            }
            else if(isNaN(contact.value)) {
                con_err.innerHTML = ("Contact Number should be numeric");
            }
            else if(contact.value.length != 10) {
                con_err.innerHTML = ("Contact Number should be of 10 digits");
            }
            else {
                con_err.innerHTML = '';
            }
        }
        if(document.body.contains(tdesc)){
            if(tdesc.value == ''){
                tdesc_err.innerHTML = 'Input field is required';
            }
            else {
                tdesc_err.innerHTML = '';
            }
        }
        if(name_err.innerHTML == '' && email_err.innerHTML == '' && subject_err.innerHTML == '' && msg_err.innerHTML == '' && con_err.innerHTML == '' && tdesc_err.innerHTML == '' && validemail) {
            jQuery(document).ready(function($) {
                $('input.button').click(function(e) {
                    e.preventDefault();
                    var that = $(this),
                    type = that.attr('method');
                    var name = $('#fullname').val();
                    var email = $('#email').val();
                    var contact = $('#contact').val();
                    var subject = $('#subject').val();
                    var msg = $('#message').val();
                    var tdesc = $('#tdesc').val();
                    $.ajax({
                        url: cpm_object.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'mail_function',
                            name: name,
                            email: email,
                            contact: contact,
                            subject: subject,
                            msg: msg,
                            tdesc: tdesc
                        },
                        success: function(data) {
                            if(data == 'success') {
                                error.innerHTML = 'Message sent successfully';
                            }
                            else if(data == 'error') {
                                error.innerHTML = 'Message not sent';
                            }
                        }
                    });
                });
            });
        }
    });
}