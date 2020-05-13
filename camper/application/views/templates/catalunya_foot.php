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
	<article class="feedbackfooter">
	<script>
		// Feedback Functions
		function send_feedback() {
			$.ajax({
	    		url: "<?php echo $this->config->item('camper_path'); ?>api/v1/feedback",
	    		type: 'POST',
	    		data: {doing: $('#feedbackdoing').val(), wrong: $('#feedbackwrong').val(), uri: $('#feedbackuri').val()},
	    		statusCode: {
	    			200: function() {
	    				//alert( "success" );
	    				$('#feedback').button('complete');
	    			},
	    			304: function() {
	    				//alert( "nothing to mark as read" );
	    				$('#feedback').button('failed');
	    			}
	    		}
			});
		}
	
	</script>
		<p><a href="#" id="footerfeedbacklink" onclick="$('#footerfeedback').toggle('slow'); $('#feedbackdoing').focus(); return false;" >Is there anything wrong with this page?</a></p>
		<div class="clear"></div>
		<div id="footerfeedback" class="half" style="display:none;">
			<h3>Help us improve Camper</h3>
			<p>Camper was built from the ground up on leader feedback, we would love to hear your thoughts. Thank you for your help!</p>
			<!--<?php echo form_open("feedback");?>-->
			<!-- Feedback form is now handled via ajax so it doesn't reset a user's progress -->
			<input type="hidden" id="feedbackuri" name="uri" value="<?php echo uri_string(); ?>" />
			<p>
				<label for="feedbackdoing">What you were doing</label>
				<input type="text" id="feedbackdoing" name="doing" />
				<label for="feedbackwrong">What went wrong</label>
				<input type="text" id="feedbackwrong" name="wrong" />
			</p>
			<p><button class="btn teal" id="feedback" data-loading-text="Sending feedback..." data-failed-text="Feedback was not sent. Retry?" data-complete-text="Thanks for the feedback!" onclick="send_feedback(); $(this).button('loading');">Send</button>
			<!--<?php echo form_close();?>-->
		</div>
		<div class="clear"></div>
	</article>
	<footer class="catalunya">
		<div class="container">
			<div class="left first">
				<img src="<?php echo $this->config->item('camper_path'); ?>includes/img/logo.footer.png" alt="Longs Peak Council" width="35" height="31" />
				<a href="http://longspeakbsa.org/" target="_blank">Longs Peak Council</a><br />
				<a href="http://scouting.org/" target="_blank">Boy Scouts of America
				</a><br /><br />
				<a href="http://camps.longspeakbsa.org/contact/">2215 23rd Avenue<br />
				Greeley, CO 80632</a><br /><br />
				
				800-800-4052<br /> 970-330-6305 (local)<br /><br />
				<a href="http://camps.longspeakbsa.org/contact/">campregistration@longspeakbsa.org</a>
			</div>
			<div class="left">
				<b>Quick Links</b><br /><br />
				<a href="http://camps.longspeakbsa.org/refund-policy/">Refund Policy</a><br />
				<a href="http://camps.longspeakbsa.org/faq/">Camps FAQ</a><br />
				<a href="http://camps.longspeakbsa.org/forms/">Forms</a><br />
				<a href="http://camps.longspeakbsa.org/reservations/">Off Season Reservations</a><br />
				<a href="http://camps.longspeakbsa.org/work-volunteer/">Staff &amp; Volunteer</a><br />
				<a href="http://camps.longspeakbsa.org/">Longs Peak Council Camps</a><br />
			</div>
			<div class="left">
				<b>Help</b><br /><br />
				We tried to make this system as easy <br />to use as possible but sometimes a <br />little extra help is needed. <br /><br />
				<a href="<?php echo $this->config->item('camper_path'); ?>help">Help Documentation</a><br />
				<a href="<?php echo $this->config->item('camper_path'); ?>help">FAQ</a><br />
				<a href="<?php echo $this->config->item('camper_path'); ?>help">Getting Started</a><br />
			</div>
			<div class="left last">
				<b>Colophon</b><br /><br />
				This registration system is powered by <a href="<?php echo $this->config->item('camper_path'); ?>new">Zilifone Camper <?php echo $this->config->item('camper_version'); ?></a><br /><br />
				This system was built from the ground up based on leader feedback, we would love to hear how we can improve!<br /><br />
				<strong>{elapsed_time} seconds</strong> / <strong>{memory_usage}</strong> / <?php echo ($this->config->item('camper_debug')) ? 'dev': 'live'; ?>
			</div>
			<div class="clear"></div>
		</div>
	</footer>
</body>
</html>