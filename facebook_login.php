<?php
include('config.php');
require_once './vendor/autoload.php';

$fb = new \Facebook\Facebook([
  'app_id' => FB_APP_ID,
  'app_secret' => FB_APP_SECRET,
  'default_graph_version' => 'v3.2',
]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/facebook/facebook/fb_callback.php', $permissions);

header('Location: ' . filter_var($loginUrl, FILTER_SANITIZE_URL));
?>
