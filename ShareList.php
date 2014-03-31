<?php
// User share data exists only if array is Started
if ($var['fsState']!="Started") {
  echo "<p class='notice'>Array must be Started to view User Shares.</p>";
  return;
}
// Share size per disk
$preserve = ($path==$prev);
$ssz = array();
foreach (glob( "/var/local/emhttp/plugins/webGui/*.ssz", GLOB_NOSORT) as $entry) {
  if ($preserve) {
    $ssz[basename($entry, ".ssz")] = parse_ini_file($entry);
  }
  else {
    unlink($entry);
  }
}
function indicator($color) {
  return "<a href='#' class='info' onClick='return false'><img src='plugins/webGui/images/$color.gif' class='icon'>
            <span>
              <img src='plugins/webGui/images/green-on.gif' class='icon'>All files on array<br>
              <img src='plugins/webGui/images/yellow-on.gif' class='icon'>Some or all files on  cache<br>
            </span>
          </a>";
}
function cmd($name) {
  return "/usr/local/emhttp/plugins/webGui/share_size $name /var/local/emhttp/plugins/webGui/$name.ssz";
}
$tr_num=0;
?>
<table class="share_status <?=$display['view']?>">
  <tr>
    <td>Name</td>
    <td>Comment</td>
    <td>Size</td>
    <td>Free</td>
    <td>View</td>
  </tr>
  <?foreach ($shares as $name => $share):?>
    <?if (array_key_exists("{$share['name']}", $ssz)):?>
      <tr class="tr_row<?=$tr_num++ & 1?>">
        <td><?=indicator($share['color']);?><a href="<?=$path?>/Share?name=<?=urlencode($name)?>"><?=$share['name']?></a></td>
        <td><?=$share['comment']?></td>
        <td><?=my_scale($ssz[$share['name']]['total']*1024, $units).' '.$units?></td>
        <td><?=my_scale($share['free']*1024, $units).' '.$units?></td>
        <td><a href="<?=$path?>/Browse?dir=/mnt/user/<?=urlencode($share['name'])?>"><img src="plugins/webGui/images/folder_explore.png" title="Browse /mnt/user/<?=$share['name']?>"></a></td>
      </tr>
      <?foreach ($ssz[$share['name']] as $disk_name => $disk_size):?>
        <?if ($disk_name=="total") continue;?>
        <tr class="share_status_size tr_row<?=$tr_num++ & 1?>">
          <td><?=my_disk($disk_name)?>:</td>
          <td></td>
          <td><?=my_scale($disk_size*1024, $units).' '.$units?></td>
          <td><?=my_scale($disks[$disk_name]['fsFree']*1024, $units).' '.$units?></td>
          <td></td>
        </tr>
      <?endforeach;?>
    <?else:?>
      <tr class="tr_row<?=$tr_num++ & 1?>">
        <td><?=indicator($share['color']);?><a href="<?=$path?>/Share?name=<?=urlencode($name)?>"><?=$share['name']?></a></td>
        <td><?=$share['comment']?></td>
        <td><a href="/update.htm?cmd=<?=cmd($share['name']);?>" target="progressFrame">Compute...</a></td>
        <td><?=my_scale($share['free']*1024, $units).' '.$units?></td>
        <td><a href="<?=$path?>/Browse?dir=/mnt/user/<?=urlencode($share['name'])?>"><img src="plugins/webGui/images/folder_explore.png" title="Browse /mnt/user/<?=$share['name']?>"></a></td>
      </tr>
    <?endif;?>
  <?endforeach;?>
</table>

<hr>

<form method="GET" action="<?=$path?>/Share">
  <input type="hidden" name="name" value="">
  <p class="centered"><input type="submit" value="Add Share"></p>
</form>
