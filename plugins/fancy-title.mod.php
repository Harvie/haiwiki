<?php

function wiki_explode_path($dir, $l='[', $p=']', $prefix='?page=') {
  //$temp = split('/', ereg_replace('/+', '/', $dir));
  $temp = explode('/', preg_replace('/\/+/', '/', $dir)); //TODO zkopirovat do jukeboxu
  $out = '';
  for($j=sizeof($temp)-1;$j>=0;$j--) {
    $dir = '';
    for($i=0;$i<(sizeof($temp)-$j);$i++) {
      $dir.=$temp[$i];
      if($i<sizeof($temp)-1) $dir.='/';
    }
    if($temp[$i-1]!='') $out.=$l.'<a href="'.$prefix.rawurlencode($dir).'">'.htmlspecialchars($temp[$i-1]).'</a>'.$p;
  }
  return($out);
}

$wiki_pages[$p_widget.'/page_title'] = array(
'desc' => 'adds clickable page title',
'code' => function($pre, $post) {
	echo(wiki_explode_path($GLOBALS['page'], $pre, $post));
});

