<?php

$wiki_pages[$p_widget.'/search'] = array('code' => function() {
  ?><form action="?" method="GET" class="search">
    <input type="text" name="search" title="keywords" />
    <input type="submit" value="search" />
  </form><?php
});

$wiki_pages[$p_widget.'/goto'] = array('code' => function() {
  ?><form action="?" method="GET" class="goto">
    <input type="text" name="page" title="page" />
    <input type="submit" value="goto" />
  </form><?php
});

