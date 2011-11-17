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
    $cache_directory = sfConfig::get('sf_upload_dir');

    // sanity check
    if (empty($blog_name) || empty($email) || empty($password))
    {
      echo "Blog name, email, and password must be configured in app.yml\n";
      echo "Stopped on errors\n";
      return;
    }

    // Run
    $b = new sfWebBrowser(array(), 'sfCurlAdapter', array('cookies' => true));

    // Login
    echo "Attempting to login...\n";
    $res = $b->post('https://www.tumblr.com/login', array('email'=>$email, 'password'=>$password));
    if ($res->responseIsError())
    {
      echo "Login Failed\n";
    }

    // get the blog
    $blog = $b->get('http://www.tumblr.com/blog/'.$blog_name);

    // cache to a file
    $filename = $cache_directory.DIRECTORY_SEPARATOR.$blog_name.'-'.date('Y-m-d_H:i:s').'.html';
    if (!file_put_contents($filename, $blog->getResponseText()))
    {
      echo "Failed to save cache file\n";
    }

    echo "Done\n";
  }
}
