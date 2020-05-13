<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* 
 * Camper Admin Event / Custom Options and Discounts View
 *
 * This is the ...
 *
 * File: /application/views/admin/event/custom.php
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
   			<li class=""><?php echo anchor("event/".$event['id']."/options", 'Starters');?></li>
   			<li class="active"><?php echo anchor("event/".$event['id']."/custom", 'Options &amp; Discounts');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/classes", 'Classes');?></li>
   			<li class=""><?php echo anchor("event/".$event['id']."/message", 'Message');?></li>
		</ul>
	</article>
	<article class="textsection">
		<?php echo form_open(uri_string());?>
		<input type="hidden" name="id" value="<?php echo $event['id']; ?>" /> 
		<input type="hidden" name="s" value="1" /> 
	   	<h2>Options &amp; Discounts</h2>
		<div class="right"> <a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to make changes to the options and discounts for this event. <br /><br />All leaders and units registered for this event will be notified about the changes.<br /><br /><input type='submit' name='submit' value='Save Changes' class='btn teal' />" class="btn teal camperpopover">Save Changes &rarr; </a> <input type="reset" name="reset" value="Reset" class="btn tan"  /> </div>
		<p>Every event will have it's unique options and discounts and Camper gives you the power to create the options and discounts you need.</p>
		<?php if (!$message == '') { ?><div class="alert"><button type="button" class="close" data-dismiss="alert">&times;</button><?php echo $message; ?></div><?php } ?> 
		<div class="clear hr"></div>
		
		<!-- OPTIONS -->
		<h2 class="">Options</h2>
		<p>Options are extra details that are offered to units that register for an event. Options are either free or will add to the cost of an event for participants. If you wish to add an option that gives a credit or discount, you can do that in the discounts section below. You can create a variety of options from requests requiring input to preorders that utilize existing data from a unit like t-shirt orders. See the help section with examples on how to create a variety of option types.</p>
		<div class="clear hr"></div>
		
		<?php $i=1; foreach ($options as $oneoption): ?>
			<div id="option<?php echo $i; ?>">
				<div class="deletedcontent"><i class="icon-ok"></i> <?php echo $oneoption['title']; ?> has been marked for deletion. Click 'Save Options' to confirm. (<a href="#" onclick="undeleteoption(<?php echo $i; ?>,'option'); return false;">Undo</a>)</div>
				<div class="optionform">
					<!-- HIDDEN	-->
					<input type="hidden" id="" name="options[<?php echo $i; ?>][id]" value="<?php echo $oneoption['id']; ?>" />
					<input type="hidden" id="" name="options[<?php echo $i; ?>][new]" value="0" />
					<!-- FIRST LINE -->
					<div class="camperform float" style="width: 60px" data-toggle="tooltip" id="fooption<?php echo $i; ?>" title="tooltip"><input type="text" value="<?php echo $i; ?>" name="options[<?php echo $i; ?>][num]" disabled="disabled" /><label for="fooption<?php echo $i; ?>" >Option</label></div>
					<div class="camperform float" style="width: 300px"><input type="text" id="fotitle<?php echo $i; ?>" value="<?php echo $oneoption['title']; ?>" name="options[<?php echo $i; ?>][title]" placeholder="Specialty Week" data-toggle="tooltip" title="tooltip" /><label for="fotitle<?php echo $i; ?>" >Title (required)</label></div>
					<div class="camperform float cbtemp" style="width: 30px"><input type="checkbox" id="foverify<?php echo $i; ?>"<?php if($oneoption['verify'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][verify]" data-toggle="tooltip" title="tooltip" /><label for="foverify<?php echo $i; ?>"<?php if($oneoption['verify'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][verify]" >Verify</label></div>
					<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox" id="focheckbox<?php echo $i; ?>"<?php if($oneoption['checkbox'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][checkbox]" data-toggle="tooltip" title="tooltip" /><label for="focheckbox<?php echo $i; ?>"<?php if($oneoption['checkbox'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][checkbox]" >Checkbox</label></div>
					<div class="camperform float cbtemp" style="width: 70px"><input type="checkbox" id="foperperson<?php echo $i; ?>"<?php if($oneoption['perperson'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][perperson]" data-toggle="tooltip" title="tooltip" /><label for="foperperson<?php echo $i; ?>"<?php if($oneoption['perperson'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][perperson]" >Per Person</label></div>
					<div class="camperform float" style="width: 65px"><input type="text" id="foamount<?php echo $i; ?>" value="<?php echo $oneoption['amount']; ?>" name="options[<?php echo $i; ?>][amount]" placeholder="none" data-toggle="tooltip" title="tooltip" /><label for="foamount<?php echo $i; ?>" >Amount</label></div>
					<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox" id="fopercent<?php echo $i; ?>"<?php if($oneoption['percent'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][percent]" data-toggle="tooltip" title="tooltip" /><label for="fopercent<?php echo $i; ?>"<?php if($oneoption['percent'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][percent]" >Percent</label></div>
					<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox" id="fovalue<?php echo $i; ?>"<?php if($oneoption['value'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][value]" data-toggle="tooltip" title="tooltip" /><label for="fovalue<?php echo $i; ?>"<?php if($oneoption['value'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][value]" >Value</label></div>
					<div class="camperform float cbtemp" style="width: 60px"><input type="checkbox" id="foinput<?php echo $i; ?>"<?php if($oneoption['input'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][input]" data-toggle="tooltip" title="tooltip" /><label for="foinput<?php echo $i; ?>"<?php if($oneoption['input'] == '1') { ?> checked="checked"<?php } ?> name="options[<?php echo $i; ?>][input]" >Input</label></div>
					<div class="clear"></div>
					<!-- TOOLS -->
					<div class="camperform float" style="width: 60px"><button class="btn btn-mini red" data-toggle="tooltip" title="Delete this option" onclick="deleteoption(<?php echo $i; ?>,'option'); return false;"><i class="icon-remove-sign"></i></button> <button class="btn btn-mini tan" data-toggle="tooltip" title="Add a new option" onclick="createoption(); return false;"><i class="icon-plus-sign"></i></button></div>
					<!-- SECOND LINE -->
					<div class="camperform float" style="width: 600px"><input type="text" id="fodescription<?php echo $i; ?>" value="<?php echo $oneoption['description']; ?>" name="options[<?php echo $i; ?>][description]" placeholder="none" data-toggle="tooltip" title="tooltip" /><label for="fodescription<?php echo $i; ?>" >Description</label></div>
					<div class="camperform float last" style="width: 205px"><input type="text" id="fodate<?php echo $i; ?>" class="datepicker" value="<?php if($oneoption['date']) { echo date('F d, Y', $oneoption['date']); } ?>" name="options[<?php echo $i; ?>][date]" placeholder="June 15, 2013" data-toggle="tooltip" title="tooltip" /><label for="fodate<?php echo $i; ?>" >Date</label></div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear hr"></div> 
		<?php $i++; endforeach;?>
		<div id="optionsend" class="clear"></div>
		<button class="btn blue" onclick="createoption(); return false;"><i class="icon-plus"></i> Add an option</button>  <a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to make changes to the options and discounts for this event. <br /><br />All leaders and units registered for this event will be notified about the changes.<br /><br /><input type='submit' name='submit' value='Save Changes' class='btn teal' />" class="btn teal camperpopover">Save Changes &rarr; </a>
		<script>
			optionscount = <?php echo $i; ?>;
		</script>
		<div class="clear hr"></div> 

		<!-- DISCOUNTS -->
		<h2 class="">Discounts</h2>
		<p>Options are extra details that are offered to units that register for an event. Options are either free or will add to the cost of an event for participants. If you wish to add an option that gives a credit or discount, you can do that in the discounts section below. You can create a variety of options from requests requiring input to preorders that utilize existing data from a unit like t-shirt orders. See the help section with examples on how to create a variety of option types.</p>
		<div class="clear hr"></div>
		
		<?php $i=1; foreach ($discounts as $onediscount): ?>
			<div id="discount<?php echo $i; ?>">
				<div class="deletedcontent"><i class="icon-ok"></i> <?php echo $onediscount['title']; ?> has been marked for deletion. Click 'Save Discounts' to confirm. (<a href="#" onclick="undeleteoption(<?php echo $i; ?>,'discount'); return false;">Undo</a>)</div>
				<div class="optionform">
					<!-- HIDDEN	-->
					<input type="hidden" id="" name="discounts[<?php echo $i; ?>][id]" value="<?php echo $onediscount['id']; ?>" />
					<input type="hidden" id="" name="discounts[<?php echo $i; ?>][new]" value="0" />
					<!-- FIRST LINE -->
					<div class="camperform float" style="width: 60px" data-toggle="tooltip" id="fddiscount<?php echo $i; ?>" title="tooltip"><input type="text" value="<?php echo $i; ?>" name="discounts[<?php echo $i; ?>][num]" disabled="disabled" /><label for="fddiscount<?php echo $i; ?>" >Discount</label></div>
					<div class="camperform float" style="width: 300px"><input type="text" id="fdtitle<?php echo $i; ?>" value="<?php echo $onediscount['title']; ?>" name="discounts[<?php echo $i; ?>][title]" placeholder="Specialty Week" data-toggle="tooltip" title="Enter a label for the discount, this will show on the options pages and in the event invoices" /><label for="fdtitle<?php echo $i; ?>" >Title (required)</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdverify<?php echo $i; ?>"<?php if($onediscount['verify'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][verify]" data-toggle="tooltip" title="Check to require admin verification" /><label for="fdverify<?php echo $i; ?>"<?php if($onediscount['verify'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][verify]" >Verify</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdcheckbox<?php echo $i; ?>"<?php if($onediscount['checkbox'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][checkbox]" data-toggle="tooltip" title="Check if you want this discount to have a checkbox" /><label for="fdcheckbox<?php echo $i; ?>"<?php if($onediscount['checkbox'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][checkbox]" >Checkbox</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdperperson<?php echo $i; ?>"<?php if($onediscount['perperson'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][perperson]" data-toggle="tooltip" title="Check to apply the cost per person instead of per unit/registration" /><label for="fdperperson<?php echo $i; ?>"<?php if($onediscount['perperson'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][perperson]" >Per Person</label></div>
					<div class="camperform float" style="width: 65px"><input type="text" id="fdamount<?php echo $i; ?>" value="<?php echo $onediscount['amount']; ?>" name="discounts[<?php echo $i; ?>][amount]" placeholder="none" data-toggle="tooltip" title="Enter an amount (cost or percent)" /><label for="fdamount<?php echo $i; ?>" >Amount</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdpercent<?php echo $i; ?>"<?php if($onediscount['percent'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][percent]" data-toggle="tooltip" title="Check to apply the amount as a percent" /><label for="fdpercent<?php echo $i; ?>"<?php if($onediscount['percent'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][percent]" >Percent</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdvalue<?php echo $i; ?>"<?php if($onediscount['value'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][value]" data-toggle="tooltip" title="Check to ask for a number/amount" /><label for="fdvalue<?php echo $i; ?>"<?php if($onediscount['value'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][value]" >Value</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdinput<?php echo $i; ?>"<?php if($onediscount['input'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][input]" data-toggle="tooltip" title="Check to ask for a non-number input, like text" /><label for="fdinput<?php echo $i; ?>"<?php if($onediscount['input'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][input]" >Input</label></div>
					<div class="camperform float cbtemp" style=""><input type="checkbox" id="fdindividual<?php echo $i; ?>"<?php if($onediscount['individual'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][individual]" data-toggle="tooltip" title="Check to customize this discount for individual youth/adults instead of for full units/registrations. This option will show up in the roster, per member instead of on the extras/options page for the registrations." /><label for="fdindividual<?php echo $i; ?>"<?php if($onediscount['individual'] == '1') { ?> checked="checked"<?php } ?> name="discounts[<?php echo $i; ?>][individual]" >Individual</label></div>
					<div class="clear"></div>
					<!-- TOOLS -->
					<div class="camperform float" style="width: 60px"><button class="btn btn-mini red" data-toggle="tooltip" title="Delete this discount" onclick="deleteoption(<?php echo $i; ?>,'discount'); return false;"><i class="icon-remove-sign"></i></button> <button class="btn btn-mini tan" data-toggle="tooltip" title="Add a new discount" onclick="creatediscount(); return false;"><i class="icon-plus-sign"></i></button></div>
					<!-- SECOND LINE -->
					<div class="camperform float" style="width: 500px"><input type="text" id="fddescription<?php echo $i; ?>" value="<?php echo $onediscount['description']; ?>" name="discounts[<?php echo $i; ?>][description]" placeholder="none" data-toggle="tooltip" title="tooltip" /><label for="fddescription<?php echo $i; ?>" >Description</label></div>
					<div class="camperform float" style="width: 80px"><input type="text" id="fdcode<?php echo $i; ?>" value="<?php echo $onediscount['code']; ?>" name="discounts[<?php echo $i; ?>][code]" placeholder="none" data-toggle="tooltip" title="tooltip" /><label for="fdcode<?php echo $i; ?>" >Code</label></div>
					<div class="camperform float last" style="width: 205px"><input type="text" id="fddate<?php echo $i; ?>" class="datepicker" value="<?php if($onediscount['date']) { echo date('F d, Y', $onediscount['date']); } ?>" name="discounts[<?php echo $i; ?>][date]" placeholder="June 15, 2013" data-toggle="tooltip" title="tooltip" /><label for="fddate<?php echo $i; ?>" >Date</label></div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear hr"></div> 
		<?php $i++; endforeach;?>
		<div id="discountsend" class="clear"></div>
		<button class="btn blue" onclick="creatediscount(); return false;"><i class="icon-plus"></i> Add a discount</button> <a data-toggle="popover" title="Are you sure?" data-placement="top" data-content="You are about to make changes to the options and discounts for this event. <br /><br />All leaders and units registered for this event will be notified about the changes.<br /><br /><input type='submit' name='submit' value='Save Changes' class='btn teal' />  " class="btn teal camperpopover">Save Changes &rarr; </a>
		<script>
			discountscount = <?php echo $i; ?>;
			function deleteoption(num,type)
			{
				var divid = "#"+type+num;
				if (type == "discount") { var t="d" }
				if (type == "option") { var t="o" }
				$(divid).append("<input type='hidden' id='f"+t+"delete"+num+"' name='"+type+"s["+num+"][delete]' value='1' />");
				$(divid).addClass('deletedoption');
				return false;
			}
			function undeleteoption(num,type)
			{
				var divid = "#"+type+num;
				if (type == "discount") { var t="d" }
				if (type == "option") { var t="o" }
				var deleteinputid = "#f"+t+"delete"+num;
				$(deleteinputid).remove();
				$(divid).removeClass('deletedoption');
				return false;
			}
			function createoption()
			{
				var divid = "#option"+optionscount;
				var newtitleid = "#fotitle"+optionscount;
				var newoption = "<div id='option"+optionscount+"'>"
					+"<div class='deletedcontent'><i class='icon-ok'></i> This option has been marked for deletion. Click 'Save Options' to confirm. (<a href='#' onclick=\"undeleteoption("+optionscount+",'option'); return false;\">Undo</a>)</div>"
					+"<div class='optionform'>"
					+"<input type='hidden' id='' name='options["+optionscount+"][id]' value='0' />"
					+"<input type='hidden' id='' name='options["+optionscount+"][new]' value='1' />"
					+"<input type='hidden' id='' name='options["+optionscount+"][individual]' value='0' />"
					+"<div class='camperform float' style='width: 60px' data-toggle='tooltip' id='fooption"+optionscount+"' title='tooltip'><input type='text' value='"+optionscount+"' name='options["+optionscount+"][num]' disabled='disabled' /><label for='fooption"+optionscount+"' >Option</label></div>"
					+"<div class='camperform float' style='width: 300px'><input type='text' id='fotitle"+optionscount+"' value='' name='options["+optionscount+"][title]' placeholder='Specialty Week' data-toggle='tooltip' title='tooltip' /><label for='fotitle"+optionscount+"' >Title (required)</label></div>"
					+"<div class='camperform float cbtemp' style='width: 30px'><input type='checkbox' id='foverify"+optionscount+"' name='options["+optionscount+"][verify]' data-toggle='tooltip' title='tooltip' /><label for='foverify"+optionscount+"' name='options["+optionscount+"][verify]' >Verify</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='focheckbox"+optionscount+"' name='options["+optionscount+"][checkbox]' data-toggle='tooltip' title='tooltip' /><label for='focheckbox"+optionscount+"' name='options["+optionscount+"][checkbox]' >Checkbox</label></div>"
					+"<div class='camperform float cbtemp' style='width: 70px'><input type='checkbox' id='foperperson"+optionscount+"' name='options["+optionscount+"][perperson]' data-toggle='tooltip' title='tooltip' /><label for='foperperson"+optionscount+"' name='options["+optionscount+"][perperson]' >Per Person</label></div>"
					+"<div class='camperform float' style='width: 65px'><input type='text' id='foamount"+optionscount+"' value='' name='options["+optionscount+"][amount]' placeholder='none' data-toggle='tooltip' title='tooltip' /><label for='foamount"+optionscount+"' >Amount</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='fopercent"+optionscount+"' name='options["+optionscount+"][percent]' data-toggle='tooltip' title='tooltip' /><label for='fopercent"+optionscount+"' name='options["+optionscount+"][percent]' >Percent</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='fovalue"+optionscount+"' name='options["+optionscount+"][value]' data-toggle='tooltip' title='tooltip' /><label for='fovalue"+optionscount+"' name='options["+optionscount+"][value]' >Value</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='foinput"+optionscount+"' name='options["+optionscount+"][input]' data-toggle='tooltip' title='tooltip' /><label for='foinput"+optionscount+"' name='options["+optionscount+"][input]' >Input</label></div>"
					+"<div class='clear'></div>"
					+"<div class='camperform float' style='width: 60px'><button class='btn btn-mini red' data-toggle='tooltip' title='Delete this option' onclick=\"deleteoption("+optionscount+",'option'); return false;\"><i class='icon-remove-sign'></i></button> <button class='btn btn-mini tan' data-toggle='tooltip' title='Add a new option' onclick='createoption(); return false;'><i class='icon-plus-sign'></i></button></div>"
					+"<div class='camperform float' style='width: 600px'><input type='text' id='fodescription"+optionscount+"' value='' name='options["+optionscount+"][description]' placeholder='none' data-toggle='tooltip' title='tooltip' /><label for='fodescription"+optionscount+"' >Description</label></div>"
					+"<div class='camperform float last' style='width: 205px'><input type='text' id='fodate"+optionscount+"' class='datepicker' value='' name='options["+optionscount+"][date]' placeholder='June 15, 2013' data-toggle='tooltip' title='tooltip' /><label for='fodate"+optionscount+"' >Date</label></div>"
					+"<div class='clear'></div></div></div><div class='clear hr'></div>";
				$("#optionsend").before(newoption);
				$(newtitleid).focus();
				optionscount++;
				$('.datepicker').pickadate({
					format: 'mmmm dd, yyyy',
					formatSubmit: 'mmmm dd yyyy',
					hiddenPrefix: 'prefix__',
					hiddenSuffix: '__suffix',
					selectYears: 4,
					selectMonths: true
				});
				return false;
			}
			function creatediscount()
			{
				var divid = "#option"+discountscount;
				var newtitleid = "#fdtitle"+discountscount;
				var newdiscount = "<div id='discount"+discountscount+"'>"
					+"<div class='deletedcontent'><i class='icon-ok'></i> This discount has been marked for deletion. Click 'Save Discounts' to confirm. (<a href='#' onclick=\"undeleteoption("+discountscount+",'discount'); return false;\">Undo</a>)</div>"
					+"<div class='optionform'>"
					+"<input type='hidden' id='' name='discounts["+discountscount+"][id]' value='0' />"
					+"<input type='hidden' id='' name='discounts["+discountscount+"][new]' value='1' />"
					+"<input type='hidden' id='' name='discounts["+discountscount+"][individual]' value='0' />"
					+"<div class='camperform float' style='width: 60px' data-toggle='tooltip' id='fddiscount"+discountscount+"' title='tooltip'><input type='text' value='"+discountscount+"' name='discounts["+discountscount+"][num]' disabled='disabled' /><label for='fddiscount"+discountscount+"' >Discount</label></div>"
					+"<div class='camperform float' style='width: 300px'><input type='text' id='fdtitle"+discountscount+"' value='' name='discounts["+discountscount+"][title]' placeholder='Specialty Week' data-toggle='tooltip' title='tooltip' /><label for='fdtitle"+discountscount+"' >Title (required)</label></div>"
					+"<div class='camperform float cbtemp' style='width: 30px'><input type='checkbox' id='fdverify"+discountscount+"' name='discounts["+discountscount+"][verify]' data-toggle='tooltip' title='tooltip' /><label for='fdverify"+discountscount+"' name='discounts["+discountscount+"][verify]' >Verify</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='fdcheckbox"+discountscount+"' name='discounts["+discountscount+"][checkbox]' data-toggle='tooltip' title='tooltip' /><label for='fdcheckbox"+discountscount+"' name='discounts["+discountscount+"][checkbox]' >Checkbox</label></div>"
					+"<div class='camperform float cbtemp' style='width: 70px'><input type='checkbox' id='fdperperson"+discountscount+"' name='discounts["+discountscount+"][perperson]' data-toggle='tooltip' title='tooltip' /><label for='fdperperson"+discountscount+"' name='discounts["+discountscount+"][perperson]' >Per Person</label></div>"
					+"<div class='camperform float' style='width: 65px'><input type='text' id='fdamount"+discountscount+"' value='' name='discounts["+discountscount+"][amount]' placeholder='none' data-toggle='tooltip' title='tooltip' /><label for='fdamount"+discountscount+"' >Amount</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='fdpercent"+discountscount+"' name='discounts["+discountscount+"][percent]' data-toggle='tooltip' title='tooltip' /><label for='fdpercent"+discountscount+"' name='discounts["+discountscount+"][percent]' >Percent</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='fdvalue"+discountscount+"' name='discounts["+discountscount+"][value]' data-toggle='tooltip' title='tooltip' /><label for='fdvalue"+discountscount+"' name='discounts["+discountscount+"][value]' >Value</label></div>"
					+"<div class='camperform float cbtemp' style='width: 60px'><input type='checkbox' id='fdinput"+discountscount+"' name='discounts["+discountscount+"][input]' data-toggle='tooltip' title='tooltip' /><label for='fdinput"+discountscount+"' name='discounts["+discountscount+"][input]' >Input</label></div>"
					+"<div class='clear'></div>"
					+"<div class='camperform float' style='width: 60px'><button class='btn btn-mini red' data-toggle='tooltip' title='Delete this discount' onclick=\"deleteoption("+discountscount+",'discount'); return false;\"><i class='icon-remove-sign'></i></button> <button class='btn btn-mini tan' data-toggle='tooltip' title='Add a new discount' onclick='creatediscount(); return false;'><i class='icon-plus-sign'></i></button></div>"
					+"<div class='camperform float' style='width: 500px'><input type='text' id='fddescription"+discountscount+"' value='' name='discounts["+discountscount+"][description]' placeholder='none' data-toggle='tooltip' title='tooltip' /><label for='fddescription"+discountscount+"' >Description</label></div>"
					+"<div class='camperform float' style='width: 80px'><input type='text' id='fdcode"+discountscount+"' value='' name='discounts["+discountscount+"][code]' placeholder='none' data-toggle='tooltip' title='tooltip' /><label for='fdcode"+discountscount+"' >Code</label></div>"
					+"<div class='camperform float last' style='width: 205px'><input type='text' id='fddate"+discountscount+"' class='datepicker' value='' name='discounts["+discountscount+"][date]' placeholder='June 15, 2013' data-toggle='tooltip' title='tooltip' /><label for='fddate"+discountscount+"' >Date</label></div>"
					+"<div class='clear'></div></div></div><div class='clear hr'></div>";
				$("#discountsend").before(newdiscount);
				$(newtitleid).focus();
				discountscount++;
				$('.datepicker').pickadate({
					format: 'mmmm dd, yyyy',
					formatSubmit: 'mmmm dd yyyy',
					hiddenPrefix: 'prefix__',
					hiddenSuffix: '__suffix',
					selectYears: 4,
					selectMonths: true
				});
				return false;
			}
		</script>
		<div class="clear"></div>
		<!-- END OPTIONS -->
   	<?php echo form_close();?>
	</article>


<?php // FORM VARS
/*
$option['num']['id']
$option['num']['eventid']
$option['num']['verify']
$option['num']['title']
$option['num']['description']
$option['num']['amount']
$option['num']['perperson']
$option['num']['checkbox']
$option['num']['date']
$option['num']['value']
$option['num']['input']
$option['num']['optionnum']

$discount['num']['id']
$discount['num']['eventid']
$discount['num']['verify']
$discount['num']['title']
$discount['num']['description']
$discount['num']['amount']
$discount['num']['perperson']
$discount['num']['checkbox']
$discount['num']['date']
$discount['num']['code']
$discount['num']['precent']
$discount['num']['optionnum']

$event['id']
$event['title']
$event['eventtype']
$event['location']
$event['description']
$event['activityregs']
$event['regcount']
$event['datestart']
$event['dateend']
$event['open']
$event['sessiontitle']
$event['freeadults']
$event['earlyreg']
$event['paymenttiers']
*/

?>	
