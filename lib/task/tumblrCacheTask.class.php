<?php
/**
 * Cache tumbler admin homepage.
 */
class tumblrCacheTask extends sfBaseTask
{
  public function configure()
  {
    // use frontend app so we get app.yml config params
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
    ));

    $this->namespace = 'tumblr';
    $this->name      = 'cache';
  }

  public function execute($arguments = array(), $options = array())
  {
    // load config
    $blog_name = sfConfig::get('app_tumblr_blog_name');
    $email = sfConfig::get('app_tumblr_email');
    $password = sfConfig::get('app_tumblr_password');

    // sanity check
    if (empty($blog_name) || empty($email) || empty($password))
    {
      echo "Blog name, email, and password must be configured in app.yml\n";
      echo "Stopped on errors\n";
      return;
    }

    // run
    $b = new sfWebBrowser(array(), 'sfCurlAdapter', array('cookies' => true));

    // login
    echo "Attempting to login...\n";
    $result = $b->post('https://www.tumblr.com/login', array('email'=>$email, 'password'=>$password));
    if ($result->responseIsError())
    {
      echo "Login Failed\n";
    }

    // cache to a file
    $blog = $b->get('http://www.tumblr.com/blog/'.$blog_name);
    if (CachedFiles::saveFile($blog_name, $blog->getResponseText()))
    {
      echo "Saved file\n";
    }
    else
    {
      echo "Failed to save file\n";
    }

    CachedFiles::removeOld();

    echo "Done\n";
  }
}
