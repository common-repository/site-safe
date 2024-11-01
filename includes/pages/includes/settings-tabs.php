	<nav class="nav-tab-wrapper">
      <a href="edit.php?post_type=wpss&amp;page=wpss" class="nav-tab <?php if(empty($tab) || $tab=='index'):?>nav-tab-active<?php endif; ?>">General</a>
      <a href="edit.php?post_type=wpss&amp;page=wpss&amp;plugin-page=settings-sync" class="nav-tab <?php if($tab==='settings-sync'):?>nav-tab-active<?php endif; ?>">Sync</a>
    </nav>
	
	