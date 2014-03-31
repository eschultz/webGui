<?PHP
include("/usr/local/emhttp/update.php");
//
if ($_POST['command']) {
  $command = $_POST['command'];
  write_log($command);
  exec($command, $output, $retval);
  foreach ($output as $line)
    write_log($line);
}
?>
