<?php

namespace Drupal\config_export_import\Form;


use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Drupal\Core\Archiver\Zip;
use Drupal\Core\Archiver\ArchiverException;

/**
 * Edit config variable form.
 */
class UploadConfigForm extends FormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'config_manual_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $config_name = '')
    {
        $config_settings = \Drupal::config("config_export_import.settings") ;
        $form['upload'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Upload Configuration Zip'),
            '#upload_location' => 'public://upload',
            '#upload_validators' => [
              'file_validate_extensions' => ['zip'],
            ],
          ];
          $form['path'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Config path location'),
            '#attributes' => ['name' => 'path'],
            '#description' => 'Leave empty if you want put in root path .For example : /block',
        ];
        // $form['export'] = array(
        //     '#type' => 'checkbox',
        //     '#title' => t('Export only if new file config in folder'),
        //     '#default_value' =>  ($config_settings->get('export'))? $config_settings->get('export'): false ,
        // );
        $form['actions'] = ['#type' => 'actions'];
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];
        return $form;

    }



    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
      $helper = \Drupal::service('config_export_import.manager');
        $form_file = $form_state->getValue('upload', 0);
        $path = $form_state->getValue('path');
        if (isset($form_file[0]) && !empty($form_file[0])) {
        $file = File::load($form_file[0]);
       // $file->setPermanent();
      //  $file->save();
        $uri = $file->getFileUri();
        $stream_wrapper_manager = \Drupal::service('stream_wrapper_manager')->getViaUri($uri);
        $file_path = $stream_wrapper_manager->realpath();
        try {
            $config_settings = \Drupal::config("config_export_import.settings") ;
            $root =  $config_settings->get('root');
            $zip = new Zip($file_path);
            $temp_path = DRUPAL_ROOT.$root."/".$path ;
            $zip->extract($temp_path);
            $url =  '/admin/config/development/configuration/manual-import';
            $message = t('Our Config Uploaded in  '.$path .' <a href="@link">Click here</a> to import .', ['@link' => $url]);
            \Drupal::messenger()->addMessage($message);
            // Remove the source zip file if necessary.
            //$zip->remove($file);
            // Shows list (array) of those unzipped files regarding the zip file folder.
            // $list = $zip->listContents();
            //  foreach($list as $config_name){
            //        $file_path = $temp_path."/".$config_name ;
            //        if (file_exists($file_path)) {

            //        }
            //  }
            //     $file_path = $temp_path."/".$config_name ;
            //     if (file_exists($file_path)) {
            //       $dep = $helper->getAllDependencyByCustomFolder($file_path,$temp_path);
            //       foreach($dep as $dep_config_path){
            //         if(!is_string($dep_config_path)){
            //           continue ;
            //         }
            //         $config_name_dep = basename($dep_config_path, '.yml');
            //         switch ($condition) {
            //           case "new_only":
            //                 $helper->importConfig($dep_config_path,false);
            //               break;
            //           case "replace":
            //                $helper->importConfig($dep_config_path,true);
            //             break;
            //         }
            //       }
            //       switch ($condition) {
            //         case "new_only":                     
            //             $helper->importConfig($file_path,false);
            //             break;
            //         case "replace":
            //             $helper->importConfig($file_path,true);
            //           break;
            //       }
            //    }
            // }
          }
          catch (ArchiverException $exception) {
            watchdog_exception('config_export_import', $exception);
            // Some code if the error of unzip will happen.
          }
        }
    }

}
