<?php

/**
 * Hooks for ULogin extension
 *
 * @file
 * @ingroup Extensions
 */
class ULoginHooks
{
    static function onUserLoginForm(&$tpl)
    {
        global $wgULoginProviders;
        global $wgULoginHidden;
        global $wgULoginDisplay;
        $header = $tpl->get('header');

        $header .= '<script src="//ulogin.ru/js/ulogin.js"></script>' .
            '<p><strong>' . wfMessage('ulogin-login-via-social-text')->text() . ':</strong></p>' .
            '<div id="uLogin" data-ulogin="display=' . $wgULoginDisplay . ';fields=first_name,last_name,nickname,email;providers=' . $wgULoginProviders . ';hidden=' . $wgULoginHidden . ';redirect_uri=' . urlencode(Title::newMainPage()->getFullUrl()) . '"></div>' .
            '<p><strong>' . wfMessage('ulogin-login-via-standart-text')->text() . '</strong></p>';

        $tpl->set('header', $header);
    }

    static function onUserLoadFromSession($user)
    {
        global $wgOut;
        if (isset($_POST['token'])) {
            $uLoginUser = json_decode(file_get_contents('http://ulogin.ru/token.php' . '?' . http_build_query([
                    'token' => $_POST['token'],
                    'host' => $_SERVER['HTTP_HOST'],
                ])), true);

            $username = $uLoginUser['nickname'] . '-' . $uLoginUser['uid'];

            $u = User::newFromName($username);

            if ($u->getId() == 0) {
                $u->addToDatabase();
                $u->setRealName($uLoginUser['first_name'] . ' ' . $uLoginUser['last_name']);
                $u->setEmail($uLoginUser['email']);
                $u->setPassword(md5($username . $uLoginUser['email']));
                $u->setToken();
                $u->saveSettings();

                $u->sendConfirmationMail();

                $ssUpdate = new SiteStatsUpdate(0, 0, 0, 0, 1);
                $ssUpdate->doUpdate();
            }

            $u->setOption("rememberpassword", 1);
            $u->setCookies();

            $wgOut->redirect(Title::newMainPage()->getFullUrl());
        }
        return true;
    }
}