<?php

// version 0.1
/* Configuration constants */
// Paramaters you change begin with "your" e.g. yourSolve360EmailAddress, yourSolve360Token,

require_once 'Solve360Service.php';

// User to authenticate as 
define('SOLVE360_LOGIN', 'divya@eventseventos.info');
define('SOLVE360_TOKEN', 'w1q0d2A428seI0lc07wf40Q6=1K9R7G0q1EcA6f1');

// Category tag for new lead
define('NEW_LEAD_CATEGORY_ID', 156891048);

// Category tag that must be applied to trigger the new client scenario
define('NEW_ASSIGNED_CATEGORY_ID', 156891076);

// Notifications from
define('NOTIFICATION_SENDER',  'Eventos <divya@eventseventos.info>');

// Manager Ids and Email Addresses
// Add IDs, Emails, and Names in the same order

	$mgrId = array(155193660, 155207302);
	$mgrEmail = array('divya@eventseventos.info', 'sindhuravulapalli@eventseventos.info');
	$mgrName = array('Divya Chitti', 'Sindgu Ravulapalli');

	// Choose the next manager in the queue
	
	// Read the index counter:
		$que_dir = "/home/dakula123/public_html/Solve360/";
		$que_num = $que_dir."assignnum.txt";
		$fr = fopen("$que_num","r"); 
		$counter = fgets($fr); 
		fclose($fr); 
	
	// Set the manager variables and assign the lead by array index
		$managerId = $mgrId[$counter];
		$managerEmail = $mgrEmail[$counter];	
		$managerName = $mgrName[$counter];	
		
		define('ASSIGNEE_ID', $managerId);
		define('ASSIGNEE_EMAIL', $managerEmail);
		define('ASSIGNEE_NAME', $managerName);
		
	// Increase index counter by one or reset to 0 for the next assignment
		$fw = fopen("$que_num","w");

		$indexCount = count($mgrId);
	
		if ($counter < $indexCount-1){
			$counter = $counter + 1; 
		} else {
			$counter = 0;
		}
		fputs($fw,$counter); 
		fclose($fw);  

// ----------------------------------------------------------------------------

// Getting raw request data
$postdata = file_get_contents("php://input");
// First verify the request has come from Solve360:
//if (hash_hmac('sha256', $postdata, WEBHOOKS_SECRET) !== $_SERVER['HTTP_X_SOLVE360_HMAC_SHA256']) {
//    header('Server Error', true, 500);
//    die();
//}
// Prepare post data as an xml object:
$notification = new SimpleXMLElement($postdata);
// Calling different handlers for different events
$handler = str_replace('.' , '_', $notification->type); // e.g. items.update => items_update
if (!function_exists($handler)) {
    header('Bad event type', true, 500);
    die();
}
call_user_func($handler, $notification);

function items_categorize($notification) {
	//Pause the script between iterations so the manager index count can catch up
	sleep(4);
	

    // A contact has just been categorized
    // Check if it has both Client and Lead tag set (meaning - it is a new client but it's not processed yet)
    $client = false;
    $lead = false;
    foreach ($notification->content->categories->children() as $category) {
        if ($category->id == NEW_ASSIGNED_CATEGORY_ID) {
            $client = true;
        }
        if ($category->id == NEW_LEAD_CATEGORY_ID) {
            $lead = true;
        }
        if ($client && $lead) {
            break;
        }
    }
    if (!$client || !$lead) {
        // If it's not a client - we don't need to do anything with it yet
        // If it's not a lead - it has been processed before
        die();
    }

	// It must have a email and last name
    if (empty($notification->content->item->fields->lastname)) {
        // No last name
        die();
    }
    $emailSet = false;
    foreach (array('personalemail', 'businessemail', 'otheremail') as $emailField) {
        if (!empty($notification->content->item->fields->{$emailField})) {
            $emailSet = $notification->content->item->fields->{$emailField};
            break;
        }
    }
    if (!$emailSet) {
        // No email set
        die();
    }

    // It mustn't be assigned to anyone
    if (!empty($notification->content->item->fields->assignedto)) {
        // Already assigned
        die();
    }

    //Now we know it's a new lead 
	
	// Assign the lead to a manager
		$solve360Api = new Solve360Service(SOLVE360_LOGIN, SOLVE360_TOKEN);
		$solve360Api->editContact($notification->objectid, array('assignedto' => ASSIGNEE_ID));
		
	sleep(2);
	// Get the lead's name and link to record
		$name = $notification->content->item->name;
		$permalink = 'https://secure.solve360.com/contact/' . $notification->objectid;
		
    // Send the manager an email
		mail(ASSIGNEE_EMAIL, 'A new lead has been assigned to you', "
			Name: $name
			Email: $emailSet
			$permalink
		", 'From: ' . NOTIFICATION_SENDER . "\r\n" . 'Reply-To: ' . NOTIFICATION_SENDER . "\r\n");
		
	sleep(2);	
	// Send the lead an email
		$acctManager = ASSIGNEE_NAME;
		mail($emailSet, 'Welcome to Eventos-Auditorium Management System', "
			Dear $name,

			Welcome to the Eventos.

			To know more about our services visit: www.eventseventos.info

			Please feel free to contact our representative $acctManager

			Thanking you

			Eventosteam
		", 'From: ' . NOTIFICATION_SENDER . "\r\n" . 'Reply-To: ' . NOTIFICATION_SENDER . "\r\n");
	

    // Remove the New tag
    $solve360Api = new Solve360Service(SOLVE360_LOGIN, SOLVE360_TOKEN);
    $solve360Api->uncategorizeContact($notification->objectid, NEW_LEAD_CATEGORY_ID);

    // Apply the Assigned tag
    $solve360Api->categorizeContact($notification->objectid, NEW_ASSIGNED_CATEGORY_ID);
		
}