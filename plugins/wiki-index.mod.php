<?php

function wiki_directory_listing($dir, $pre='', $chop=0) {
  if (is_dir($dir) && $handle = opendir($dir)) {
  	echo("<ul>\n");
		while (false !== ($file = readdir($handle))) {
      if ($file != '.' && $file != '..') {
        if($chop>0) { $file =  substr ($file , 0 , strlen($file)-$chop); }
        $label = htmlspecialchars(urldecode($file));
        $file = htmlspecialchars($file);
        echo('<li><a href="'.htmlspecialchars($pre).$file.'">'.$label."</a></li>\n");
      }
    }
    closedir($handle);
  	echo("</ul>\n");
  } else {
		wiki_perror('Cannot access directory: '.$dir);
		return false;
	}
}


$wiki_pages[$p_wiki] = array( 
  'desc' => 'index of internal pages',
  'code' => function() {
		ksort($GLOBALS['wiki_pages']);
    echo('<ul>');
    foreach($GLOBALS['wiki_pages'] as $file => $f) {
      $file = htmlspecialchars($file);
      echo('<li><a href="?page='.$file.'">'.$file.'</a></li>');
      foreach($GLOBALS['wiki_pages'][$file] as $key => $val) if($key != 'code') {
        echo('<dd>'.htmlspecialchars("$key = $val").'</dd>');
      }
    }
    echo('</ul>');
  }
);

$wiki_pages[$p_wiki.'/index'] = array(
  'desc' => 'index of pages',
	'code' => function() {
		wiki_directory_listing($GLOBALS['dir']['pages'], '?page=', strlen($GLOBALS['page_ext']));
});

$wiki_pages[$p_wiki.'/easter'] = array(
  'desc' => 'this plugin disables all easter-eggs',
	'code' => function() { ?>
  	<h1>GO AWAY<span onclick="alert('WOOHOO');window.location.href='http://video.google.com/videoplay?docid=2786893074222732218'">!</span>
  	THIS WIKI CONTAINS NO EASTER EGGS!</h1>
<?php });

