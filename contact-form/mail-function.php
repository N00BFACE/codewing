<?php
    add_action('wp_head', 'mail_function');
    function mail_function() {
        if(array_key_exists('submit_form', $_POST)) {
            $email = get_option('admin_email');
            $to = $email;
            $subject = "Subject: " . $_POST['subject'];
            $message = "Name: " . $_POST['name'] . "\n";
            $message .= "Email: " . $_POST['email'] . "\n";
            $message .= "Contact: " . $_POST['contact'] . "\n";
            $message .= "Message: " . $_POST['message'] . "\n";
            wp_mail( $to, $subject, $message );
        }
    }       