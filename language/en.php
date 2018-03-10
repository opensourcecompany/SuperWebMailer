<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################


   $language_strings_en  =         array(
                                        "000000" => "",
                                        "000001" => "Fehler beim MySQL-Zugriff / Error while connecting to MySQL database",
                                        "000002" => "Fehler beim Verwenden der Datenbank / Error while selecting database",
                                        "000003" => "Sitzung abgelaufen / Session expired",
                                        "000004" => "Login",
                                        "000005" => "Username/Password not specified.",
                                        "000006" => "Username and/or Password incorrect.",
                                        "000007" => "Dashboard",
                                        "000008" => "Send login information",
                                        "000009" => "Logout",
                                        "000010" => "You must specify username <b>or</b> email address.",
                                        "000011" => "There is no user with name '%s'.",
                                        "000012" => "There is no user with email address '%s'.",
                                        "000013" => "Your login information",
                                        "000014" => "Create new recipients list",
                                        "000015" => "You must specify an unique name for your recipients list.",
                                        "000016" => "Browse recipients lists",
                                        "000017" => "Error while executing a SQL query",
                                        "000018" => "Recipients list was created now you can set other options.",
                                        "000019" => "Edit recipients list",
                                        "000020" => "There are errors please check the values in red highlighted fields.",
                                        "000021" => "Settings are saved.",
                                        "000022" => "Recipients list(s) was removed.",
                                        "000023" => "There are errors while removing:<br />",
                                        "000024" => "Report of Sub-/Unsubscriptions",
                                        "000025" => "Subscriptions",
                                        "000026" => "Unsubscriptions",
                                        "000027" => "Member activities",
                                        "000028" => "HTML page/redirection was removed.",
                                        "000029" => "There are errors while removing:<br />",
                                        "000030" => "HTML pages / Redirections",
                                        "000031" => "Default, not removeable",
                                        "000032" => "Edit HTML page/redirection",
                                        "000033" => "Not removeable, always in use",
                                        "000034" => "Recipients are removed.",
                                        "000035" => "There are errors while removing:<br />",
                                        "000036" => "Browse recipients",
                                        "000037" => "Recipient(s) are moved.",
                                        "000038" => "There are errors while moving::<br />",
                                        "000039" => "Recipients are copied.",
                                        "000040" => "There are errors while copying:<br />",
                                        "000041" => "Recipients are added to block list.",
                                        "000042" => "There are errors while adding recipients to block list:<br />",
                                        "000043" => "Source and destination recipients list should not be the same.",
                                        "000044" => "Edit recipients",
                                        "000045" => "Recipients list not found.",
                                        "000046" => "It always exists a recipient with these email address.",
                                        "000047" => "added manually",

                                        "000048" => "Confirmation link not clicked so far",

                                        "000049" => "Recipient activated",
                                        "000050" => "Confirmation link for unsubscription not clicked so far",
                                        "000051" => "Undeliverable permanently",
                                        "000052" => "Temporarily not deliverable",
                                        "000053" => "Select recipients list",
                                        "000054" => "Import recipiens",
                                        "000055" => "File %s can't be saved to directory %s. Please check permissions on directory.",
                                        "000056" => "Can't open file %s for reading.",
                                        "000057" => "Date format",
                                        "000058" => "You must assign one field.",
                                        "000059" => "Field with email address must have an assignment.",
                                        "000060" => "File %s can't be deleted.",
                                        "000061" => "Message text(s) was removed.",
                                        "000062" => "There are errors while removing:<br />",
                                        "000063" => "Message texts",
                                        "000064" => "Edit message texts",
                                        "000065" => "Form was removed.",
                                        "000066" => "There are errors while removing:<br />",
                                        "000067" => "Subscriptions / Unsubscriptions forms",
                                        "000068" => "Edit Subscriptions / Unsubscriptions form",
                                        "000069" => '<option value="invisible">invisible</option><option value="visible">visible, optional</option>',
                                        "000070" => '<option value="visiblerequired">visible, required field</option>',
                                        "000071" => "Form creation",
                                        "000072" => "Form creation - Integrated PRODUCTAPPNAME form",
                                        "000073" => "Form creation - Form for your own website",

                                        "000075" => "Send variant was removed.",
                                        "000076" => "There are errors while removing:<br />",
                                        "000077" => "Send variants (MTAs)",
                                        "000078" => "Edit send variants",
                                        "000079" => "Test Email",
                                        "000080" => "This is the text of test email.",
                                        "000081" => "Test email was sent successfully.",
                                        "000082" => "Test email was NOT sent successfully:<br />",

                                        "000084" => "Test SMS was sent successfully.",
                                        "000085" => "Test SMS was NOT sent successfully:<br />",

                                        "000090" => "Recipients was assigned to groups.",
                                        "000091" => "Assignment to groups was removed.",
                                        "000092" => "Groups are removed.",
                                        "000093" => "There are errors while removing:<br />",
                                        "000094" => "<b>You must select one file for deleting.</b>",

                                        "000098" => "Settings are duplicated.",
                                        "000099" => "There are errors while duplicatin settings:<br />",

                                        "000100" => "Edit users",
                                        "000101" => "User(s) was removed.",
                                        "000102" => "There are errors while removing:<br />",
                                        "000103" => "There are errors please check the values in red highlighted fields.",
                                        "000104" => "There is always an user with same username please specify an other username.",
                                        "000105" => "Not removeable, because the user owns recipients lists.",
                                        "000106" => "Not removeable, because the user owns other users.",
                                        "000107" => "Edit own account",

                                        "000110" => "Edit functions",
                                        "000111" => "Functions are removed.",
                                        "000112" => "There are errors while removing:<br />",
                                        "000115" => "Edit function %s",
                                        "000116" => "You must specify an unique name for function.",
                                        "000117" => "Function name can't contain characters [ and ].",
                                        "000118" => "Function name should only contain characters A-Z, a-z, 0-9 and _.",
                                        "000119" => "There are no conditions defined.",
                                        "000120" => "Editing condition of function %s.",

                                        "000130" => "Global block list",
                                        "000131" => "Local block list",
                                        "000132" => "Entries are removed.",
                                        "000133" => "There are errors while removing:<br />",
                                        "000134" => "Email address always exists in block list.",
                                        "000135" => "Email address was saved to block list.",
                                        "000136" => "Email address can't be saved because block list always contains this email address.<br />",
                                        "000137" => "New entry in global block list",
                                        "000138" => "New entry in local block list",
                                        "000139" => "Import to global block list",
                                        "000140" => "Import to local block list",

                                        "000141" => "Export global block list",
                                        "000142" => "Export local block list",


                                        "000150" => "Export recipients",
                                        "000151" => "File %s can't be saved to directory %s. Check permissions for this directory.",
                                        "000152" => "Can't open file %s for writing.",
                                        "000153" => "You must select at least one field.",

                                        "000160" => "Inbox server was removed.",
                                        "000161" => "There are errors while removing:<br />",
                                        "000162" => "Inbox servers",
                                        "000163" => "Edit inbox",
                                        "000164" => "Test of inbox server was successfully. There are %d Email(s) in mailbox.",
                                        "000165" => "Test of inbox server was NOT successfully:<br />",
                                        "000166" => "Bounce state was reset.",
                                        "000167" => "Recipient is inactive for email sending",
                                        "000168" => "Recipient(s) are activated.",
                                        "000169" => "Hard bounces / Undeliverable mails",
                                        "000170" => "Subscription state changed.",

                                        "000180" => "Unconfirmed subscribers",  // must be UTF-8
                                        "000181" => "Unconfirmed  unsubscribers",  // must be UTF-8

                                        "000200" => "Serial email preview",
                                        "000201" => "Serial SMS preview",

                                        "000250" => "Remove recipients by block lists",
                                        "000251" => "Remove recipients by local block list",
                                        "000252" => "Remove recipients by global block list",
                                        "000253" => "Remove recipients by ECG list",
                                        "000254" => "Remove recipients by local domain block list",
                                        "000255" => "Remove recipients by global domain block list",

                                        "000260" => "Recipient removed",
                                        "000261" => "Recipient not in ECG list",
                                        "000262" => "Recipient not in domain block list",

                                        "000300" => "Options",
                                        "000320" => "Change firm logo",

                                        "000325" => "Test system",
                                        "000326" => "Email was sent successfully.",
                                        "000327" => "Email was <b>not</b> sent successfully.",

                                        "000329" => "Maintenance database",
                                        "000330" => "Optimization of tables was done.",
                                        "000331" => "Repairing tables was done.",

                                        "000340" => "Event protocol of scheduled tasks",
                                        "000345" => "Scheduled tasks",
                                        "000346" => "Settings in field 'Execution interval' are containing errors, e.g. no values.",
                                        "000347" => "Scheduled tasks was executed if necessary.",

                                        "000360" => "Distribution of email providers",
                                        "000361" => "Reasons for unsubscription for '%s'",
                                        "000370" => "Statistics over all recipients lists",
                                        "000371" => "in last 5 days",

                                        "000390" => "Can't establish database connection to %s.",
                                        "000391" => "SQL query failed: %s",

                                        "000480" => "Select %s",
                                        "000481" => "Please select %s to be shown.",
                                        "000482" => "%s",

                                        "000483" => "Select Emailing",
                                        "000484" => "Please select emailing to be shown.",
                                        "000485" => "Emailing",

                                        "000486" => "Select sent entry for emailing '%s'",
                                        "000487" => "Please select sent entry for emailing '%s'.",
                                        "000488" => "Sent at",

                                        "000489" => "Please select SMS campaign to be shown.",

                                        "000490" => "Select sent entry",
                                        "000491" => "Please select sent entry for '%s'.",
                                        "000492" => "Sent at",

                                        "000500" => "Autoresponder was removed.",
                                        "000501" => "There are errors while removing:<br />",
                                        "000502" => "Autoresponders",
                                        "000503" => "Edit autoresponder",
                                        "000504" => "An autoresponder with name '%s' always exists.",
                                        "000510" => "Sending protocol of autoresponder&nbsp;",
                                        "000511" => "Entries were removed.",
                                        "000512" => "There are errors while removing:<br />",
                                        "000513" => "Entries were resend again or put to outqueue for sending.",
                                        "000514" => "There are errors while resending:<br />",

                                        "000520" => "Follow up responder was removed.",
                                        "000521" => "There are errors while removing:<br />",
                                        "000522" => "Follow up responder",
                                        "000523" => "Edit Follow up responder",
                                        "000524" => "A follow up responder with name '%s' always exists.",
                                        "000528" => "For action based follow up responders you must activate recipients tracking with counting openings and clicks on hyperlinks.",
                                        "000529" => "Not editable, one or more follow up emails always sent",
                                        "000530" => "Sent protocol of follow up responder&nbsp;",

                                        "000540" => "Follow up emails are removed.",
                                        "000541" => "There are errors while removing:<br />",
                                        "000542" => "Edit follow up emails",
                                        "000543" => "after %d %s",
                                        "000544" => "A follow up email with name '%s' always exists.",
                                        "000545" => "Edit follow up emails of follow up responder '%s'",
                                        "000549" => "Check for this action based follow up responder the following email for correct selection of to performing action.",

                                        "000550" => "Show recipients and next follow up email of follow up responder '%s'",

                                        "000560" => "Birthday responder was removed.",
                                        "000561" => "There are errors while removing:<br />",
                                        "000562" => "Birthday responder",
                                        "000563" => "Edit birthday responder",
                                        "000564" => "A birthday responder with name '%s' always exists.",
                                        "000570" => "Sent protocol of birthday responder&nbsp;",
                                        "000571" => "Entries were removed.",
                                        "000572" => "There are errors while removing:<br />",
                                        "000573" => "Entries were resend again or put to outqueue for sending.",
                                        "000574" => "There are errors while resending:<br />",
                                        "000580" => "Check cell phone number '%s', only numbers are allowed.",
                                        "000581" => "Check cell phone number '%s', access code unknown.",
                                        "000582" => "Check cell phone number '%s', it doesn't contain country code.",
                                        "000583" => "Check cell phone number '%s', it is too short.",
                                        "000584" => "Cell phone number shouldn't be empty.",

                                        "000600" => "Create new emailing",
                                        "000601" => "You must specify an unique name for your emailing.",
                                        "000602" => "You must select a recipients list.",
                                        "000603" => "Emailing was created now specify other options.",
                                        "000604" => "Edit emailing '%s'",
                                        "000605" => "Setup for emailing '%s' done.",
                                        "000606" => "This emailing",

                                        "000610" => "Not editable while sending",
                                        "000611" => "Not editable is used by one or more split tests",

                                        "000620" => "Emailings",
                                        "000621" => "Emailings were removed.",
                                        "000622" => "There are errors while removing:<br />",

                                        "000623" => "Sending of emailing was stopped.",
                                        "000624" => "Sending of emailing will be stopped after next cronjob script call.",

                                        "000650" => "Sending results of emailing &quot;%s&quot;",
                                        "000651" => "Sent successfully, time:",
                                        "000652" => "Not sent successfully, error code: %d, error text: %s, time:",
                                        "000653" => "Report of email sending: %s",

                                        "000670" => "Sent protocol of Emailing&nbsp;'%s', sent at %s",
                                        "000671" => "Entries were removed.",
                                        "000672" => "There are errors while removing:<br />",
                                        "000673" => "Entries were resend again or put to outqueue for sending.",
                                        "000674" => "There are errors while resending:<br />",
                                        "000675" => "Sending now",

                                        "000680" => "Tracking statistics of emailing&nbsp;'%s', sent at %s",
                                        "000681" => "Openings", // must be UTF-8
                                        "000682" => "Soft bounces", // must be UTF-8
                                        "000683" => "Hard bounces", // must be UTF-8
                                        "000684" => "after %d-%d hours", // must be UTF-8
                                        "000685" => "Opening at", // must be UTF-8
                                        "000686" => "successfully", // must be UTF-8
                                        "000687" => "failed", // must be UTF-8
                                        "000688" => "Evaluation of last email sending", // must be UTF-8

                                        "000690" => "%RECIPIENTCOUNT% recipients they have opened emailing",
                                        "000691" => "%%RECIPIENTCOUNT%% recipients they have clicked on link '%s'",
                                        "000692" => "Recipients doesn't exists anymore.",

                                        "000693" => "First access",
                                        "000694" => "Last access",
                                        "000695" => "Sum",

                                        "000696" => "First click",
                                        "000697" => "last click",
                                        "000698" => "Sum of clicks",

                                        "000700" => "Statistics of responder '%s'",
                                        "000701" => "Openings %s - %s", // must be UTF-8
                                        "000702" => "%%RECIPIENTCOUNT%% recipients they haved opened email between %s and %s",
                                        "000703" => "%%RECIPIENTCOUNT%% recipients they haved clicked on link '%s' between %s and %s",
                                        "000704" => "Statistics of responder '%s'; follow up email: '%s'",
                                        "000705" => "Statistics of distribution list '%s'; email: '%s'",

                                        "000730" => "Statistics of sent emails",
                                        "000731" => "Sent emails", // must be UTF-8

                                        "000740" => "Post Twitter Tweet",
                                        "000741" => "Post Facebook status message",

                                        "000860" => "RSS2EMail responder was removed.",
                                        "000861" => "There are errors while removing:<br />",
                                        "000862" => "RSS2EMail responder",
                                        "000863" => "Edit RSS2EMail responder",
                                        "000864" => "A RSS2EMail responder with name '%s' always exists.",
                                        "000870" => "Sent protocol of RSS2EMail responder&nbsp;",
                                        "000871" => "Entries were removed.",
                                        "000872" => "There are errors while removing:<br />",
                                        "000873" => "Entries were resend again or put to outqueue for sending.",
                                        "000874" => "There are errors while resending:<br />",

                                        "000800" => "Email template was removed.",
                                        "000801" => "There are errors while removing:<br />",
                                        "000802" => "Email templates",
                                        "000803" => "Default, not removeable",
                                        "000804" => "Edit email template",
                                        "000805" => "An email template with name '%s' always exists.",
                                        "000806" => "Sample email template",
                                        "000807" => "Sample email template to use with wizard",

                                        "000810" => "Insert email template",

                                        "000830" => "Newsletter archives",
                                        "000831" => "Default, not removeable",
                                        "000832" => "Edit Newsletter archive",
                                        "000833" => "Newsletter archive was removed.",
                                        "000834" => "There are errors while removing:<br />",


                                        "000950" => "You have no permissions.",
                                        "000951" => "Viewing outqueue",
                                        "000952" => "Entries were removed.",
                                        "000953" => "There are errors while removing:<br />",

                                        "001110" => "Local message center",
                                        "001111" => "Message(s) are removed.",
                                        "001112" => "There are errors while removing:<br />",
                                        "001115" => "Read message",
                                        "001116" => "New message",
                                        "001117" => "Reply message",
                                        "001118" => "Username doesn't exists.",
                                        "001119" => "Can't send message, error %s.",
                                        "001120" => "Message was sent.",

                                        "001130" => "Global domain block list",
                                        "001131" => "Local domain block list",
                                        "001132" => "Entries are removed.",
                                        "001133" => "There are errors while removing:<br />",
                                        "001134" => "Domain always exists in block list.",
                                        "001135" => "Domain was saved to block list.",
                                        "001136" => "Domain can't be saved because block list always contains this domain.<br />",
                                        "001137" => "New entry in global domain block list",
                                        "001138" => "New entry in local domain block list",
                                        "001139" => "Import to global domain block list",
                                        "001140" => "Import to local domain block list",

                                        "001141" => "Export global domain block list",
                                        "001142" => "Export local domain block list",


                                        "001200" => "Automatic recipients import",
                                        "001201" => "Not removable, import is running",
                                        "001202" => "Import from file",
                                        "001203" => "Import from database",
                                        "001204" => "An entry with name '%s' always exists.",
                                        "001205" => "%s - Edit automatic recipients import",
                                        "001206" => "DEMO - Import variant will be saved as inactive entry.",
                                        "001207" => "Automatic recipients import removed.",

                                        "001300" => "Twitter update not activated.",
                                        "001301" => "Twitter tweet was postet.",
                                        "001302" => "Error while posting twitter tweet.",
                                        "001303" => "Error while connection to short URL service.",

                                        "001310" => "Authorize with Twitter",
                                        "001311" => "Entry was created:",
                                        "001312" => "Creation date:",
                                        "001313" => "ID of entry:",
                                        "001314" => "Text of entry:",
                                        "001315" => "<b>Entry was not created:</b>",
                                        "001316" => "<b>Error:</b>",
                                        "001317" => "None authorized access.",
                                        "001318" => "<b>We caught an unexpected Exception</b>",

                                        "001410" => "Edit text blocks",
                                        "001411" => "Text block(s) are removed.",
                                        "001412" => "There are errors while removing:<br />",
                                        "001415" => "Edit text block %s",
                                        "001416" => "You must provide a unique name for text block.",
                                        "001417" => "Name of text block couldn't contain characters [ and ].",
                                        "001418" => "Name of text block can only contain characters A-Z, a-z, 0-9 and _.",
                                        "001419" => "Please provider a output text.",
                                        "001420" => "Name of text block are always used for an other placeholder.",

                                        "001600" => "New SMS campaign",
                                        "001601" => "You must give your SMS campaign an unique name.",
                                        "001602" => "You must select a mailinglist for campaign.",
                                        "001603" => "SMS campaign was created, you can now make more settings.",
                                        "001604" => "Edit SMS campaign '%s'",
                                        "001605" => "Setup of SMS campaign '%s' was done",

                                        "001610" => "Not editable, sending in progress",

                                        "001620" => "SMS campaigns",
                                        "001621" => "SMS campaign(s) was removed.",
                                        "001622" => "There are errors while removing:<br />",

                                        "001623" => "Sending of SMS campaign was canceled.",
                                        "001624" => "Sending of SMS campaign will be canceled after next cron job script request.",

                                        "001650" => "Sending results of SMS campaign &quot;%s&quot;",
                                        "001651" => "Sent successfully, %s time:",
                                        "001652" => "Not sent successfully, error code: %d, error text: %s, time:",
                                        "001653" => "Report of SMS sending: %s",

                                        "001670" => "Sent protocol of SMS campaign&nbsp;'%s', sent at %s",

                                        "001675" => "Sending now",

                                        "001800" => "New A/B Split Test",
                                        "001801" => "You must give your A/B Split Test an unique name.",
                                        "001802" => "You must select a mailinglist.",
                                        "001803" => "A/B Split Test was created, now you can specify more settings.",
                                        "001804" => "Edit A/B Split Test '%s'",
                                        "001805" => "A/B Split Test '%s' completed",
                                        "001810" => "Not editable, sending in progress",
                                        "001820" => "A/B Split Test",
                                        "001821" => "Split Test(s) are removed.",
                                        "001822" => "There are errors while removing:<br />",

                                        "001830" => "Selected recipient groups and emailing sending rules must be identically.",

                                        "001840" => "Prepare sending",
                                        "001841" => "Wait for sending results",
                                        "001842" => "Preparing sending of &quot;Winner emailing&quot;",
                                        "001843" => "Sending of &quot;Winner emailing&quot; in progress",

                                        "001850" => "Sent protocol of A/B Split Tests&nbsp;'%s', sent at %s",
                                        "001851" => "%s; %d Openings of email",
                                        "001852" => "%s; %d click(s) of hyperlinks in email",

                                        "001860" => "Tracking statistics of A/B Split Test&nbsp;'%s', sent at %s",

                                        "002000" => "Search recipients",
                                        "002001" => "Result of search (<!--FOUNDCOUNT--> Hits)",
                                        "002002" => "Browse found recipients",

                                        "002600" => "Create new distribution list",
                                        "002601" => "You must give your distribution list an unique name.",
                                        "002602" => "You must select a mailing list.",
                                        "002603" => "Distribution list was created now you can specify more parameters.",
                                        "002604" => "Edit distribution list '%s'",
                                        "002605" => "Distribution list '%s' completed",
                                        "002606" => "This distribution list",
                                        "002607" => "You must select an inbox server/email account.",

                                        "002610" => "Not editable, sending in progress",
                                        "002611" => "Not editable, sending in progress",

                                        "002620" => "Distribution lists",
                                        "002621" => "Distribution list(s) were removed.",
                                        "002622" => "There are errors while removing:<br />",

                                        "002623" => "Dispatching emails of distribution list has been canceled.",
                                        "002624" => "Dispatching emails of distribution list were canceled after next cronjob script request.",

                                        "002630" => "Warning! Mailbox is also used for:",

                                        "002650" => "Delivery of entry in distribution list &quot;%s&quot;",
                                        "002651" => "Successfully sent. Time:",
                                        "002652" => "Unsuccessfully, Error code: %d, Error text: %s. Time:",
                                        "002653" => "Report of entry in distribution list: %s",

                                        "002660" => "Select email of distribution list '%s'",
                                        "002661" => "Please select email of distribution list '%s'.",
                                        "002662" => "Subject",
                                        "002663" => "Please select to show %s.",

                                        "002670" => "Delivery protocol of email '%s' in distribution list&nbsp;'%s', sent at %s",
                                        "002671" => "Email(s) were removed.",
                                        "002672" => "There are errors while removing:<br />",
                                        "002673" => "Email(s) were resend again or put to outqueue for sending.",
                                        "002674" => "There are errors while sending:<br />",

                                        "002675" => "Sending in progress",
                                        "002680" => "Confirmation of sent email of distribution list: [DISTRIBLISTNAME]",
                                        "002681" => "Report of sending to distribution list: %s; Subject: %s",

                                        "002690" => "Email(s) were removed.",
                                        "002691" => "There are errors while removing:<br />",
                                        "002692" => "Emails of distribution list",
                                        "002693" => "Email with confirmation link was sent again to '%s'.",
                                        "002694" => "Email with confirmmation couldn't be sent again to '%s'.",

                                        "002710" => "Edit reasons for unsubscripe",
                                        "002711" => "Reasons were removed.",
                                        "002712" => "There are errors while removing:<br />",
                                        "002715" => "Edit reason %s",
                                        "002716" => "Unsubscripe reason must contain a text.",

                                        "090000" => "Create Super Administrator",
                                        "090001" => "Password and Passwort repetition not correct.",
                                        "090002" => "User SuperAdmin was created.",
                                        "090003" => "Username should only contain characters A-Z, numbers 0-9 and underline(_). It must have at least 3 characters.",

                                        "090100" => "Installation PRODUCTAPPNAME - Welcome",
                                        "090101" => "Installation PRODUCTAPPNAME - License data",
                                        "090102" => "Installation PRODUCTAPPNAME - Directories",
                                        "090103" => "Installation PRODUCTAPPNAME - Database data",
                                        "090104" => "Installation PRODUCTAPPNAME - Information about CronJobs/Scheduled tasks",
                                        "090105" => "Installation PRODUCTAPPNAME - Setting permissions to files and directories",
                                        "090107" => "Installation PRODUCTAPPNAME - Creating SuperAdministrator",
                                        "090108" => "Installation PRODUCTAPPNAME - Creating first Administrator",
                                        "090109" => "Installation PRODUCTAPPNAME - Removing script install.php",
                                        "090110" => "Installation PRODUCTAPPNAME - Completing installation",

                                        "090170" => "Upgrade PRODUCTAPPNAME - Welcome",
                                        "090171" => "Upgrade PRODUCTAPPNAME - analyzing and updateing tables",
                                        "090185" => "Upgrade PRODUCTAPPNAME - Removing script upgrade.php and install.php",
                                        "090186" => "Upgrade PRODUCTAPPNAME - Completing upgrade",

                                        "090200" => "Please check correct writing of license name and license number.",
                                        "090201" => "Error while checking license number.<br />Click on &quot;Check license manually&quot; to check the license in your internet browser.<br />",
                                        "090202" => "License data are not correct.",
                                        "090203" => "License was locked e.g. unpayed bills.",
                                        "090204" => "License was used to often. These product should only be installed on one web space or server.",
                                        "090205" => "An unknown error occurs.",
                                        "090206" => "The entered verification code isn't correct.",
                                        "090210" => "Can't save file config_paths.inc.php. Check the value entered in field script server directory and permissions of file config_paths.inc.php (Linux 777, Windows full access).",
                                        "090211" => "Accessing MySQL database with specified data are failed, Error: ",
                                        "090212" => "Can't save file config_db.inc.php. Check the value entered in field script server directory and permissions of file config_db.inc.php (Linux 777, Windows full access).",
                                        "090213" => "Script install.php exists furthermore on your webspace. Please delete the script.",
                                        "090214" => "Script upgrade.php and/or install.php exists furthermore on your webspace. Please delete the script(s).",

                                        "999997" => "<b>Limited public Demo</b>, you can send <b>test emails</b> only sending of mass emails are simulated. Don't upload personal data these can be viewed by other testers.",
                                        "999998" => "<b>Evaluation version</b>, <b>one mailinglist</b> per admin user can be created and sending of <b>test emails</b> are possible.",
                                        "999999" => "Evaluation version, one mailinglist per admin user can be created only.",

                                        "YES"  => "yes",
                                        "NO"  => "no",
                                        "CLOSE"  => "Close",
                                        "CANCEL"  => "Cancel",
                                        "NA"  => "n/a",
                                        "ERROR" => "Error",
                                        "NEW"  => "new",
                                        "NONE" => "none",
                                        "NEVER" => "never",
                                        "INACTIVE" => "disabled",
                                        "ENTRYNOTFOUND" => "Entry not found",
                                        "NO_MAILINGLISTS" => "No mailing list found, first create one mailinglist",
                                        "RecipientsCount" => "Recipients",
                                        "UNKNOWN" => "unknown",
                                        "FAILED" => "failed",
                                        "EXECUTED" => "executed",
                                        "NOT_EXECUTED" => "not executed",
                                        "EXECUTING" => "is executed now",
                                        "TRUE" => "true",
                                        "FALSE" => "false",
                                        "Date" => "Date",
                                        "Quantity" => "Quantity",
                                        "QuantityAccumulated" => "Quantity, accumulated",
                                        "CountOfVotes" => "Count of votes",
                                        "PortionOfVotes" => "Portion of votes",
                                        "ClickRate" => "Click rate",
                                        "Subject" => "Subject",
                                        "Clicks" => "Clicks",
                                        "LinkDescription" => "Link/Description",
                                        "Top10" => "Top 10",
                                        "UNKNOWN_COUNTRY" => "unknown country",
                                        "ImageOrFileNotFound" => "Image or file '%s' not found.",
                                        "ImagesOrFilesNotFound" => "Images or files not found.",
                                        "UnknownEMailClient" => "Unknown email client",
                                        "UnknownOS" => "Unknown OS",

                                        "UniqueOpenings" => "unique openings: %d",
                                        "UniqueClicks" => "unique clicks: %d",

                                        "PlaceholderBecauseOfMailingListRuleNotAllowed" => "Placeholder '%s' not allowed, because setting of mailinglist for sub/unsubscriptions forbids this.",

                                        "SUBMITBtnText" => "Submit",
                                        "SUBSCRIBEBtnText" => "Subscribe",
                                        "UNSUBSCRIBEBtnText" => "Unsubscribe",

                                        "AccountDisabled" => "Your account was disabled by administrator.",

                                        "CampaignTargetGroupsFoundAutoCreateTextPartDisabled" => "There are target groups in HTML part. You should activate creating plain text part from HTML part to send the same content.",

                                        "MailingList" => "Recipients list",
                                        "DomainName" => "Domain",

                                        "EMailCampaignSetupIncomplete" => "Setup incomplete",
                                        "EMailCampaignSendingInProgress" => "Sending of emailing is in progress.",

                                        "SaveOnly" => "Only saved",
                                        "SendManually" => "Live sending",
                                        "SendImmediately" => "Send immediately by CronJob",
                                        "SCHEDULED" => "Scheduled ",
                                        "SendInFutureMultipleTimes" => "Send in future multiple times",

                                        "DistribListConfirmationPending" => "Confirmation pending",
                                        "DistribListSendingInProgress" => "Sending of emails is in progress.",
                                        "DistribListSendingDone" => "Sent",

                                        "RcptsColumnsChanged" => "All changes where saved. Reload recipients list to view changes.",

                                        "ALLOW_URL_FOPEN_OFF" => "allow_url_fopen is disabled in file php.ini. http:// or https:// request are not possible.",
                                        "MEMORY_LIMIT_EXCEEDED" => "Calculated size of email %s will perhaps exceed defined memory limit in file php.ini (value memory_limit=%s). You should remove images or attachments from email otherwise the script can be terminated and no emails send.",
                                        "NO_MSSQL_SUPPORT" => "Required extension to import data from Microsoft SQL servers are not installed in your PHP installation.",

                                        "Minute" => "Minute",
                                        "Hour" => "Hour",
                                        "Day" => "Day",
                                        "Month" => "Month",
                                        "Year" => "Year",
                                        "Week" => "Week",
                                        "Quarter" => "Quarter",

                                        "Minutes" => "Minute(s)",
                                        "Hours" => "Hour(s)",
                                        "Days" => "Day(s)",
                                        "Months" => "Month(s)",
                                        "Years" => "Year(s)",
                                        "Weeks" => "Week(s)",
                                        "Quarters" => "Quarter(s)",

                                        "EveryDay" => "every day",
                                        "EveryMonth" => "every month",

                                        'Monday' => 'Monday',
                                        'Tuesday' => 'Tuesday',
                                        'Wednesday' => 'Wednesday',
                                        'Thursday' => 'Thursday',
                                        'Friday' => 'Friday',
                                        'Saturday' => 'Saturday',
                                        'Sunday' => 'Sunday',

                                        "January" => "January",
                                        "February" => "February",
                                        "March" => "March",
                                        "April" => "April",
                                        "May" => "May",
                                        "June" => "June",
                                        "July" => "July",
                                        "August" => "August",
                                        "September" => "September",
                                        "October" => "October",
                                        "November" => "November",
                                        "December" => "December",

                                        'SuperAdmin' => "SuperAdmin",
                                        'Admin' => "Admin",
                                        'User' => "User",
                                        'Guest' => "Guest",

                                        "MemberActivated" => "Recipient activated",
                                        "MemberDeactivated" => "Recipient inactive",
                                        "MemberSubscribed" => "Recipient subscribed",
                                        "MemberOptInConfirmationPending" => "Opt In confirmation pending",
                                        "MemberOptOutConfirmationPending" => "Opt Out confirmation pending",

                                        "MailSendPrepared" => "sending prepared",
                                        "MailSendSent" => "sent",
                                        "MailSendFailed" => "sent failed",
                                        "MailSendPossiblySent" => "possibly sent",

                                        "undefined" => "undefined",
                                        "man"  => "male",
                                        "woman"  => "female",

                                        "IF" => "IF",
                                        "ELSE" => "ELSE",
                                        "ELSEIF" => "ELSE, IF",
                                        "GIVEOUT" => "GIVE OUT",
                                        "AND" => "AND",
                                        "OR" => "OR",

                                        "contains" => "contains",
                                        "contains_not" => "contains not",
                                        "starts_with" => "starts with",
                                        "REGEXP" => "regular expression",
                                        "IS" => "IS",

                                        "unknown_Action"  => "Unknown value for variable Action.",

                                        "SUMTESTEMAILCOUNT" => "Test emails", // must be UTF-8
                                        "SUMADMINNOTIFYMAILCOUNT" => "Information emails", // must be UTF-8
                                        "SUMOPTINCONFIRMATIONMAILCOUNT" => "Opt in confirmations emails", // must be UTF-8
                                        "SUMOPTINCONFIRMEDMAILCOUNT" => "Emails after subscribtion", // must be UTF-8
                                        "SUMOPTOUTCONFIRMATIONMAILCOUNT" => "Opt out confirmation emails", // must be UTF-8
                                        "SUMOPTOUTCONFIRMEDMAILCOUNT" => "Emails after unsubscribtion", // must be UTF-8
                                        "SUMAUTORESPONDEREMAILCOUNT" => "Autoresponder emails", // must be UTF-8
                                        "SUMBIRTHDAYRESPONDEREMAILCOUNT" => "Birthday responder emails", // must be UTF-8
                                        "SUMFOLLOWUPRESPONDEREMAILCOUNT" => "Follow up responder emails", // must be UTF-8
                                        "SUMEVENTRESPONDEREMAILCOUNT" => "Event responder emails", // must be UTF-8
                                        "SUMCAMPAIGNEMAILCOUNT" => "Emailings/Newsletters", // must be UTF-8
                                        "SUMRSS2EMAILRESPONDEREMAILCOUNT" => "RSS2EMail responder emails", // must be UTF-8
                                        "SUMEDITCONFIRMATIONMAILCOUNT" => "edit confirmations emails", // must be UTF-8
                                        "SUMEDITCONFIRMEDMAILCOUNT" => "Emails after edit", // must be UTF-8
                                        "SUMDISTRIBLISTEMAILCOUNT" => "Distribution list emails", // must be UTF-8

                                        "CantSaveFile" => "Can't save file %s. SAFE_MODE problems?",
                                        "CantOpenFile" => "Can't open file %s. SAFE_MODE problems?",

                                        "AdminNotifySubjectOnSubscribe" => "Subscription to recipients list  '%s'",
                                        "AdminNotifySubjectOnUnubscribe" => "Unsubscription of recipients list '%s'",
                                        "AdminNotifySubjectOnEdit" => "Edit of recipient in recipients list '%s'",
                                        "AdminNotifyBody" => "Recipient data:",
                                        "AdminNotifyEMailOld" => "Email address old",

                                        "EntryCount" => "&nbsp;(%RECIPIENTCOUNT%&nbsp;Entries)",
                                        "RecipientCount" => "&nbsp;(%RECIPIENTCOUNT%&nbsp;Recipients)",
                                        "MKDIR_ERROR" => "Can't create directory %s. You must create it manually by using your FTP client and set the rights to this directory to 0777.<br /><br />",
                                        "EMailCount" => "&nbsp;-&nbsp;Email:&nbsp;%EMAILCOUNT%",
                                        "SMSCount" => "&nbsp;-&nbsp;SMS:&nbsp;%EMAILCOUNT%",
                                        "NoRecipients" => "Recipients list contains no recipients or recipient not found.",

                                        "ScriptURLDifferentFromReferer" => "Check URL of HTTP request for scripts nl.php and defaultnewsletter.php. HTTP request of this %s installation does not seem correspond with data in file config_paths.inc.php. While a HTTP request parameters for subscription/unsubscription will not be submitted correctly (%s).",

                                        "SubscribeRejectLink" => "Reject subscription",
                                        "SubscribeConfirmationLink" => "Confirmation link",
                                        "UnsubscribeRejectLink" => "Reject unsubscription",
                                        "UnsubscribeConfirmationLink" => "Confirmation link",
                                        "UnsubscribeLink" => "Unsubscription link",
                                        "EditRejectLink" => "Edit reject",
                                        "EditConfirmationLink" => "Edit confirmation",
                                        "EditLink" => "Edit link",
                                        'DateShort' => 'Date short',
                                        'DateLong' => 'Date long',
                                        'Time' => 'Time',
                                        'RecipientId' => 'Recipients-Id',
                                        'MailingListId' => 'Mailinglist-Id',
                                        'SubscriptionStatus' => 'SubscriütionStatus',
                                        'DateOfSubscription' => 'Date of subscription',
                                        'DateOfOptInConfirmation' => 'Date of confirmation',
                                        'IPOnSubscription' => 'IP on subscription',
                                        'OrgMailSubject' => 'Org. email subject',
                                        'MembersAge' => 'Age of member',
                                        'LastEMailSent' => 'Date of last email sending',
                                        'Days_to_Birthday' => 'Days to birthday',
                                        'AltBrowserLink' => 'Alt. browser link',
                                        'DistribListsName' => 'Name of distribution list',
                                        'DistribListsDescription' => 'Description of distribution list',
                                        'MailingListName' => 'Name of mailing list',
                                        'DistribSenderEMailAddress' => 'Sender of email',
                                        'DistribListsSubject' => 'Subject of email',
                                        'DistribListsConfirmationLink' => 'Confirmation link',

                                        'ReasonsForUnsubscriptionSurvey' => 'Survey reasons for unsubscription',

                                        "DefaultSubscribeSubject" => "Your subscription",
                                        "DefaultSubscribePlainMail" => "Hi, \r\n\r\nthank you for subscribing to our newsletter list. \r\n\r\nTo complete your subscription please click on following link: \r\n\r\n[SubscribeConfirmationLink] \r\n\r\nDo you want to reject newsletter subscription click on following link: \r\n\r\n[SubscribeRejectLink] \r\n\r\n\r\nYours,",
                                        "DefaultSubscribeHTMLMail" => '<html><head><title></title></head><body><div style="font-size: 10pt; font-family: Verdana">Hi,<p>thank you for subscribing to our newsletter list. To complete your subscription please click on following link:</p><p><a href="[SubscribeConfirmationLink]">[SubscribeConfirmationLink]</a>.</p><p>Do you want to reject newsletter subscription click on following link:</p><p><a href="[SubscribeRejectLink]">[SubscribeRejectLink]</a>.</p><p>Yours,</p></div></body></html>',

                                        "DefaultUnsubscribeSubject" => "Your unsubscription",
                                        "DefaultUnsubscribePlainMail" => "Hi,  \r\n\r\nwe have received your newsletter unsubscription. To complete newsletter unsubscription please click on following link: \r\n\r\n[UnsubscribeConfirmationLink].  \r\n\r\nYou don\'t want to be removed from our newsletter list than click on following link \r\n\r\n[UnsubscribeRejectLink]. \r\n\r\nYours,",
                                        "DefaultUnsubscribeHTMLMail" => '<html><head><title></title></head><body><div style="font-size: 10pt; font-family: Verdana">Hi,<p>we have received your newsletter unsubscription. To complete newsletter unsubscription please click on following link: <a href="[UnsubscribeConfirmationLink]">[UnsubscribeConfirmationLink]</a>.</p><p>You don\'t want to be removed from our newsletter list than click on following link <a href="[UnsubscribeRejectLink]">[UnsubscribeRejectLink]</a>.</p><p>Yours,</p></div></body></html>',

                                        "DefaultUnsSubscribeLinkHTML" => '<p>You don\'t want to get the newsletter in the future than click on .<a href="%s">following link to unsubscribe</a>.</p>',
                                        "DefaultUnsSubscribeLinkTEXT" => "You don't want to get the newsletter in the future than click on following link %s.",

                                        "DefaultEditLinkHTML" => '<p>For changing your data <a href="%s">click here.</a>.</p>',
                                        "DefaultEditLinkTEXT" => "For changing your data click here %s.",

                                        "DefaultEditSubject" => "Your changes of subscription data",
                                        "DefaultEditPlainMail" => "Hi, \r\n\r\nthank you for changing your subscription data. \r\n\r\nPlease confirm your changes by clicking on following link: \r\n\r\n[EditConfirmationLink] \r\n\r\nDo you don\'t want to save changes than click on this link \r\n\r\n[EditRejectLink] \r\n\r\n\r\nYours,",
                                        "DefaultEditHTMLMail" => '<html><head><title></title></head><body><div style="font-size: 10pt; font-family: Verdana">Hi,<p>thank you for changing your subscription data. Please confirm your changes by clicking on following link:</p><p><a href="[EditConfirmationLink]">[EditConfirmationLink]</a>.</p><p>Do you don\'t want to save changes than click on this link</p><p><a href="[EditRejectLink]">[EditRejectLink]</a>.</p><p>Yours,</p></div></body></html>',

                                        "DefaultCaptchaText" => "Enter the word shown in next field (Spam protection)*",
                                        "DefaultReCaptchaText" => "",

                                        "CronOptInOptOutExpirationCheck" => "Checking Opt-In/Opt-Out expiration",
                                        "CronCronLogCleanUp" => "Removing old CronJob log entries",
                                        "CronMailingListStatCleanUp" => "Removing old recipients list statistic entries",
                                        "CronResponderStatCleanUp" => "Removing old responder/mailings send log entries",
                                        "CronTrackingStatCleanUp" => "Removing tracking data of responders/mailings",
                                        "CronBounceChecking" => "Checking for undeliverable emails (Hard bounces)",
                                        "CronAutoresponderChecking" => "Checking autoresponders",
                                        "CronFollowUpResponderChecking" => "Checking follow Up responders",
                                        "CronBirthdayResponderChecking" => "Checking birthday responders",
                                        "CronEventResponderChecking" => "Checking event responders",
                                        "CronSendEngineChecking" => "Checking for to send emails",
                                        "CronCampaignChecking" => "Checking emailings",
                                        "Cron" => "Script errors",
                                        "CronRSS2EMailResponderChecking" => "Checking RSS2EMail responders",
                                        "CronAutoImport" => "Import recipients automatically",
                                        "CronSplitTestChecking" => "Checking split tests",
                                        "CronSMSCampaignChecking" => "Checking SMS campaigns",
                                        "CronDistribListChecking" => "Checking distribution lists",

                                        "OutqueueSourcenone" => "undefined",
                                        "OutqueueSourceAutoresponder" => "Autoresponder",
                                        "OutqueueSourceFollowUpResponder" => "FollowUp responder",
                                        "OutqueueSourceBirthdayResponder" => "Birthday responder",
                                        "OutqueueSourceCampaign" => "Emailings",
                                        "OutqueueSourceEventResponder" => "Event responder",
                                        "OutqueueSourceRSS2EMailResponder" => "RSS2EMail responder",
                                        "OutqueueSourceSMSCampaign" => "SMS campaigns",
                                        "OutqueueSourceDistributionList" => "Distribution list",

                                        "MailFormatPlainText" => "plain text email",
                                        "MailFormatHTML" => "plain HTML email",
                                        "MailFormatMultipart" => "multi part email",

                                        "PlainText" => "Text",
                                        "HTML" => "HTML",
                                        "Multipart" => "multipart",

                                        "MailPriorityLow" => "low priority",
                                        "MailPriorityNormal" => "normal priority",
                                        "MailPriorityHigh" => "high priority",

                                        "MailFormatNotApplicable" => "Mail subject or mail body contains characters there are not applicable with current email encoding. Change email enoding to UTF-8.",
                                        "SMSTextFormatNotApplicable" => "Chars in SMS text can't be converted to iso-8859-1 charset. Please remove special character e.g. typographical quotes from SMS text.",

                                        "NoTemplateLoaded" => "No templated loaded.",
                                        "NoTemplateLoadedHint" => "You can load an existing email template by clicking on <br /><br /> <img src='images/templates24x24.gif' border='0' />.",

                                        "MySQLQueryEmptyResult" => "Query result empty",

                                        "ConfigFilesWriteable" => '<img src="images/icon_warning.gif" width="24" height="24" align="left" />WARNING: The files config.inc.php, config_db.inc.php or config_paths.inc.php are editable. Change the rights to READ ONLY for all users and READ/WRITE for the owner of file (chmod 444 on Linux/Unix OS).',
                                        "UserPathsNotWriteable" => '<img src="images/icon_warning.gif" width="24" height="24" align="left" />WARNING: The users folders to save pictures, attachments, import files and export files are not writeable. Change the rights of directory %s and subdirectories export, file, image and import to let the script write to this directories (chmod 777 on Linux/Unix OS).',

                                        "ChartNoDataText" => "No chart data available",
                                        "PBarLoadingText" => "Please wait. The chart is loading...",

                                        "UpdateAvailableSubject" => 'New version available',
                                        "UpdateAvailable" => '<img src="images/icon_information.gif" width="24" height="24" align="left" alt="Update" />&nbsp;It\'s an update for PRODUCTAPPNAME available. New version: %NEWVERSION%, %NEWVERSIONDATE%<br />&nbsp;To download the new version visit <a href="PRODUCTURL" target="_blank">PRODUCTURL</a>.',

                                        "TrackingIPBlocking" => '(IP blocking activated)',
                                        "MailingListPermissionsError" => 'You have no permissions to browse recipients in this recipients list.',

                                        "PermissionsError" => 'You have no permissions to use this function.',

                                        "GeoLiteCityDatMissing" => 'File geoip/GeoLiteCity.dat or geoip/GeoLite2-City.mmdb requires PHP 5.3.1 was not found or can\'t be opened. You can download file at http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz or http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz and unzip it to geoip directory.',

                                        "GeoConfirmedSubscribtions" => 'Confirmed subscribtions',
                                        "GeoOwnStation" => "Own station",
                                        "GeoStationOfSubscriber" => "Station of subscriber",
                                        "GeoStationOfOpener" => "Station of opener",
                                        "GeoStationOfClick" => "Station of click",

                                        "ExportOptionalFields" => "<b>Export internal fields optionally (not reimportable)</b>",
                                        "ExportOptIsActive" => "Recipient active",
                                        "ExportOptSubscriptionStatus" => "Subscription status",
                                        "ExportOptDateOfSubscription" => "Date of subscription",
                                        "ExportOptDateOfOptInConfirmation" => "Date of opt in confirmation",
                                        "ExportOptIPOnSubscription" => "IP address at subscription",
                                        "ExportOptIdentString" => "Identification string",
                                        "ExportOptLastEMailSent" => "Date of last email sending",
                                        "ExportOptBounceStatus" => "Bounce status",
                                        "ExportOptSoftbounceCount" => "Count of soft bounces",
                                        "ExportOptHardbounceCount" => "Count of hard bounces",
                                        "ExportOptHistoryOfSendEMails" => "History of sent email",
                                        "ExportOptGroupsRelations" => "Groups relationship of recipient",

                                        "ImportErrorDuplicateEntry" => "Email address '%s' always exists in list.",
                                        "ImportErrorEMailAddressIncorrect" => "Email address '%s' is syntactly incorrect.",
                                        "ImportErrorNoEMailAddress" => "'%s' isn't as valid email address.",
                                        "ImportErrorEMailAddressInGlobalBlockList" => "Email address '%s' is in globale block list.",
                                        "ImportErrorEMailAddressInLocalBlockList" => "Email address '%s' is in locale block list.",
                                        "ImportErrorEMailAddressInGlobalDomainBlockList" => "Email address '%s' is in globale domain block list.",
                                        "ImportErrorEMailAddressInLocalDomainBlockList" => "Email address '%s' is in locale domain block list.",
                                        "ImportErrorEMailAddressInECGList" => "Email address '%s' is in ECG list.",
                                        "ImportErrorFileTypeNotAllowed" => "File type are not importable, please use only CSV files (text with separator).",

                                        "rsNAStartPageTitle" => "Newsletter archive start page",
                                        "rsNAStartPageHeadline" => "Newsletter archive Sample Ltd.",
                                        "rsNAYearsLabel" => "Year:",
                                        "rsNATextBehindYears" => "Click on a year to see the newsletters of the year.",
                                        "rsNAYearsHeaderlineSelect" => "Newsletter archive [NewsletterYear]",
                                        "rsNANewsletterEntryText" => "Newsletter at [NewsletterDate]",
                                        "rsNALinkLabelToMainArchive" => "Newsletter archive - Main page",
                                        "rsNALinkLabelPrev" => "Previous",
                                        "rsNALinkLabelNext" => "Next",
                                        "rsNAImpressumText" => "<p><b>Impress</b></p>\n<p>Sample Ltd.<br>\nSample Street 11</p>\n<p><b>12345 Sample town</b></p>\n<p>Email: sample@sample.com</p>\n<p>http://www.sample.com</p>\n<p>Phone: 0123123456<br>\nFax: 0123/123457</p>\n<p>\nVAT: 123456789<br>\nCEO: Sample CEO</p>\n",
                                        "rsNAShowNewsletterWithoutFramesText" => "Show newsletter without frames",
                                        "rsNAPrintingLabel" => "Print",

                                        "WinnerTypeWinnerOpens" => "based on number of recipients who opened the email",
                                        "WinnerTypeWinnerClicks" => "based on number of recipients who clicked on links in email",
                                        "TestTypeTestSendToAllMembers" => "to all random selected recipients of recipients list.",
                                        "TestTypeTestSendToListPercentage" => "to %d%% recipients of recipients list, winner email will be send %d %s later to all other recpients.",

                                        "TabCaptionEMailAsHTML" => "Email as HTML",
                                        "TabCaptionEMailAsPlainText" => "Email as plaintext",
                                        "TabCaptionSMSAsPlainText" => "Text of SMS",
                                        "TabCaptionEMailAttachments" => "Attachments",

                                        "PWReminderLinkNotFound" => "No entry for this link found or link is invalid / outdated.",

                                        "SaveAsPNGFile" => "Save as PNG file",
                                        "SaveAsJPEGFile" => "Save as JPEG file",
                                        "ChartOptions" => "Options",

                                        "CantLoadCert" => "Can't load certificate.",
                                        "GoogleDeveloperPublicKeyMissing" => "Google API key was not specified at options therefore GoogleMaps can't be used.",

                                        "CantRemoveFUResponderMails" => 'Removing of follow up emails are inpossible because follow up responder is activated or emails of responder are in outqueue.',

                                        "AfterDoingAnAction" => "After doing action",

                                        "SampleUnsubscripeReasons" => "I receive the newsletter too often.;I find topics of newsletters not interesting.;newsletter is not displayed correctly.;other reasons",

                                        "iso-8859-2" => "Croat., Polish, Ruman., Slovak, Slovene, Czech, Hungar. (iso-8859-2)",
                                        "iso-8859-3" => "Esperanto, Galizien, Maltese, Turkish (iso-8859-3)",
                                        "iso-8859-4" => "Estonian, Latvian, Lithuanian (iso-8859-4)",
                                        "iso-8859-6" => "Arabic (iso-8859-6)",
                                        "iso-8859-7" => "Newgreek font (iso-8859-7)",
                                        "iso-8859-8" => "Hebraic font (iso-8859-8)",
                                        "iso-8859-9" => "Turkish, like 8859-1 instead of Icelan. special signs Turk. chars (iso-8859-9)",
                                        "iso-8859-10" => "Greenlandic (Inuit) und Lappish (Sami). (iso-8859-10)",
                                        "windows-1250" => "Central europe (windows-1250)",
                                        "windows-1251" => "Bulgarian, Russian, Serbian, Ukrain (windows-1251)",
                                        "windows-1252" => "US/western europe (windows-1252)",
                                        "windows-1253" => "Greek (windows-1253)",
                                        "windows-1254" => "Turkish (windows-1254)",
                                        "windows-1255" => "Hebraic (windows-1255)",
                                        "windows-1256" => "Arabic (windows-1256)",
                                        "windows-1257" => "Estonian, Latvian, Lithuanian (windows-1257)",
                                        "windows-1258" => "Vietnamese (windows-1258)",
                                        "KOI8-R" => "Cyrillic (KOI8-R)",
                                        "KOI8-U" => "Cyrillic (KOI8-U)",
                                        "KOI8-RU" => "Cyrillic (KOI8-RU)"

                                       );

?>
