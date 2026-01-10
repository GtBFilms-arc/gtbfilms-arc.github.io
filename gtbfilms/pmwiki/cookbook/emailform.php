<?php if (!defined('PmWiki')) exit();

/*
  PmWiki module to generate a that allows you to send email to
  a person specified in the Wiki.
  Copyright (C) 2004  Nils Knappmeier <nk@knappi.org>
  Copyright (c) 2005 by Jeffrey W Barke <jbarke@milwaukeedept.org>
  Copyright (c) 2006 by Anno <anno@shroomery.org>
  Copyright (c) 2006 by Sandy Schoen
  Copyright (c) 2007 Martin Kerz <konsum@kerz.org>
  
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  Modifications to work with PmWiki 2.0, 
  Copyright (c) 2005 by Patrick R. Michaud <pmichaud@pobox.com>

  Revision 1.5 20070803	Martin Kerz
 	* Modifications to create xhtml compliant forms
 	* Included modifications to send the email in utf-8 by Anno <anno@shroomery.org>
 	* Included confirmation code of emailform-s.php by Sandy Schoen
 Revision 1.5.1 20070804	Martin Kerz
  	* Renamed flags to start with Enable
  Revision 1.5.2 20070804	Martin Kerz
  	* Added standard css-file to distribution.
 Revision 1.5.3 20071110	Martin Kerz
 	* Corrected a mistake in the help file
*/

# Version date
SDV($RecipeInfo['EMailForm']['Version'], '2007-11-10');

SDV($EMailFormUrl, 
  (substr(__FILE__, 0, strlen($FarmD)) == $FarmD) 
  ?  '$FarmPubDirUrl/emailform' : '$PubDirUrl/emailform');

##  Add in CSS styles.  
SDV($HTMLHeaderFmt['emailform'], "
  <link rel='stylesheet' href='$EMailFormUrl/emailform.css' 
    type='text/css' />
");

# Some messages
XLSDV('en', array(
  'MF' => '',
  'MFsuccess' => 'Message has been sent successfully.',
  'MFfailure' => 'Message could not be sent.',
  'MFerror' => 'An error has occurred.',
  'MFwrongcode' => 'Wrong confirmation code. Are you sure you are human?'));

#Defining variables
$r = @$_REQUEST['emailform'];
$EMailFormResult = FmtPageName("$[MF$r]", $pagename);
$ACodeCalc = rand(100,999);

# This is the default form.  The '$1' is replaced with
# the word that comes after the mailform: markup -- the rest are
# straightforward $...Fmt substitutions.
if ($EnableEMailFormSecurity==true) 
SDV($EMailFormFmt,"<form class='emailform' action='\$PageUrl' method='post'>
  <fieldset>
  <div class='emailformresult'>\$EMailFormResult</div>
  <input type='hidden' name='pagename' value='\$FullName' />
  <input type='hidden' name='address' value='\$1' />
  <input type='hidden' name='action' value='emailform' />
  <input type='hidden' name='ACodeReturn' value='\$ACodeCalc' />
  <label>$[Your Address:]</label><input type='text' size='20' name='sender' value='' /><br />
  <label>$[Subject:]</label><input type='text' size='20' value='' name='subject' /><br />
  <label>$[Message:]</label><textarea name='text' cols='41' rows='10'></textarea><br />
  <label>$[Repeat Security Code:] <span class='emailform''>$ACodeCalc</span></label><input type='text' size='4' maxlength='3' name='ACodeEntered' value='' /><br />
  <input type='submit' name='send' value='$[Send]' /><br />
  </fieldset>
  </form>
  ");
 else
 SDV($EMailFormFmt,"<form class='emailform' action='\$PageUrl' method='post'>
  <fieldset>
  <div class='emailformresult'>\$EMailFormResult</div>
  <input type='hidden' name='pagename' value='\$FullName' />
  <input type='hidden' name='address' value='\$1' />
  <input type='hidden' name='action' value='emailform' />
  <input type='hidden' name='ACodeReturn' value='\$ACodeCalc' />
  <label>$[Your Address:]</label><input type='text' size='20' name='sender' value='' /><br />
  <label>$[Subject:]</label><input type='text' size='20' value='' name='subject' /><br />
  <label>$[Message:]</label><textarea name='text' cols='41' rows='10'></textarea><br />
  <input type='submit' name='send' value='$[Send]' /><br />
  </fieldset>
  </form>
  ");
# This defines the mailform: markup -- it's just a straight text
# substitution.  
Markup('emailform', '>links', 
  '/\\bemailform:(\\w+)/',
  FmtPageName($EMailFormFmt, $pagename));

# These define what happens after someone has submitted a message.
# The variables are the header and footer for the email message,
# while HandleEMailForm sends the message according to the
# value of 'address' in the request.
SDV($EMailFormHeader,"");
SDV($EMailFormFooter,
   "\n-------------------------------------------\n"
  ."This message was sent by the PmWiki EMailForm at $ScriptUrl\n");
SDV($EMailFormDefaultSender,"");

$HandleActions['emailform'] = 'HandleEMailForm';

function HandleEMailForm($pagename) {
  global $EMailFormAddresses, $EMailFormHeader, $EMailFormFooter,
    $EMailFormDefaultSender, $EnableEMailFormUTF8, $EnableEMailFormSecurity;

  $to = $EMailFormAddresses[$_REQUEST['address']];
  $from = $_REQUEST['sender'];
  $subject = $_REQUEST['subject'];
  $text = $EMailFormHeader.stripmagic($_REQUEST['text']).$EMailFormFooter;
  $text  = nl2br($text);
  $headers  = "MIME-Version: 1.0\r\n"; 
  if ($EnableEMailFormUTF8==true) $headers .= "Content-type: text/html;charset=utf-8\r\n";
  else $headers .= "Content-type: text/html;charset=iso-8859-2\r\n";
  $headers .= "From: $from\r\n";
  if (!$from) $from=$EMailFormDefaultSender;
  if (!$to
      || !$_REQUEST['text']) $msg = 'error';
  else if ($EnableEMailFormSecurity==true && $_REQUEST['ACodeReturn'] != $_REQUEST['ACodeEntered']) $msg = 'wrongcode';
  else if (mail($to, $subject, $text,  $headers)) $msg = 'success';
  else $msg = 'failure';
  header("Location: $ScriptUrl?pagename=$pagename&emailform=$msg");
}

?>