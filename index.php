<?php
//Settings
$wiki_title='haiwiki';
$template='default';
$index_page='index';
$hash='sha512';
$passwords=array( //multiple passwords
	hash($hash,'passw')
);

$charset_encoding='UTF-8';
$paranoia = false; //"ultrasecure" mode with less features
1 ? error_reporting(E_ALL | E_STRICT) : error_reporting(0);

$root='?page='; //TODO
$p_wiki='wiki';
$p_widget='widget';
//Directories and Files
$text_ext='.txt';
$html_ext='.html';

$dir['pages']='./pages';
	$page_ext=$text_ext;

$dir['templates']='./templates';
	$template_ext='.tpl.php';

$dir['plugins']='./plugins';
$dir['backup']='./backup';

$dir['cache']='./cache';
	$cache_ext=$html_ext;
	$enable_cache = false;


////////////////////////////////////////////////////////////////
if (get_magic_quotes_gpc()) { //magic_quotes_gpc hack
	echo('<!-- !!! YOU SHOULD BETTER DISABLE MAGIC_QUOTES_GPC IN PHP.INI !!! -->'."\n");
	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);	while (list($key, $val) = each($process)) { foreach ($val as $k => $v) { unset($process[$key][$k]);
	if (is_array($v)) { $process[$key][stripslashes($k)] = $v; $process[] = &$process[$key][stripslashes($k)]; } else { $process[$key][stripslashes($k)] = stripslashes($v); } } }
	unset($process);
}

//Init
foreach($dir as $k => $i) {
	@mkdir($i, 0775, true);
	$dir[$k].='/';
}

$page = $index_page;
if(isset($_GET['template'])) $template = $_GET['template'];
if(isset($_GET['page']) && trim($_GET['page']) != '' && trim($_GET['page']) != '/') $page = trim($_GET['page']);
if($page[strlen($page)-1]=='/') $page[strlen($page)-1]=''; $page = trim($page); //TODO predelat na preg_replace
$page_url = urlencode($page);
$page_title = $page;

@include_once('_config.php');

//Functions
function wiki_perror($text) {
	if(!isset($GLOBALS['wiki_errors'])) { $GLOBALS['wiki_errors']=''; }
	$GLOBALS['wiki_errors'].=htmlspecialchars($text)."\n";
}


function wiki_check_pass($pass) {
	if(!in_array(hash($GLOBALS['hash'],$pass), $GLOBALS['passwords'])) {
		wiki_perror('Wrong password!');
		return false;
	}
	return true;
}

function wiki_is_internal_page($page,$err=false) {
	if(isset($GLOBALS['wiki_pages'][$page])) {
		if($err) echo('THIS IS INTERNAL PAGE AND THEREFORE IT CANNOT BE EDITED!');
		return true;
	}
	return false;
}

function wiki($var, $pre='', $post='') {
	$widget = $GLOBALS['p_widget'].'/'.$var;
	if(wiki_is_internal_page($widget)) {
		$GLOBALS['wiki_pages'][$widget]['code']($pre, $post);
	} elseif(isset($GLOBALS[$var])) {
		echo($pre.nl2br(htmlspecialchars($GLOBALS[$var])).$post);

	} else {
		echo("<!-- !!! VARIABLE '$var' NOT FOUND !!! -->\n");
		return false;
	}
}

$wiki_pages[$p_widget.'/editor'] = array('code' => function() {
	if(wiki_is_internal_page($GLOBALS['page'], true)) return;
	?><form action="?page=<?php echo($GLOBALS['page_url']) ?>" method="POST" id="edit" class="edit">
		<textarea name="content" cols="80"><?php wiki_page_plain(); ?></textarea>
		<input type="password" name="passwd" value="<?php echo(@htmlspecialchars($_POST['passwd'])) ?>" title="wiki password" />
		<input type="submit" value="save" />
	</form><?php
});

$wiki_pages[$p_widget.'/info'] = array('code' => function() {
	?>This site is powered by HaiWiki 2oo9 ;)<br />Written by <a href="http://blog.harvie.cz/">Harvie</a>.<?php
});

$wiki_pages[$p_widget.'/headers'] = array('code' => function() {
	?><!-- INTERNAL HEADERS BEGIN -->
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo($GLOBALS['charset_encoding']) ?>" />
		<link rel='index' title='<?php echo($GLOBALS['wiki_title']); ?>' href='./' />
		<meta name="generator" content="<?php echo($GLOBALS['wiki_title']); ?>" />
		<!-- link rel="alternate" type="application/x-wiki" title="Edit this page" href="?page=<?php echo($GLOBALS['page_url']) ?>&edit=true#___edit___" /><!-- UEB -->
		<!-- INTERNAL HEADERS END --><?php
});


$wiki_format = function(&$content) { return true; };
function wiki_format(&$content) { return $GLOBALS['wiki_format']($content); }
function wiki_page_filename($page) { return $GLOBALS['dir']['pages'].urlencode($page).$GLOBALS['page_ext']; }
function wiki_backup_filename($page) { return $GLOBALS['dir']['backup'].urlencode($page).$GLOBALS['page_ext'].'.'.time(); }

function wiki_page_plain($arg_page = '') {
  $page = $GLOBALS['page'];
  if($arg_page != '') $page = $arg_page;
	if(wiki_is_internal_page($page,true)) return;
	$page_file=wiki_page_filename($page);

	//read
	$content = @file_get_contents($page_file);
	//format
	$content = htmlspecialchars($content);
	//render + unset
	echo($content); unset($content);
}

function wiki_page_content($arg_page = '') {
	$page = $GLOBALS['page'];
	if($arg_page != '') $page = $arg_page;

	if(wiki_is_internal_page($page)) return $GLOBALS['wiki_pages'][$page]['code']();

	$page_file=wiki_page_filename($page);
	if(!is_file($page_file)) wiki_perror("Page not found: $page\nFile not found: $page_file");

	//read
	$content = file_get_contents($page_file);
	//format
	wiki_format($content);
	//render + unset
	echo($content); unset($content);
}


function wiki_save_page($arg_page = '', &$content) {
  $page = $GLOBALS['page'];
  if($arg_page != '') $page = $arg_page;
  $page_file=wiki_page_filename($page);

	if(file_exists($page_file)) {	copy($page_file, wiki_backup_filename($page)); }
	if(!file_put_contents($page_file, $content)) wiki_perror('Cannot write to file: '.htmlspecialchars($page_file));
}

function wiki_dump($out_dir) {
	if ($handle = opendir($GLOBALS['dir']['pages'])) {
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$content = file_get_contents($GLOBALS['dir']['pages'].$file);
				wiki_format($content);
				$content = preg_replace('/\?page=([^"]*)/', '$1'.$GLOBALS['html_ext'] , $content);
				$file =  substr ($file , 0 , strlen($file)-strlen($GLOBALS['page_ext']));
				file_put_contents($out_dir.'/'.$file.'.html', $content);
			}
		}
		closedir($handle);
	}
}

//LOAD PLUGINS
if($handle = opendir($GLOBALS['dir']['plugins'])) {
  while (($plugin = readdir($handle))) {
    if ($plugin[0] != '.' && $plugin[0] != '_' &&
			!is_dir($plugin = $GLOBALS['dir']['plugins'].$plugin)) {
				if(!include_once($plugin)) wiki_perror("Failed to load plugin: $plugin");
		}
	}
	closedir($handle);
}

//do nothing if included...
if(!isset($wiki_include)) {


//Handle POSTs (edit)
if(isset($_POST['content']) && $_POST['content'] != '') {
	if(@wiki_check_pass($_POST['passwd'])) wiki_save_page($page, $_POST['content']);
}

//Handle GETs (render)
$template_file=$dir['templates'].$template.$template_ext;

if(@($_GET['template'] === 'plaintext'))	die(wiki_page_plain());
if(@($_GET['template'] === 'html') || !@include($template_file)) {
	/*echo("<!-- IT'S STRANGE, BUT THIS PAGE WAS RENDERED WITHOUT USE OF TEMPLATES\n".
		"IF YOU HAVEN'T DISABLED TEMPLATES INTENTIONALY, PLEASE CHECK CONFIGURATION. -->\n"
	);*/
	wiki_page_content();
}


}
