<?php
/**
* @version   $Id: mod_swm.php 2011-02-18 10:30:00Z mirko boeer $
* @package  SuperWebMailer Newsletteranmeldung
* @copyright    Copyright (C) 2001-2011 Mirko Boeer, Leipzig. All rights reserved.
* @license   GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

  /** ensure this file is being included by a parent file */
  defined('_JEXEC') or die('Direct Access to this location is not allowed.');

  $moduleclass_sfx      = ($params->get( 'moduleclass_sfx', 'moduletable' ));
  $module_description = ($params->get( 'module_description', 'Newsletteranmeldung' ));

  $swm_url_to_nl_php = ($params->get( 'swm_url_to_nl_php', 'http://.../swm/nl.php' ));
  $swm_MailinglistId = ($params->get( 'swm_MailinglistId', '1' ));
  $swm_FormId = ($params->get( 'swm_FormId', '1' ));
  $swm_FormEncoding = ($params->get( 'swm_FormEncoding', 'utf-8' ));

  $swm_salutation_visible = ($params->get( 'swm_salutation_visible', 0 ));
  $swm_name_visible = ($params->get( 'swm_name_visible', 0 ));
  $swm_firstname_visible = ($params->get( 'swm_firstname_visible', 0 ));
  $swm_poweredby_visible = ($params->get( 'swm_poweredby_visible', 1 ));

  $swm_salutation_desc = ($params->get( 'swm_salutation_desc', 'Anrede:' ));
  $swm_name_desc = ($params->get( 'swm_name_desc', 'Name:' ));
  $swm_firstname_desc = ($params->get( 'swm_firstname_desc', 'Vorname:' ));

  $swm_email_desc = ($params->get( 'swm_email_desc', 'Ihre E-Mail-Adresse:' ));

  $swm_newsletter_action_desc = ($params->get( 'swm_newsletter_action_desc', 'Sie m&ouml;chten sich f&uuml;r unseren Newsletter' ));
  $swm_sub_rb_desc = ($params->get( 'swm_sub_rb_desc', 'anmelden' ));
  $swm_unsub_rb_desc = ($params->get( 'swm_unsub_rb_desc', 'abmelden' ));

  $swm_submit_btn_desc = ($params->get( 'swm_submit_btn_desc', 'Absenden' ));

  $swm_PrivacyPolicyURL = ($params->get( 'swm_PrivacyPolicyURL', '' ));
?>


<!--Newsletteranmeldung beginnt hier-->
<form method="POST" action="<?php echo $swm_url_to_nl_php ?>">

<input type="hidden" name="MailingListId" value="<?php echo $swm_MailinglistId ?>" />
<input type="hidden" name="FormId" value="<?php echo $swm_FormId ?>" />
<input type="hidden" name="FormEncoding" value="<?php echo $swm_FormEncoding ?>" />

<div class="<?php echo $moduleclass_sfx; ?>">
<table>
  <tr>
   <td>
       <p>
       <b><?php echo $module_description; ?></b></p>
       <p><?php echo $swm_email_desc; ?><br>
       <input type="text" name="u_EMail" size="" />
       </p>

       <?php
         if($swm_salutation_visible)
           print '<p>'.$swm_salutation_desc.'<br /><select size="1" name="u_Salutation"><option>Herr</option><option>Frau</option></select></p>';
         if($swm_name_visible)
           print '<p>'.$swm_name_desc.'<br /><input type="text" name="u_LastName" size="" /></p>';
         if($swm_firstname_visible)
           print '<p>'.$swm_firstname_desc.'<br /><input type="text" name="u_FirstName" size="" /></p>';
       ?>

       <p>
       <?php echo $swm_newsletter_action_desc ?><br />
        <input type="radio" value="subscribe" checked="checked" name="Action" /><?php echo $swm_sub_rb_desc; ?><br />
        <input type="radio" value="unsubscribe" name="Action" /><?php echo $swm_unsub_rb_desc; ?>
       </p>
       <p>&nbsp;</p>
       <?php
         if(!empty($swm_PrivacyPolicyURL)){
           print "<p>&nbsp;</p>";
           print '<p>Mit Angabe meiner Daten und Absenden der Anmeldung erkl&auml;re ich mich einverstanden, den hier bestellten Newsletter ';
           print 'per E-Mail zu erhalten. Meine Daten werden nicht an Dritte weitergegeben. Dieses Einverst&auml;ndnis kann ich jederzeit widerrufen. ';
           print 'Weitere ausf&uuml;hrliche Informationen in der <a href="' . $swm_PrivacyPolicyURL . '">Datenschutzerkl&auml;rung</a>.</p>';
           print "<p>&nbsp;</p>";
         }
       ?>
       <p>
       <input type="submit" value="<?php echo $swm_submit_btn_desc; ?>" name="SubmitBtn">
       <br><br>
       <?php
       if($swm_poweredby_visible) {
         print '
         <span style="font-size:7pt">Powered&nbsp;by&nbsp;
         <a href="http://www.superwebmailer.de" target="_blank">SuperWebMailer</a></span>
         ';
       }
       ?>
       </p>
   </td>
  </tr>
</table>
</div>
</form>
<!--Newsletteranmeldung endet hier-->

