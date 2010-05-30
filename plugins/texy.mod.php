<?php
$texy_path = '_texy.min.php';
$disable_iconv = false; //workaround for crippled php.ini & iconv.so...

$wiki_pages[$p_wiki.'/markup'] = array(
'desc' => 'markup plugin using Texy! markup language',
'texy' => $texy_path,
'code' => function() { ?>
	<a href="http://texy.info/"><img src="http://texy.info/images/texy-powered.png" style="float:right;" /></a>
	<h2>This wiki is running Texy!</h2>
	<ul>
		<li><a href="?page=wiki/syntax">Internal Syntax Reference</a></li>
		<li><a href="http://texy.info/syntax">Syntax Reference</a></li>
		<li><a href="http://texy.info/syntax-podrobne">Full Syntax Reference [CZ]</a></li>
		<li><a href="http://texy.info/">Homepage</a></li>
	</ul>
	<br style="clear: right;" />
<?php });



if($disable_iconv) {
  //texy-iconv hack:
  function iconv($in_charset, $out_charset, $str) { return $str; }
  function iconv_strlen ($str, $charset = '') { return strlen($str); }
  function iconv_substr ($str, $offset, $length ='', $charset) { if($length=='') $length=strlen($str); return substr($str, $offset, $length); }
}

function wiki_texy_InlineHandler($parser, $matches, $name) {
  list(, $mContent, $mMod) = $matches;
  $texy = $parser->getTexy();

  $tag = 'a';
  $el = TexyHtml::el($tag);
  $mod = new TexyModifier($mMod);
  $mod->decorate($texy, $el);
  if($name == 'wikilink') {
    $el->attrs['href'] = '?page='.urlencode($mContent);
  } else {
    $el->attrs['href'] = $mContent;
  }
  $el->attrs['class'] = $name;
  $el->setText($mContent);
  $parser->again = TRUE;
  return $el;
}

function wiki_texy_phraseHandler($invocation, $phrase, $content, $modifier, $link) {
  if (!$link) return $invocation->proceed();
  if (Texy::isRelative($link->URL)) {
    $link->URL = '?page=' . urlencode($link->URL);
  } elseif (substr($link->URL, 0, 5) === 'wiki:') {
    $link->URL = 'http://en.wikipedia.org/wiki/Special:Search?search=' . urlencode(substr($link->URL, 5));
  } elseif (substr($link->URL, 0, 4) === 'url:') {
    $link->URL = substr($link->URL, 4);
  }
  return $invocation->proceed();
}

$GLOBALS['wiki_format'] = function(&$content) {
  $cache = $GLOBALS['dir']['cache'].md5($content).$GLOBALS['cache_ext'];
  if($GLOBALS['enable_cache'] && file_exists($cache)) {
    $content = file_get_contents($cache).'<!-- THIS FILE WAS CACHED IN '.$cache.' -->';
  } else {
    if(!require_once($GLOBALS['texy_path'])) {
			wiki_perror('Cannot load: '.$GLOBALS['texy_path']);
			return false;
		}
    if(!isset($GLOBALS['texy'])) {
      $GLOBALS['texy'] = new Texy();
			if($GLOBALS['paranoia']) TexyConfigurator::safeMode($GLOBALS['texy']);
      $GLOBALS['texy']->encoding = $GLOBALS['charset_encoding'];
      $GLOBALS['texy']->htmlOutputModule->baseIndent  = 4;
      //$GLOBALS['texy']->linkModule->root = '?page=';
      $GLOBALS['texy']->addHandler('phrase', 'wiki_texy_phraseHandler');
      $GLOBALS['texy']->registerLinePattern(
        'wiki_texy_InlineHandler',  // callback function or method
        '#(?<!\[\[)\[\[(?!\ |\[\[)(.+)'.TEXY_MODIFIER.'?(?<!\ |\]\])\]\](?!\]\])()#U', // regular expression
        'wikilink' // any syntax name
      );
      /*$GLOBALS['texy']->registerLinePattern(
        'wiki_texy_InlineHandler',  // callback function or method
        '#(?<!\[)\[(?!\ |\[)(.+)'.TEXY_MODIFIER.'?(?<!\ |\])\](?!\])()#U', // regular expression
        'wikilink' // any syntax name
      );*/
    }
    $content = $GLOBALS['texy']->process($content);
    file_put_contents($cache, $content);
  }
};

