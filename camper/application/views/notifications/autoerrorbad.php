<!DOCTYPE html>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
	body { margin: 0 !important; padding: 0 !important; width: 100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; font-family: Arial !important; color: #6e6473 !important; }
    #backgroundTable { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #d8d7d5 !important; }
    #templateContainer { width: 100%; max-width: 600px; }
    #outlook a {padding:0;}
    .ReadMsgBody { width: 100% !important; }
    .ExternalClass { width: 100% !important; }
    img { border: 0 !important; height: auto !important; line-height: 100% !important; outline: none !important; text-decoration: none !important; }
    body { font: 15px/24px 'Helvetica Neue', Helvetica, Arial, sans-serif !important; }
    body { background-color: #d8d7d5 !important; }
    .headerContent a:visited { color: #2284CC !important; font-weight: normal !important; text-decoration: underline !important; }
    .bodyContent a { color: #2284CC; text-decoration: none; } div a:visited { color: #2284CC !important; font-weight: normal !important; text-decoration: underline !important; }
    .templateButton a:visited { color: #fff !important; font-size: 15px !important; font-weight: bold !important; letter-spacing: -.5px !important; line-height: 100% !important; text-align: center !important; text-decoration: none !important; }
    .footerContent a { color: #dc5408; text-decoration: none; } a:visited { color: #dc5408 !important; font-weight: normal !important; text-decoration: underline !important; }
    .footerContent p { font-size: 12px !important; line-height: 16px !important; }
    hr { height: 0px; border: 0; border-top: 1px solid #ebf0f7; }
    pre { font-size: 9px; font-family: Monaco, monospace; max-width: 580px !important; }
    /*@media only screen and (min-device-width: 601px) {
    table[id=templateContainer] { width: 600px !important; }
    }*/
    </style>
</head>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width: 100% !important; -webkit-text-size-adjust: none; color: #5b5b5b; margin-top: 0; margin-right: 0; margin-bottom: 0; margin-left: 0; padding-top: 0; padding-right: 0; padding-bottom: 0; padding-left: 0; font-style: normal; font-variant: normal; font-weight: normal; font-size: 15px; line-height: 24px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #d8d7d5;" bgcolor="#D8D7D5">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="height: 100% !important; width: 100% !important; margin-top: 0; margin-right: 0; margin-bottom: 0; margin-left: 0; padding-top: 0; padding-right: 0; padding-bottom: 0; padding-left: 0; background-color: #d8d7d5;" bgcolor="#D8D7D5">
        <tr>
            <td align="center" valign="top" style="border-collapse: collapse; padding: 20px 0; border-collapse: collapse;">
                <!--[if (gte mso 9)]>
				<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
				<tr>
				<td>
				<![endif]-->

                <table id="templateContainer" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" bgcolor="#EFEBD2">
                    <tr>
                        <td class="bodyHeader" valign="top" style="border-collapse: collapse; padding: 25px 30px 15px 23px; background-color: #D0C593;"><img style="width: 170px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;" src="http://dev.camperapp.org/email.lpc.small.png" alt="<?php echo $this->config->item('camper_council'); ?>"></td>
                    </tr>

                    <tr>
                        <td class="bodyContent" valign="top" style="border-collapse: collapse; background-color: #EFEBD2; padding: 15px 60px; color: #30261B;">
                            <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <h2 style="color: #30261B;margin-bottom:0px"><b>Error on Camper!</b></h2>
                                        <p>&nbsp;</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
							            <p><?php if (!$this->ion_auth->logged_in()) { ?>Someone<?php } else { $singleuser = $this->ion_auth->user()->row(); echo '<a href="mailto:'.$singleuser->email.'">'.$singleuser->first_name.' '.$singleuser->last_name.'</a>'; } ?> encountered the error, <strong>"<?php echo $message; ?>"</strong>, from the <strong></strong> function while browsing <strong><a href="<?php echo $this->config->item('camper_protocol').$this->config->item('camper_domain').$this->config->item('camper_path').$uri; ?>"><?php echo $uri; ?></a></strong>.</p>
							            <p>We have attached some details below, including a debug array, to help you get to the bottom of this. </p>
							            <p>Yours in Scouting,<br /><?php echo $this->config->item('camper_council'); ?></p>
                                    </td>
                                </tr>

                                <tr>
							        <td class="templateButton" style="text-align: center; padding: 30px 0;">
							            <a style="font-size: 16px; color: #ffffff; background-color: #2284CC; padding: 15px 30px; border-radius: 5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-bottom: 0px solid #cc6b5e;" target="_blank" href="<?php echo $this->config->item('camper_protocol').$this->config->item('camper_domain').$this->config->item('camper_path'); ?>">Sign in &rarr;</a>
							        </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="bodyFooter" valigh="top" style="border-collapse: collapse;">
                            <!--[if (gte mso 9)]>
							<table width="600" align="left" cellpadding="0" cellspacing="0" border="0">
							<tr>
							<td>
							<![endif]-->

                            <table class="footerContent" width="100%" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 600px;" bgcolor="#D8D7D5">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="font-size: 12px; line-height: 16px;"><?php echo $this->config->item('camper_council'); ?>, BSA<br /><?php echo $this->config->item('camper_mailaddress'); ?></p>
                                        <p style="font-size: 12px; line-height: 16px;">
                                        We sent you this message because you are an administrator in Camper, <?php echo $this->config->item('camper_council'); ?>'s registration system. Need additional help? Visit our <a style="color: #6e6473 !important; text-decoration: underline;" href="<?php echo $this->config->item('camper_protocol').$this->config->item('camper_domain'); ?>help">Help Center</a> or contact us at
                                        <a style="color: #6e6473 !important; text-decoration: underline;" href="mailto:<?php echo $this->config->item('camper_supportemail'); ?>"><?php echo $this->config->item('camper_supportemail'); ?></a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 15px;">
                                        <p><strong>Debug Array</strong></p>
										<pre><?php print_r($errorarray); ?></pre>
                                        <p><strong>Request Data</strong></p>
										<pre><?php print_r($_REQUEST)); ?></pre>
                                        <p><strong>Server Data</strong></p>
										<pre><?php print_r($_SERVER)); ?></pre>
                                    </td>
                                </tr>
                            </table>
                            <!--[if (gte mso 9)]>
							</td>
							</tr>
							</table>
							<![endif]-->
                        </td>
                    </tr>
                </table><!--[if (gte mso 9)]>
			</td>
			</tr>
			</table>
			<![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
