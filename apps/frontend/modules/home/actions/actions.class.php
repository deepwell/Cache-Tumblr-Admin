<?php

/**
 * home actions.
 *
 * @package    sf_sandbox
 * @subpackage home
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class homeActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    // get list of files
    $files = array();
    $upload_directory = sfConfig::get('sf_upload_dir');
    if ($handle = opendir($upload_directory))
    {
      while (false !== ($file = readdir($handle)))
      {
        if (substr($file, -4) == 'html')
        {
          $files[] = $file;
        }
      }
      closedir($handle);
    }

    // sort by newest on top
    rsort($files);

    $this->files = $files;
  }
}
