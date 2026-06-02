<?php

namespace Drupal\easyappointments\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for configuring Easy!Appointments settings.
 */
class EasyAppointmentsSettingsForm extends ConfigFormBase
{

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames()
    {
        return ['easyappointments.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'easyappointments_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('easyappointments.settings');

        $form['easyappointments_url'] = [
            '#type' => 'url',
            '#title' => $this->t('Easy!Appointments URL'),
            '#description' => $this->t('Enter the URL to your Easy!Appointments booking page.'),
            '#default_value' => $config->get('easyappointments_url'),
            '#required' => TRUE,
        ];

        $form['configuration_notice'] = [
            '#type' => 'inline_template',
            '#template' => '<details style="cursor:pointer !important;width:100% !important;display:block !important;background:#fff8e8 !important;padding:0 !important;border:1px solid #f5c06f !important;border-radius:6px !important;margin-top:20px !important;" open=""><summary style="font-weight:bold !important;padding:12px 16px !important;margin:0 !important;list-style:none !important;user-select:none !important;width:100% !important;display:block !important;color:#856404 !important;background:#ffeeba !important;border-radius:5px 5px 0 0 !important;border-bottom:1px solid #f5c06f !important;">⚠️ Easy!Appointments 1.6.0 - Required Configuration</summary><div style="padding:16px !important;background:#fff8e8 !important;color:#856404 !important;"><p style="margin-top:0 !important;">Easy!Appointments v1.6.0 introduced stricter HTTP security headers that block iframe embedding by default. If your booking form is not loading inside the page, you need to apply the following one-line fix to your Easy!Appointments installation.</p><p><strong>Step 1:</strong> Open the file <code style="background:#ffeeba !important;padding:2px 6px !important;border-radius:3px !important;color:#856404 !important;">application/hooks/security_headers.php</code> in your Easy!Appointments installation.</p><p><strong>Step 2:</strong> Find the following line:</p><input type="text" readonly="" value="header(\'X-Frame-Options: SAMEORIGIN\');" onclick="this.select();" style="width:100% !important;padding:10px !important;font-family:monospace !important;background:#f5f5f5 !important;border:1px solid #f5c06f !important;border-radius:4px !important;margin-bottom:12px !important;color:#333 !important;"><p><strong>Step 3:</strong> Replace it with:</p><input type="text" readonly="" value="header(&quot;X-Frame-Options: ALLOWALL&quot;);" onclick="this.select();" style="width:100% !important;padding:10px !important;font-family:monospace !important;background:#f5f5f5 !important;border:1px solid #f5c06f !important;border-radius:4px !important;margin-bottom:12px !important;color:#333 !important;"><p><strong>Step 4:</strong> Save the file and reload your Joomla page — the booking form should now embed correctly.</p><p style="margin-bottom:0 !important;"><strong>Note:</strong> This fix is only required if your Easy!Appointments installation is hosted on a different domain from your Joomla site (e.g. bookings.example.com vs example.com). If Easy!Appointments lives in a subfolder of the same domain (e.g. example.com/easyappointments), iframe embedding works without this change.</p></div></details>',
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        parent::validateForm($form, $form_state);

        // Validate the URL.
        if (!filter_var($form_state->getValue('easyappointments_url'), FILTER_VALIDATE_URL)) {
            $form_state->setErrorByName('easyappointments_url', $this->t('The URL is not valid.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('easyappointments.settings')
            ->set('easyappointments_url', $form_state->getValue('easyappointments_url'))
            ->save();

        parent::submitForm($form, $form_state);
    }

}
