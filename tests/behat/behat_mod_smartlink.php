<?php
require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

class behat_mod_smartlink extends behat_base
{
    /**
     * Check that mxaimanager is set up with an ai provider instance
     *
     * @Given /^I check that mxaimanager "(?P<has_or_has_not>has|has not)" an AI provider instance configured when$/
     */
    public function i_check_that_mxaimanager_has_or_has_not_an_AI_provider_instance_configured_when(string $has_or_has_not): void {
        $url = new \moodle_url('/local/mxaimanager/view.php', ['view' => 'manage', 'action' => 'index']);
        $this->execute('behat_general::i_visit', [$url->out_as_local_url(false)]);

        $css_selector = '.alert-success';

        if($has_or_has_not === 'has not'){
            $css_selector = '.alert-danger';
        }

        $this->execute('behat_general::should_be_visible',
            [$css_selector, 'css_element']);

    }
}