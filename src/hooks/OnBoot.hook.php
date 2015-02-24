<?php
/**
 * SSO - A single sign-on module for Sentora.
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-sso
 * @license https://github.com/supared/sentora-sso/blob/master/LICENSE
 * @version 1.0.0
 */
global $zdbh;

require_once __DIR__ . '/../libs/SSO.php';

$conf = json_decode(ctrl_options::GetSystemOption('sso_config'));

if (!$conf->disable_sso_login && isset($_REQUEST['ssoToken']) && isset($_REQUEST['ssoInit'])) {

    runtime_hook::Execute('OnLogout');
    ctrl_auth::KillSession();
    ctrl_auth::KillCookies();

    $sso = SSO::getInstance();
    $sso->setKey($conf->key);
    $sso->setIv($_REQUEST['ssoInit']);

    $credentials = $sso->decrypt($_REQUEST['ssoToken'])->ssoData();

    if (isset($credentials['uid']) && isset($credentials['user'])) {
        $sql = $zdbh->prepare("SELECT ac_id_pk, ac_user_vc"
            . " FROM x_accounts"
            . " WHERE ac_id_pk = :uid"
            . " AND ac_user_vc = :username"
            . " AND ac_deleted_ts IS NULL"
            . " AND ac_enabled_in = 1");
        $sql->bindParam(':uid', $credentials['uid']);
        $sql->bindParam(':username', $credentials['user']);
        $sql->execute();

        $authenticated = $sql->fetch();

        if ($authenticated) {
            ctrl_auth::SetUserSession($authenticated['ac_id_pk'], false);
            $log_user_auth = $zdbh->prepare("UPDATE x_accounts SET ac_lastlogon_ts=" . time() . " WHERE ac_id_pk= :uid");
            $log_user_auth->bindParam(':uid', $authenticated['ac_id_pk']);
            $log_user_auth->execute();
        } else {
            header("location: ./?invalidlogin&type=sso");
            exit();
        }
    }
}

if ($conf->disable_form_login) {
    die("SSO authentication failed and form-based authentication has been disabled!");
}
