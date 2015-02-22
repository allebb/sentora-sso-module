<?php
/**
 * SSO - A single sign-on module for Sentora.
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-sso
 * @license https://github.com/supared/sentora-sso/blob/master/LICENSE
 * @version 1.0.0
 */
require_once __DIR__ . '/../libs/SSO.php';
$conf = json_decode(ctrl_options::GetSystemOption('sso_config'), true);
$sso = SSO::getInstance($conf);
