<?php

/**
 * SSO - A single sign-on module for Sentora.
 * @author Bobby Allen (ballen@bobbyallen.me)
 * @copyright (c) 2015, Supared Limited
 * @link https://github.com/supared/sentora-sso
 * @license https://github.com/supared/sentora-sso/blob/master/LICENSE
 * @version 1.0.0
 */
class module_controller extends ctrl_module
{

    /**
     * The HTML to generate for HTML checkboxes that should be checked.
     */
    const CHECKBOX_ENABLED_HTML = "checked=\"checked\"";

    /**
     * Return the template placeholder result for the 'SSO Auth enabled' checkbox.
     * @return string
     */
    public static function getIsSSOAuthEnabled()
    {
        if (!self::ssoConfiguration()->disable_sso_login) {
            return self::CHECKBOX_ENABLED_HTML;
        }
        return null;
    }

    /**
     * Return the template placeholder result for the 'Standard Forms Auth enabled' checkbox.
     * @return string
     */
    public static function getIsFormsAuthEnabled()
    {
        if (!self::ssoConfiguration()->disable_form_login) {
            return self::CHECKBOX_ENABLED_HTML;
        }
        return null;
    }

    /**
     * Return the tempalte placeholder result for the shared encryption key.
     * @return string
     */
    public static function getSharedKey()
    {
        return self::ssoConfiguration()->key;
    }

    /**
     * Display any notice messages for the current request.
     * @return string
     */
    public static function getResult()
    {
        if (isset($_REQUEST['saved']) && $_REQUEST['saved'] == 'true') {
            return ui_sysmessage::shout(ui_language::translate("Configuration changes have been saved successfully!"), "zannounceok");
        }
        if (isset($_REQUEST['saved']) && $_REQUEST['saved'] == 'false') {
            return ui_sysmessage::shout(ui_language::translate("An error occured and the changes to the SSO module configuration could not be saved."), "zannounceerror");
        }
    }

    /**
     * Form action handler
     * @global type $controller
     */
    public static function doUpdateConf()
    {
        global $controller;
        runtime_csfr::Protect();
        $res_state = "false";
        $formvars = $controller->GetAllControllerRequests('FORM');
        if (self::saveConfiguration(array(
                'disable_form_login' => (boolean) !$formvars['forms_auth'],
                'disable_sso_login' => (boolean) !$formvars['sso_auth'],
                'key' => (boolean) $formvars['shared_key'],
            ))) {
            $res_state = "true";
        }
        header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=" . $res_state);
        exit;
    }

    /**
     * Load the SSO module configuration from the database.
     * @return stdClass
     */
    private static function ssoConfiguration()
    {
        return json_decode(ctrl_options::GetSystemOption('sso_config'));
    }

    /**
     * Save the configuration to the database.
     * @return void
     */
    private static function saveConfiguration(array $options)
    {
        return ctrl_options::SetSystemOption('sso_config', json_encode($options));
    }
}
