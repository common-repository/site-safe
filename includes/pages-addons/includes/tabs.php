	<nav class="nav-tab-wrapper">
      <a href="edit.php?post_type=wpss&amp;page=wpss" class="nav-tab <?php if(empty($tab) || $tab=='index'):?>nav-tab-active<?php endif; ?>">All Add-Ons</a>
      <a href="edit.php?post_type=wpss&amp;page=wpss&amp;plugin-page=settings-sync" class="nav-tab <?php if($tab==='settings-sync'):?>nav-tab-active<?php endif; ?>">Payment Gateways</a>
      <a href="edit.php?post_type=wpss&amp;page=wpss&amp;plugin-page=settings-sync" class="nav-tab <?php if($tab==='settings-sync'):?>nav-tab-active<?php endif; ?>">Cloud Storage</a>
    </nav>
	
	