<?PHP
/* Copyright Lime Technology LLC.
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2, or (at your option)
 * any later version.
 */
?>
<?php
// Set our timezone
date_default_timezone_set(substr(readlink("/etc/localtime-copied-from"), 20));

include "plugins/webGui/include/app.php";

// MORE: put this in a webGui config file or just get rid of it?
//$defaultLayout = "classic_layout.php";
$defaultLayout = "default_layout.php";

// Extract the 'querystring'
// variables provided by emhttp:
//   path=<path>   page path, e.g., path=Main/Disk
//   prev=<path>   prev path, e.g., prev=Main (used to deterine if page was refreshed)
extract($_GET);

// Define some paths
$docroot = $_SERVER['DOCUMENT_ROOT'];

// The current "task" is the first element of the path
$task = strtok($path, "/");

// Get the webGui configuration preferences
extract(parse_plugin_cfg("webGui", true));

// Read emhttp status
$var = parse_ini_file("/var/local/emhttp/var.ini");
$sec = parse_ini_file("/var/local/emhttp/sec.ini", TRUE);
$devs = parse_ini_file("/var/local/emhttp/devs.ini", TRUE);
$disks = parse_ini_file("/var/local/emhttp/disks.ini", TRUE);
$users = parse_ini_file("/var/local/emhttp/users.ini", TRUE);
$shares = parse_ini_file("/var/local/emhttp/shares.ini", TRUE);
$sec_afp = parse_ini_file("/var/local/emhttp/sec_afp.ini", TRUE);
$sec_nfs = parse_ini_file("/var/local/emhttp/sec_nfs.ini", TRUE);

// Build the webGui pages first, then plugin pages
$page_array = array();
build_pages("plugins/webGui/*.page");
foreach (glob("plugins/*", GLOB_NOSORT) as $plugin)
  if ($plugin != "plugins/webGui")
    build_pages("$plugin/*.page");

// Here's the page we're rendering
$myPage = $page_array[basename($path)];
$pageroot = $docroot."/".dirname($myPage['file']);

// Giddyup
if (empty($myPage['Layout']))
  include "plugins/webGui/include/$defaultLayout";
else
  include "plugins/webGui/include/{$myPage['Layout']}";
?>
