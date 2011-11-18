<?php
class CachedFiles
{
  /**
   * Get list of previously cached files.
   * @return array of file names
   */
  public static function getFileNames()
  {
    $cache_directory = sfConfig::get('sf_upload_dir');

    $files = array();
    if ($handle = opendir($cache_directory))
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

    return $files;
  }

  /**
   * Save contents to a cached file.
   * @boolean true if successful
   */
  public static function saveFile($name, $content)
  {
    $cache_directory = sfConfig::get('sf_upload_dir');

    $filename = $cache_directory.DIRECTORY_SEPARATOR.$name.'-'.date('Y-m-d_H:i:s').'.html';
    if (!file_put_contents($filename, $content))
    {
      return false;
    }

    return true;
  }

  /**
   * Remove any files that are older than 3 days.
   */
  public static function removeOld()
  {
    $cache_directory = sfConfig::get('sf_upload_dir');
    $today = new DateTime();
    $max_days_old = 3;

    $files = self::getFileNames();
    foreach ($files as $file)
    {
      // get the date
      preg_match('/([0-9]{4}-[0-9]{2}-[0-9]{2})_([0-9]{2}:[0-9]{2}:[0-9]{2})/i', $file, $result);
      if (isset($result[1]) && isset($result[2]))
      {
        $date = $result[1];
        list($hour, $minute, $second) = explode(':', $result[2]);

        $file_date = new DateTime($date);
        $file_date->setTime($hour, $minute, $second);
        $interval = $file_date->diff($today);
        if ($interval->format('%a') >= $max_days_old)
        {
          $filename = $cache_directory.DIRECTORY_SEPARATOR.$file;
          unlink($filename);
        }
      }
    }
  }
}
