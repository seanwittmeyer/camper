<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Options View
 *
 * This is the ...
 *
 * File: /application/views/admin/event/options.php
 * Copyright (c) 2013 Zilifone
 * Version 1.0 (2013 10 08 2233)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

?>
	<div class="subnav">
		<div class="container">
			<h2>Events</h2>
			<nav class="campersubnav">
				<li class="" data-toggle="tooltip" title="New Activity"><?php echo anchor("event/activities/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/activities", 'Activity Library');?></li>
				<li class="" data-toggle="tooltip" title="New Event"><?php echo anchor("event/new", '<i class="icon-plus"></i>');?></li>
				<li class=""><?php echo anchor("event/past", 'Past Events');?></li>
				<li class="active"><?php echo anchor("event", 'Upcoming Events');?></li>
			</nav>
		</div>
	</div>
	<article class="textsection">
   	    <h2 class="">Upcoming Events / <?php echo $event['title']; ?> </h2>
   	    <p>This is where you can modify upcoming event details, view registrations, and manage event activities. You can view a list of all upcoming events and change events by clicking on the event title above.</p>
		<div class="clear"></div>
   		<ul id="detailstabs" class="teal">
   			<li class=""><?php echo anchor("event/".$event['id']."/registrations", 'Registrations');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/details", 'Details &amp; Dates');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/sessions", 'Sessions');?></li>
   			<li class="active"><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
   		</ul>
	</article>
	<article class="textsection">
	<?php echo form_open(uri_string());?>
	<input type="hidden" name="id" value="<?php echo $event['id']; ?>" /> 
   		<div class="container">
       		<div class="quarter">
   	    		<h2 class="pull">Starters</h2>
   	    		<p>Starters are common options and payment scheduling rules that you can use for more complex events.</p>
   	    		<p>
   	    			<span class="">1. <a href="#earlyreg">Early Regs &amp; Free Adults</a></span><br />
   	    			<span class="">2. <a href="#paymentschedule">Payment Schedule</a></span><br />
   	    			<span class="">3. <a href="#preorders">Preorders</a></span><br />
   	    		</p>
   				<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
   	    		<input type="submit" name="submit" value="Save Changes" class="btn teal" data-loading-text="Saving options..." onclick="$(this).button('loading');" />	
   	    		<div class="clear"></div>
       		</div>
       		<div class="threequarter">
   	    		<a id="earlyreg" class="clear"></a><h2>Early Registration and Free Adults</h2>
   	    		<p>The early registration and free adults features are designed to allow you to offer benefits for units that don't necessarily fall under the options or discounts categories. Learn more about how preorders work in our <b>help section</b>.</p>
   				<!-- early registration -->
       			<?php $t= 'enabled'; ?><div class="camperform float cbl" style="width: 180px;" ><input type="checkbox" class="cbl" name="er<?php echo $t; ?>" id="er<?php echo $t; ?>"<?php if($earlyreg[$t] == '1') { ?> checked="checked"<?php } ?> /><label for="er<?php echo $t; ?>" class="cbl">Early Registrations</label></div>
   				<?php $t= 'date'; ?><div class="camperform float " style="width: 200px;"><input type="text" name="er<?php echo $t; ?>" id="er<?php echo $t; ?>" class="datepicker" value="<?php if(!empty($earlyreg[$t]) && $earlyreg[$t] !== 0) { echo date('F d, Y', $earlyreg[$t]); } ?>" placeholder="December 2, 2013" /><label for="er<?php echo $t; ?>">Date</label></div>
   				<?php $t= 'amount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="er<?php echo $t; ?>" id="er<?php echo $t; ?>" value="<?php echo $earlyreg[$t]; ?>" placeholder="$15" /><label for="er<?php echo $t; ?>">Amount</label></div>
		   		<?php $t= 'per'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="er<?php echo $t; ?>" id="er<?php echo $t; ?>"<?php if($earlyreg[$t] == '1') { ?> checked="checked"<?php } ?>  /><label class="cbb" for="er<?php echo $t; ?>">Per Person</label></div>
		   		<?php $t= 'percent'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="er<?php echo $t; ?>" id="er<?php echo $t; ?>"<?php if($earlyreg[$t] == '1') { ?> checked="checked"<?php } ?>  /><label class="cbb" for="er<?php echo $t; ?>">Percent</label></div>
   				<div class="clear hr"></div>
   				<!-- free adults -->
       			<?php $t= 'enabled'; ?><div class="camperform float " style="width: 180px;"><input type="checkbox"  class="cbl" name="fa<?php echo $t; ?>" id="fa<?php echo $t; ?>"<?php if($freeadults[$t] == '1') { ?> checked="checked"<?php } ?>><label for="fa<?php echo $t; ?>" class="cbl">Free Adults</label></div>
   				<?php $t= 'amount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="fa<?php echo $t; ?>" id="fa<?php echo $t; ?>" value="<?php echo $freeadults[$t]; ?>" placeholder="15" /><label for="fa<?php echo $t; ?>">Amount</label></div>
   				<?php $t= 'threshold'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="fa<?php echo $t; ?>" id="fa<?php echo $t; ?>" value="<?php echo $freeadults[$t]; ?>" placeholder="15" /><label for="fa<?php echo $t; ?>">Threshold</label></div>
		   		<?php $t= 'dollar'; ?><div class="camperform float cbtemp" style="width: 120px"><input type="checkbox" class="cbb" name="fa<?php echo $t; ?>" id="fa<?php echo $t; ?>"<?php if($freeadults[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="fa<?php echo $t; ?>">Dollar Amount?</label></div>
   				<?php $t= 'description'; ?><div class="camperform float last" style="width: 96%"><input type="text" name="fa<?php echo $t; ?>" id="fa<?php echo $t; ?>" value="<?php echo $freeadults[$t]; ?>" placeholder="Register 10+ youth and get the first 2 adults free!" /><label for="fa<?php echo $t; ?>">Free Adults Description</label></div>
   				<div class="clear hr"></div>
   	    		<a id="paymentschedule" class="clear"></a><h2>Payment Schedule</h2>
   	    		<p>Some events cost more than others and require a more structured payment schedule. Camper has been designed to let you control when payment is due, from a reservation fee / down payment to scheduled payments and late fees, the site will calculate and manage payments and prevent activity registration and other benefits unless the account is up to date. Learn more about how the payment schedule works in the <b>help section</b>.</p>
   				<!-- reservation fee -->
       			<?php $t= 'r'; ?><div class="camperform float " style="width: 180px;"><input type="checkbox" class="cbl"  name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?>><label for="<?php echo $t; ?>" class="cbl">Reservation Fee</label></div>
   				<?php $t= 'ramount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" value="<?php echo $paymenttiers[$t]; ?>" placeholder="$15" /><label for="<?php echo $t; ?>">Amount</label></div>
		   		<?php $t= 'rper'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Per Person</label></div>
		   		<?php $t= 'rrefund'; ?><div class="camperform float cbtemp" style="width: 100px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Refundable</label></div>
   				<div class="clear"></div>
   				<!-- first payment -->
       			<?php $t= 'f'; ?><div class="camperform float " style="width: 180px;"><input type="checkbox"  class="cbl" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?>><label for="<?php echo $t; ?>"  class="cbl">First Payment</label></div>
   				<?php $t= 'fdate'; ?><div class="camperform float " style="width: 200px;"><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" class="datepicker" value="<?php if(!empty($paymenttiers[$t]) && $paymenttiers[$t] !== 0) { echo date('F d, Y', $paymenttiers[$t]); } ?>" placeholder="December 2, 2013" /><label for="<?php echo $t; ?>">Date</label></div>
   				<?php $t= 'famount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" value="<?php echo $paymenttiers[$t]; ?>" placeholder="$15" /><label for="<?php echo $t; ?>">Amount</label></div>
		   		<?php $t= 'fpercent'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Percent</label></div>
		   		<?php $t= 'fper'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Per Person</label></div>
   				<div class="clear"></div>
   				<!-- second payment -->
       			<?php $t= 's'; ?><div class="camperform float " style="width: 180px;"><input type="checkbox"  class="cbl" class="cbl" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?>><label for="<?php echo $t; ?>"  class="cbl">Second Payment</label></div>
   				<?php $t= 'sdate'; ?><div class="camperform float " style="width: 200px;"><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" class="datepicker" value="<?php if(!empty($paymenttiers[$t]) && $paymenttiers[$t] !== 0) { echo date('F d, Y', $paymenttiers[$t]); } ?>" placeholder="December 2, 2013" /><label for="<?php echo $t; ?>">Date</label></div>
   				<?php $t= 'samount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" value="<?php echo $paymenttiers[$t]; ?>" placeholder="$15" /><label for="<?php echo $t; ?>">Amount</label></div>
		   		<?php $t= 'spercent'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Percent</label></div>
		   		<?php $t= 'sper'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Per Person</label></div>
   				<div class="clear"></div>
   				<!-- final payment -->
       			<?php $t= 'n'; ?><div class="camperform float " style="width: 180px;"><input type="checkbox"  class="cbl" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?>><label for="<?php echo $t; ?>"  class="cbl">Final Payment</label></div>
   				<?php $t= 'ndate'; ?><div class="camperform float " style="width: 200px;"><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" class="datepicker" value="<?php if(!empty($paymenttiers[$t]) && $paymenttiers[$t] !== 0) { echo date('F d, Y', $paymenttiers[$t]); } ?>" placeholder="December 2, 2013" /><label for="<?php echo $t; ?>">Date</label></div>
   				<?php $t= 'namount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" value="<?php echo $paymenttiers[$t]; ?>" placeholder="$15" /><label for="<?php echo $t; ?>">Amount</label></div>
		   		<?php $t= 'npercent'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Percent</label></div>
		   		<?php $t= 'nper'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Per Person</label></div>
   				<div class="clear"></div>
   				<!-- late fee -->
       			<?php $t= 'l'; ?><div class="camperform float " style="width: 180px;"><input type="checkbox"  class="cbl" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?>><label for="<?php echo $t; ?>"  class="cbl">Late Fee</label></div>
   				<?php $t= 'ldate'; ?><div class="camperform float " style="width: 200px;"><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" class="datepicker" value="<?php if(!empty($paymenttiers[$t]) && $paymenttiers[$t] !== 0) { echo date('F d, Y', $paymenttiers[$t]); } ?>" placeholder="December 2, 2013" /><label for="<?php echo $t; ?>">Start Date</label></div>
   				<?php $t= 'lamount'; ?><div class="camperform float " style="width: 70px; "><input type="text" name="<?php echo $t; ?>" id="<?php echo $t; ?>" value="<?php echo $paymenttiers[$t]; ?>" placeholder="$15" /><label for="<?php echo $t; ?>">Amount</label></div>
		   		<?php $t= 'lpercent'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Percent</label></div>
		   		<?php $t= 'lper'; ?><div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" class="cbb" name="<?php echo $t; ?>" id="<?php echo $t; ?>"<?php if($paymenttiers[$t] == '1') { ?> checked="checked"<?php } ?> /><label class="cbb" for="<?php echo $t; ?>">Per Person</label></div>
   				<div class="clear hr"></div>
   	    		<a id="preorders" class="clear"></a><h2>Preorders</h2>
   	    		<p>You can enable preorders for your event's participants. This feature takes the cost of any preorder items from event activities and will calculate each unit and participant's preorder costs allowing them to choose to pay ahead of time. Units can exclude preorders on an individual basis. Learn more about how preorders work in our <b>help section</b>.</p>
   				<!-- allow preorders -->
       			<?php $t= 'activitypreorders'; ?><div class="camperform float cbl" style="width: 300px;"><input type="checkbox" class="cbl" <?php if($event[$t] == '1') { ?> checked="checked"<?php } ?> name="<?php echo $t; ?>" id="<?php echo $t; ?>" /><label for="<?php echo $t; ?>" class="cbl" >Allow Activity Preorders</label></div>
   				<div class="clear"></div>
   			</div>
   		</div>
   		<div class="clear"></div>
   	<?php echo form_close();?> 
	</article>
