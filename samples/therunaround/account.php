<?php

include_once 'lib/core.php';
echo render_header();

$user = User::getLoggedIn();

if (!$user) {
  go_home();
}

// If the user updated their account, handle the form submission

if (!empty($_POST)) {
  if ($user != $_POST['username']) {
    error_log("submitted a your account form for '".$_POST['username'] ."' when user '".$user->username. "' is logged in!");
    go_home();
  }

  $user->name = $_POST['name'];
  $user->email = $_POST['email'];
  if ($_POST['password'] != PASSWORD_PLACEHOLDER) {
    $user->password = $_POST['password'];
  }
  $user->save();
}

echo '<table class="account_table"><tr><td>';
echo '<h3>Account settings for <b>'.$user->getName().'</b></h3>';
echo '<p>You can edit information about yourself here.</p>';
echo render_edit_user_table($user);
echo '</td><td>';

if (is_fbconnect_enabled()) {

  if ($user->is_facebook_user()) {

    // If a user has a password, then it means that they can log in independent of their Facebook
    // account. So they should be given the opportunity to Disconnect and maintain their account
    if ($user->hasPassword()) {
      echo '<h3>Connected To Facebook</h3>';
      echo '<p> Your account is linked with a Facebook account. </p>';
      echo '<a href="disconnect.php">Disconnect from Facebook</a>';
    }

  } else {
    echo '<h3>Connect with Facebook</h3>';
    echo '<p>Do you have a Facebook account? Connect it with The Run Around to share your information here, and see which of your friends are here.</p>';
    echo render_fbconnect_button(($user > 0));
  }
}

echo '</td></tr></table>';

echo render_footer();
