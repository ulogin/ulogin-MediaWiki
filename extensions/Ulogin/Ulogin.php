<?php

if (!defined('MEDIAWIKI')) {
    die('Not an entry point.');
}

define('Ulogin_VERSION', '1.0');

$dir = dirname(__FILE__) . '/';

$wgUloginPrividerrs = 'vkontakte,odnoklassniki,mailru,facebook';
$wgUloginHidden = 'twitter,google,yandex,livejournal,openid';

$wgExtensionCredits['validextensionclass'][] = array(
    'path' => __FILE__,
    'name' => 'Ulogin',
    'author' => 'Cramen',
    'url' => 'https://www.mediawiki.org/wiki/Extension:Example',
    'description' => 'Авторизация через социальные сети',
    'version' => Ulogin_VERSION,
);

$wgExtensionMessagesFiles['ulogin'] = dirname(__FILE__) . '/Ulogin.i18n.php';


$wgHooks['UserLoadFromSession'][] = 'fnUloginAuthenticateHook';


function fnUloginAuthenticateHook($user, &$result)
{
    global $IP, $wgLanguageCode, $wgRequest, $wgOut;

    if (isset($_REQUEST["title"])) {
        $lg = Language::factory($wgLanguageCode);

        if ($_REQUEST["title"] == $lg->specialPage("Userlogin")) {

            if ($_POST && isset($_POST['token'])) {
                $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
                $user = json_decode($s, true);

                $username = $user['nickname'] . '-' . $user['uid'];

                $u = User::newFromName($username);

                require_once("$IP/includes/WebStart.php");

                if ($u->getId() == 0) {

                    $u->addToDatabase();
                    $u->setRealName($user['first_name'] . ' ' . $user['last_name']);
                    $u->setEmail($user['email']);
                    $u->setPassword(md5($username)); // do something random
                    $u->setToken();
                    $u->saveSettings();

                    $u->sendConfirmationMail();

                    $ssUpdate = new SiteStatsUpdate(0, 0, 0, 0, 1);
                    $ssUpdate->doUpdate();

                }

                $u->setOption("rememberpassword", 1);
                $u->setCookies();
                $user = $u;

                $wgOut->redirect(Title::newMainPage()->getFullUrl());

            }
        }
        else if ($_REQUEST["title"] == $lg->specialPage("Userlogout")) {
            $user->logout();
        }
    }

    return true;
}