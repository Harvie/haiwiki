<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=<?php wiki('charset_encoding') ?>" />
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php wiki('page'); ?> - <?php wiki('wiki_title'); ?></title>
	<meta http-equiv='Content-Style-Type' content='text/css' />
	<link rel='stylesheet' href='./monobook.css' type='text/css'/>
	<link rel='stylesheet' href='<?php echo $dir['templates']; ?>monobook.tpl/monobook.css' type='text/css'/>
	<!-- monobook/monobook.html - See COPYING for info and license -->
</head>
<body><a name='monobook_topofpage'></a>
  <div id='globalwrapper'>

  	<div id='pageleft'>
		<div id='pageleftcontent'>

		        <div class='pageleftbody' id='sidebar'>
				<ul>
					<h1><?php wiki('wiki_title'); ?></h1><br />
					<?php wiki_page_content('menu'); ?>
				</ul>
							<?php wiki_page_content('widget/search'); ?>
        			<!-- {SEARCH_FORM}<br />{SEARCH_INPUT}<div style="height:3px;"></div>{SEARCH_SUBMIT}{/SEARCH_FORM} -->
		        </div>
	
		        <!-- div class='pageleftbody' id='sidesearch'>
	        	</div -->

		</div>
      
		<div id='pagelogo'>
			<!--a id="site-logo" style="background-image: url('http://upload.wikimedia.org/wikipedia/en/b/bc/Wiki.png');"  -->
			<a id="site-logo" href='./' title="<?php wiki('wiki_title'); ?>"><!-- h2 style="color:red">Change logo here!</h2 --></a>
		</div>
	</div>

	<a name='topcontent'></a>
	<div id='content'>
		<!-- div id='header'>{<span style="float: right;"> COOKIE </span>}</div -->
		<div id='tabs'>
			<ul>
				<li><a href="#">editovat</a></li>
				<li><a href="#">editovat</a></li>
			</ul>
		</div>

		<div id='tabpage'>
			<div id='contentbody'>
				<h1 class='titlepage'><?php wiki('page_title', '/'); ?></h1>
<!-- CONTENT BEGIN -->
<?php wiki_page_content(); ?>
<!-- CONTENT END -->
				<div class="error"><?php wiki('wiki_errors'); ?></div>

<!-- EDIT BEGIN -->
<div class="edit">
          <?php wiki_page_editor(); ?><a name="___edit___" />
          <script type="text/javascript">
            showhide('edit');
            if(/[\?\&]edit\=true($|\&|\?)/ .exec(location.search)){ showhide('edit'); }
          </script>
</div>
<!-- EDIT END -->

			<span style='clear:both;'></span>
		</div>
	</div>
	
	<div id='footer' style='clear:both;'>
		<?php echo($wiki_info); ?>
	</div>

</div>
</div>
<div id='stopwatch'></div>
</body>
</html>
