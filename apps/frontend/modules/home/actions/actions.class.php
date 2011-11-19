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
    $files = CachedFiles::getFileNames();
    // sort by newest on top
    rsort($files);
    $this->files = $files;
  }

 /**
  * Delete a cached file
  *
  * @param sfRequest $request A request object
  */
  public function executeDeletefile(sfWebRequest $request)
  {
    $file = isset($_POST['file']) ? $_POST['file'] : null;
    if (CachedFiles::delete($file))
    {
      return $this->renderText(json_encode(array('true')));
    }
    else
    {
      $this->getResponse()->setStatusCode(403);
      return sfView::NONE;
    }
  }
}
