<?php


$settings['hash_salt'] = 'Mm9EjqmiIH8zmw8zqNIqjc_szsmMqMtxyoJNF9KJW8fcTEkQn9XshFteFM6OMbEfQ7yKjV2Dwg';

$settings['update_free_access'] = FALSE;

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';


$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

$settings['entity_update_batch_size'] = 50;


$settings['entity_update_backup'] = TRUE;

$settings['migrate_node_migrate_type_classic'] = FALSE;


$settings['config_sync_directory'] = 'sites/default/files/config_G_Nk8z66uVBhyvUBzOodO0xP7fNYt667LAnsI74EAz7aPT9yrtvxsHDtikZ2lJNlfI4dN4N8Sg/sync';

if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
   include $app_root . '/' . $site_path . '/settings.local.php';
 }
