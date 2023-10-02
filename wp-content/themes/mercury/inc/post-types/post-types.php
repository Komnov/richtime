<?php
$files_list = scandir(__DIR__);
foreach($files_list as $file) {
  $info = new SplFileInfo($file);
  if( $info->getExtension() == 'php' && $file != 'post-types.php' ) {
    include get_template_directory() . '/inc/post-types/' . $file;
  }
}
