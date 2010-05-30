<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>

	<head>
		<title><?php wiki('page'); ?> - <?php wiki('wiki_title'); ?></title>
		<link rel="alternate" type="application/x-wiki" title="Edit this page" href="javascript:showhide('edit');/*window.location.hash='___EDIT___'*/" /><!-- UEB: http://universaleditbutton.org/ -->
		<?php wiki('headers'); ?>

		<!-- CASCADING STYLE SHEETS ------------------------------------------------------------------- -->
		<style>
			/* @variables {
  			bg: black;
  			fg: green;
				block-bg: lime;
				block-fg: black;
				a: grey;
				a-hover: white;
			} */

			* { background-color: white; color: black; font-family: monospace; background-color:var(bg); color:var(fg); }
			h1,h2,h3,h3,h5,h6 { margin: 2px; }
			a { color: blue; color:var(a); }
			a:visited { color: blue; color:var(a); }
			a:hover { color: lightblue; color:var(a-hover); }
			div { margin: 5px; padding: 5px; border: 3px solid white; border-color:var(fg);}
			textarea { width: 100%; height: 200px; }
			table { background-color: black; padding: 1px; background-color:var(fg);}
			td, th { padding: 5px; }
			code { white-space: pre-wrap; margin: 5px; padding: 5px; background-color: skyblue; display: block; font-family: monospace; background-color:var(block-bg);}
			q, blockquote, blockquote * { margin: 5px; padding: 5px; background-color: lightblue; background-color:var(block-bg);}
				blockquote blockquote { border: 3px solid skyblue; padding: 5px; background-color:var(block-fg);}

			.container { margin: 0; padding: 0; border: none; }
			.header { margin: 0; padding: 0; }
			.content { border-color: black; }
			.menu, .menu * { background-color: white; margin: 0; padding: 0;}
				.menu table { width: 100%; }
			.edit { margin: 0; padding: 0; border: none; text-align: right; }
			.errors { border-color: red; color: red; background-color: lightyellow; }
				.errors { position: absolute; top: 0; right: 8; width: 60%; }
			.info { border-color: grey; color: grey; text-align: center; }
			.right { text-align: right; }
		</style>

		<!-- JAVASCRIPT FUNCTIONS ------------------------------------------------------------------- -->
		<script type="text/javascript" language="javascript">
			function showhide(eid) {
				obj = document.getElementById(eid);
				obj.style.display = (obj.style.display == 'none' ? '' : 'none');
			}
/*
			var xmlHttp = false;
			function getEditor() {

				xmlHttp = false;
				if (window.XMLHttpRequest) { // Mozilla, Safari,...
					xmlHttp = new XMLHttpRequest();
				} else if (window.ActiveXObject) { // IE
					try {
						xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
      		} catch (e) {
						try {
							xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
						} catch (e) {}
					}
				}
    
				if (!xmlHttp) { return false; }

				xmlHttp.onreadystatechange = function() {
					if(xmlHttp.readyState==4) {
						if (xmlHttp.status == 200) {
							//var body = document.getElementsByTagName('body')[0];
 							//body.innerHTML = xmlHttp.responseText;
							document.getElementById('edit').innerHTML = xmlHttp.responseText;
							//alert(xmlHttp.responseText);
 						}
 					}
				};
				xmlHttp.open('GET', '?template=html&page=widget/editor', true);
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlHttp.send(null);
			}
*/
		</script>
	</head>

	<body>
		<div class="container">
			<!-- h1 class="header">[<a href="?"><?php wiki('wiki_title'); ?></a>][<?php wiki('page'); ?>]</h1 -->
			<h1 class="header"><?php wiki('wiki_title', '[<a href="?">', '</a>]'); ?><?php wiki('page_title', '[', ']'); ?></h1>
			<div class="content">

				<!-- MENU ---------------------------------------------------------------------------- -->
				<div class="menu">
					<table class="menu">
						<tr>
							<td class="left"><?php wiki_page_content('menu'); ?></td>
							<td class="right"><?php wiki('goto'); ?></td>
							<td class="right"><a href="#___edit___" onclick="showhide('edit')">code/edit</a></td-->
							<!--td class="right"><a href="#___edit___" onclick="getEditor()">code/edit</a></td-->
						</tr>
					</table>
				</div>

				<!-- PAGE CONTENT ITSELF ------------------------------------------------------------- -->
				<?php wiki_page_content(); ?>
				<?php
					$hide_errors = '<a onclick="showhide(\'errors\');" href="#" style="float:right;color:red">[X]</a>';
					wiki('wiki_errors', '<div class="errors" id="errors">'.$hide_errors, '</div>');
				?>

				<!-- EDITOR -------------------------------------------------------------------------- -->
				<div class="edit" id="edit">
					<?php wiki('editor'); ?><a name="___edit___" />
					<script type="text/javascript">
						showhide('edit');
						if(/[\?\&]edit\=true($|\&|\?)/ .exec(location.search)){ showhide('edit');	}
					</script>
				</div>

			</div>
			<div class="info"><small><?php wiki('info'); ?></small></div>
		</div>
	</body>

</html>
