<?php
include('config.php');
require_once '../vendor/autoload.php';

$fb = new \Facebook\Facebook([
  '3756658971282539' => FB_APP_ID,
  '4b29d7b56156ef963c1e924c04ad9187' => FB_APP_SECRET,
  'default_graph_version' => 'v3.2',
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    header('Location: login.php');
    exit;
}

$oAuth2Client = $fb->getOAuth2Client();
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
$tokenMetadata->validateAppId(FB_APP_ID);
$tokenMetadata->validateExpiration();

if (!$accessToken->isLongLived()) {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
}

$response = $fb->get('/me?fields=id,name,email', $accessToken);
$userNode = $response->getGraphUser();

$email = $userNode->getField('email');
$name = $userNode->getField('name');

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO users (name, email, oauth_provider) VALUES (?, ?, 'facebook')");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $user_id = $stmt->insert_id;
} else {
    $stmt->bind_result($user_id);
    $stmt->fetch();
}

$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $name;

header("Location: dashboard.php");
?>
