<?php

require_once('wiki-index.mod.php'); //wiki_directory_listing()

$wiki_pages[$p_wiki.'/downloads'] = array(
	'desc' => 'File repository plug-in',
	'code' => function() {
		if(!isset($GLOBALS['dir']['downloads'])) $GLOBALS['dir']['downloads'] = './downloads/';
		wiki_directory_listing($GLOBALS['dir']['downloads'], $GLOBALS['dir']['downloads']);
});
