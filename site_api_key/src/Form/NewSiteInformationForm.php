<?php
/**
 * @file
 * Contains \Drupal\form_overwrite\Form\NewSiteInformationForm.
 */

namespace Drupal\site_api_key\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Provides a user login form.
 */
class NewSiteInformationForm extends SiteInformationForm {
    public function buildForm(array $form, FormStateInterface $form_state) {
    	// Load configuration of site information page.
        $site_config = $this->config('system.site');
        // Load parent form of site information
        $form = parent::buildForm($form, $form_state);
        //Add api key to site information page
        $form['site_information']['site_api_key'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
    		'#description' =>t('Add Site API Key here'),
    		'#required' => TRUE,
			'#default_value' => $site_config->get('site_api_key'),
        ];

        // Check API key value and update submit value.
  		if ( !empty($site_config->get('site_api_key')) && $site_config->get('site_api_key') != '' ) {
    		$form['actions']['submit']['#value'] = t('Update Configuration');
  		}
  		
        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      $this->config('system.site')
        ->set('site_api_key', $form_state->getValue('site_api_key'))
        ->save();

      parent::submitForm($form, $form_state);
    }  
}

