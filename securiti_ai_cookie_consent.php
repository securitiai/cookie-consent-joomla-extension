<?php
// Licensed under the GPL v2
defined('_JEXEC') or die;


class PlgSystemSecuriti_ai_cookie_consent extends JPlugin
{
    private $_insert_html = "1";

    /**
     * Constructor
     *
     * @param object  &$subject The object to observe
     * @param array $config An array that holds the plugin configuration
     *
     * @since   3.9.0
     */
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }


    public function onAfterInitialise()
    {

        $pluginUrl = JURI::base(true) . '/plugins/system/securiti_ai_cookie_consent/';
        $app = JFactory::getApplication();

        if ($app->isClient('site')) {
            try{
                $this->_insert_html="1";
            }catch(Exception $e)
            {
                $this->_insert_html="0";
            }
        }
    }


    public function onAfterRender()
    {

        $pluginUrl = JURI::base(true) . '/plugins/system/securiti_ai_cookie_consent/';
        $app = JFactory::getApplication();

        // only insert the script in the frontend
        if ($app->isClient('site')) {

            // retrieve all the response as an html string

            if ($this->_insert_html == "1") {
                $securiti_ai_cookie_consent_code = $this->params->get('securiti_ai_cookie_consent_code', "");

                if ($securiti_ai_cookie_consent_code != '') {
                    $html_site = $app->getBody();

                    $css = $pluginUrl . 'securiti_ai/html.css';

                    JHtml::_('stylesheet', $css, array('version' => 'auto', 'relative' => true));
                    $securiti_ai_cookie_consent_code = str_replace(array('&lt;', '&gt;', '&quot;'), array('<', '>', '"'), $securiti_ai_cookie_consent_code);

                    $html = '<div id="securiti_ai_cookie_consent">
                                {SECURITI_AI_COOKIE_CONSENT_CODE}
                             </div>';

                    $html = str_replace('{SECURITI_AI_COOKIE_CONSENT_CODE}', $securiti_ai_cookie_consent_code, $html);
                    $html_site = str_replace('</body>', $html . '</body>', $html_site);

                    // override the original response
                    $app->setBody($html_site);
                }
            }
        }
    }
}
