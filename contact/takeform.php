<html>
<head>
<title>Thanks For Contacting Us</title>
</head>
<body>
<?php
  // Change this to YOUR address
  $email = $_POST['cd-email'];
  $realName = $_POST['cd-name'];
  $subject = $_POST['cd-subject'];
  $body = $_POST['cd-textarea'];
  # We'll make a list of error messages in an array
  $messages = array();
# Allow only reasonable email addresses
if (!preg_match("/^[\w\+\-.~]+\@[\-\w\.\!]+$/", $email)) {
$messages[] = "That is not a valid email address.";
}
# Allow only reasonable real names
if (!preg_match("/^[\w\ \+\-\'\"]+$/", $realName)) {
$messages[] = "The real name field must contain only " .
"alphabetical characters, numbers, spaces, and " .
"reasonable punctuation. We apologize for any inconvenience.";
}
# CAREFUL: don't allow hackers to sneak line breaks and additional
# headers into the message and trick us into spamming for them!
$subject = preg_replace('/\s+/', ' ', $subject);
# Make sure the subject isn't blank afterwards!
if (preg_match('/^\s*$/', $subject)) {
$messages[] = "Please specify a subject for your message.";
}

$body = $_POST['cd_textarea'];
# Make sure the message has a body
if (preg_match('/^\s*$/', $body)) {
$messages[] = "Your message was blank. Did you mean to say " .
"something?"; 
}
$body .= "\r\n Company/Organization: " . $_POST['cd_company'];


$recipient = '';

$topic = $_POST['cd-subject_topic'];
if ($topic=="1" || $topic=="0" ){
  $receipient = 'mguo@mit.edu';
  // $recipient = 'swe-exec@mit.edu';
} elseif ($topic=="2" ) {
  $recipient = 'swe-president@mit.edu';
} elseif ($topic=="3" ) {
  $recipient = 'swe-vp-membership@mit.edu';
} elseif ($topic=="4" ) {
  $recipient = 'swe-vp-outreach@mit.edu';
} elseif ($topic=="5" ) {
  $recipient = 'swe-vp-corporate@mit.edu';
} elseif ($topic=="6" ) {
  $recipient = 'swe-vp-campus@mit.edu';
}


# Send message
if (count($messages)) {
    # There were problems, so tell the user and
    # don't send the message yet
    foreach ($messages as $message) {
      echo("<p>".$message".</p>\n");
    }
    echo("<p>Click the back button and correct the problems. " .
      "Then click Send Your Message again.</p>");
  } else {
    # Send the email - we're done
    mail($recipient,
      $subject,
      $body,
      "From: ".$realName ."<"$email.">\r\n" .
      "Reply-To: ".$realName ."<"$email.">\r\n"); 
    echo("<p>Your message has been sent. Thank you!</p>\n");
  }
?>
</body>
</html>