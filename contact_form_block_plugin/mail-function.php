<?php
    add_action( 'wp_ajax_mail_function', 'mail_function' );
    add_action( 'wp_ajax_nopriv_mail_function', 'mail_function');
    function mail_function() {
        $email = get_option('admin_email');
        $to = $email;
        if($_POST['name'] != ''){
            $message = "Name: " . $_POST['name'] . "\n";
        }
        if($_POST['email'] != ''){
            $message .= "Email: " . $_POST['email'] . "\n";
        }
        if($_POST['contact'] != ''){
            $message .= "Contact: " . $_POST['contact'] . "\n";
        }
        if($_POST['msg'] != ''){
            $message .= "Message: " . $_POST['msg'] . "\n";
        }
        if($_POST['subject'] != '') {
            $subject = "Subject: " . $_POST['subject'];
        }
        $test = wp_mail($to, $subject, $message);
        if($test) {
            echo "success";
        }
        else {
            echo "error";
        }
        die();
    }       