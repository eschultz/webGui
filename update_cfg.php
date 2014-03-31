<?PHP
// update_cfg() is used to update selected name=value variables in a cfg file.  Note that calling
// this function will write the cfg file on the flash.
// The $_POST variable contains a list of key/value parameters to be updated in the file. There are a
// number of special parameters prefixed with a hash '#' character:
// $file - the path of the cfg file to be updated.  It does not need to previously exist.
// #section - If present, then the ini file consists of a set of named sections, and all of the $cfg
// parameters apply to this one particular section.  If omitted, then it's just a flat ini file without
// sections.
// #include - specifies name of an include file to read in before saving the file contents
// #cleanup - if present then parameters with empty strings are omitted from being written to the file
//
include("/usr/local/emhttp/update.php");
//
$file = $_POST['#file'];
if ($file) {
  $section = isset($_POST['#section']) ? $_POST['#section'] : FALSE;
  $cleanup = isset($_POST['#cleanup']);

  $keys = @parse_ini_file($file, $section);

  if (isset($_POST['#include'])) include "/usr/local/emhttp/{$_POST['#include']}";

  $text = "# Generated settings:\n";
  if ($section) {
    foreach ($_POST as $key => $value) if (substr($key,0,1)!='#') $keys[$section][$key] = $value;
    foreach ($keys as $section => $block) {
      $text .= "[$section]\n";
      foreach ($block as $key => $value) {
        if (strlen($value) || !$cleanup) {
          $text .= "$key=\"$value\"\n";
        }
      }
    }
  }
  else {
    foreach ($_POST as $key => $value) if (substr($key,0,1)!='#') $keys[$key] = $value;
    foreach ($keys as $key => $value) {
      if (strlen($value) || !$cleanup) {
        $text .= "$key=\"$value\"\n";
      }
    }
  }
  @mkdir(dirname($file));
  file_put_contents($file, $text);
}
?>
