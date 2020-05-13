<?php 

/* 
 * Camper Footer
 *
 * This is the global footer that displays on all pages that call the
 * footer template. This page includes some javascript needed for the
 * page.
 *
 * File: /application/views/templates/footer.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */
 
/* 
 * Function & Resource Grab
 */	

?>	
	<div class="clear tall"></div>
	<div class="clear hr"></div>
	<footer>
		<ul>
			<li><?php echo anchor('signout','Sign Out'); ?></li>
			<li><?php echo anchor('help','Help'); ?></li>
			<?php if ($this->ion_auth->in_group('members')) { ?><li><?php echo anchor('registrations','Leader View'); ?></li><?php } ?>
			<?php if ($this->ion_auth->is_admin()) { ?><li><?php echo anchor('dashboard','Admin View'); ?></li><?php } ?>
		</ul>
		<div class="clear"></div>
		<p>
			<br /><a href="http://longspeakbsa.org/" target="_blank">Longs Peak Council</a><br />
			<a href="http://scouting.org/" target="_blank">Boy Scouts of America</a><br />
			<a href="http://camps.longspeakbsa.org/contact/">2215 23rd Avenue<br />
				Greeley, CO 80632</a><br />
			<br />
			This registration system is powered by <a href="<?php echo $this->config->item('camper_path'); ?>new">Zilifone Camper <?php echo $this->config->item('camper_version'); ?></a><br />
			<strong>{elapsed_time} seconds</strong> / <strong>{memory_usage}</strong> / <?php echo ($this->config->item('camper_debug')) ? 'dev': 'live'; ?>
		</p>
	</footer>
	</div>
</body>
</html>