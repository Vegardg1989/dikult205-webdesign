<?php
// Shows one message passed in the query string, like Retrobaser's ?error= codes.
$messages = array(
	'emptyfields' => 'Fill in all fields.',
	'invalidemail' => 'Invalid e-mail.',
	'invalidusername' => 'Invalid username (letters and numbers only).',
	'passwordlength' => 'Password too short (at least 6 characters).',
	'passwordcheck' => 'Your passwords did not match.',
	'usernametaken' => 'Username is already taken, please choose another.',
	'emailtaken' => 'That e-mail is already registered. Use Forgot Password if you need a new one.',
	'nomatch' => 'Either the e-mail address or password do not match, or the account is not activated yet.',
	'emailnomatch' => 'That e-mail address is not registered.',
	'sqlerror' => 'System error, please try again later.',
	'adminonly' => 'Only CRA admins can do that.',
	'loginfirst' => 'Please log in first.',
);
$notices = array(
	'signup' => '<h3>Thank you for registering!</h3><p>A confirmation e-mail has been sent to your address. Click the link in it to activate your account.</p>',
	'activated' => 'Your account is now active. You may now log in.',
	'reset' => 'Your password has been changed. You will receive the new, temporary password by e-mail.',
	'changed' => 'Your password has been changed.',
	'saved' => 'The news post is saved.',
	'deleted' => 'The news post is deleted.',
	'rated' => 'Thanks for rating!',
	'loggedout' => 'You are now logged out.',
);
if (isset($_GET['error']) && isset($messages[$_GET['error']])) {
	echo '<p class="error">' . $messages[$_GET['error']] . '</p>';
}
if (isset($_GET['done']) && isset($notices[$_GET['done']])) {
	echo '<p class="notice">' . $notices[$_GET['done']] . '</p>';
}
