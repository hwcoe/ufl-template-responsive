<?php
include_once TEMPLATEPATH . '/library/securimage/securimage.php';
$securimage = new Securimage();

if (isset($_POST['submitted'])) {
    //echo "<script type='text/javascript'>location.href='#emailForm';</script>";
    if ($_POST['emailFrom'] == '' || $_POST['emailFrom'] == 'Your Email') {
        $emailFromError = 'Please enter your email address.';
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $_POST['emailFrom'])) {
        $emailFromError = 'Please enter a valid email address.';
    }

    if ($_POST['emailFromName'] == '' || $_POST['emailFromName'] == 'Your Name') {
        $emailFromNameError = 'Please enter your name.';
    }

    if ($_POST['message'] == '' || $_POST['message'] == 'Your Message') {
        $messageError = 'Please enter a message.';
    }

    if (strpos($_POST['message'], '[url=') || strpos($_POST['message'], '[link=') || strpos($_POST['message'], 'http://')) {
        $messageError = 'Sorry, URLs are not supported in message bodies.';
    }


    $emailcontactform_ip = $_SERVER['REMOTE_ADDR'];
    if (filter_var($emailcontactform_ip, FILTER_VALIDATE_IP)) {
        if ($emailcontactform_ip == "91.201.66.76") {
            $invalidiprange = 'Your IP has been identified as one used by spammers. You are denied access.';
        }
    }

    if ($captcha && $securimage->check($_POST['captcha_code']) == false) {
        $captchaError = 'The security code you entered was incorrect.';
    } else {
        if (!isset($emailFromError) && !isset($emailFromNameError) && !isset($messageError) && strlen($_POST['NameHide']) < 1 && !isset($invalidiprange)) {
            // sending the email:

            $_POST['emailFromName'] = preg_replace("/\r/", "", $_POST['emailFromName']);
            $_POST['emailFromName'] = preg_replace("/\n/", "", $_POST['emailFromName']);

            // $_POST['emailFrom'] = filter_var($_POST['emailFrom'], FILTER_SANITIZE_EMAIL);

            $_POST['emailFrom'] = preg_replace("/\r/", "", $_POST['emailFrom']);
            $_POST['emailFrom'] = preg_replace("/\n/", "", $_POST['emailFrom']);

            $mailFromName = $_POST['emailFromName'];
            $mailFrom = $_POST['emailFrom'];
            $message = $_POST['message'];



            $email_to_add = trim($email_to);
            if (strlen($email_to) < 7) {
                $email_to_add = get_option('admin_email');
            }

            // $email_to_add = $email_to;
            $subject = "Email From Website: " . get_bloginfo('name');
            $body = "<blockquote>The following message was sent from the following webpage " . $_SERVER['HTTP_REFERER'] . " <br /><br />
                                    Name: " . $mailFromName . "<br/>
                                    Email: " . $mailFrom . "<br/>
                                    Message:<br/>" . $message . "<br/>
                                    <hr/>
                                    Remote Address (the emailer's IP address): " . $_SERVER['REMOTE_ADDR'] . "<br/>
                                    Browser: " . $_SERVER['HTTP_USER_AGENT'] . "
                                    <hr/>
                                    </blockquote>";

            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $mailFromName . '<' . $mailFrom . '>' . "\r\n";
            $headers .= 'Bcc: webmaster@eng.ufl.edu' . "\r\n";  //switch to current site webmaster email


            // if (filter_var($_POST['emailFrom'], FILTER_VALIDATE_EMAIL)) { mail($email_to_add, $subject, $body, $headers); }
            wp_mail($email_to_add, $subject, $body, $headers);

            // thank you response
            $alldone = "true";
            echo "<h3>Thank you for contacting us</h3>";
            echo "<p><em>We will respond shortly.</em></p>";
        }
    }
}


?>

<?php if (!$alldone) { 
    ?>

    <a name="form"></a>
<?php if ( !empty( $title ) ) { echo $before_title . $title . $after_title; }; ?>

    
    <div id="widgetEmail">
        
        <form action="#form" method="post" id="sendEmail">
            <input type="text" style="display:none;" value="" name="NameHide" />
            <div><input type="text" class="widget-email" name="emailFromName" <?php
    if (!$_POST['message']) {
        echo "ONFOCUS=\"clearDefault(this)\"";
    }
    ?> id="emailFromName" value="<?php
            if ($_POST['emailFromName'] == '' || $_POST['emailFromName'] == "Your Name") {
                echo "Your Name (required)";
            } else {
                echo $_POST['emailFromName'];
            }
    ?>" /><?php if (isset($emailFromNameError))
                        echo '<p class="widget_small error">' . $emailFromNameError . '</p>'; ?></div>
            <div><input type="text" class="widget-email" name="emailFrom" id="emailFrom" <?php
                    if (!$_POST['emailFrom']) {
                        echo "ONFOCUS=\"clearDefault(this)\"";
                    }
    ?> value="<?php
            if ($_POST['emailFrom'] == '' || $_POST['emailFrom'] == "Your Email") {
                echo "Your Email (required)";
            } else {
                echo $_POST['emailFrom'];
            }
    ?>" /><?php if (isset($emailFromError))
                        echo '<p class="widget_small error">' . $emailFromError . '</p>'; ?></div>
            <div><textarea rows="4" name="message" class="widget-email" id="message" <?php
                    if (!$_POST['message']) {
                        echo "ONFOCUS=\"clearDefault(this)\"";
                    }
    ?>><?php
            if ($_POST['message'] == '' || $_POST['message'] == "Your Message") {
                echo "Your Message (required)";
            } else {
                echo $_POST['message'];
            }
    ?></textarea>
                    <?php
                    if (isset($messageError))
                        echo '<p class="widget_small error">' . $messageError . '</p>';

                    if (isset($invalidiprange))
                        echo '<p class="widget_small error">' . $invalidiprange . '</p>';
                    ?>
            </div>
            <?php if ($captcha) { ?>
            <div id="captcha-wrap">
                <p class="instructions">Please type the characters you see in the image below.</p>
                <img id="captcha" src="<?php echo bloginfo('template_url') ?>/library/securimage/securimage_show.php" alt="CAPTCHA Image" />
                <a href="#" onclick="document.getElementById('captcha').src = '<?php echo bloginfo('template_url') ?>/library/securimage/securimage_show.php?' + Math.random(); return false"><img class="reload" src="<?php echo bloginfo('template_url') ?>/images/captcha-reload.png" alt="reload captcha" /></a>
                <input type="text" name="captcha_code" size="5" maxlength="4" />
                <?php
                if (isset($captchaError))
                    echo '<p class="wiget_small error">' . $captchaError . '</p>';
                ?>
            </div>
            <?php } ?>
            <button type="submit" id="submit">Send Email &raquo;</button><input type="hidden" name="submitted" id="submitted" value="true" />
        </form>
        <div class="clear"></div>
    </div>

            <?php } ?>