<?PHP
include("/usr/local/emhttp/update.php");
//
if ($_POST['file']) {
  $file = $_POST['file'];
  write_log("Saving file $file");
  $text = str_replace("\r\n", "\n", $_POST['text']);
  $text = str_replace("\r", "\n", $text);
  file_put_contents($file, $text);
}
?>
