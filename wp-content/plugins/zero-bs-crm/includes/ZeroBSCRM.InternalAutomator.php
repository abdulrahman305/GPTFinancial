<?php 
/*!
 * Jetpack CRM
 * https://jetpackcrm.com
 * V1.1.15
 *
 * Copyright 2020 Automattic
 *
 * Date: 30/08/16
 */

defined( 'ZEROBSCRM_PATH' ) || exit( 0 );






 
/* ======================================================
	Internal Automator
   ====================================================== */

#} Main automator func, this'll get run at the bottom of all the main actions
#} e.g. when adding a new invoice:
#} 		zeroBSCRM_FireInternalAutomator('invoice.new',$newInvoice);
/*
	Current action str:
	
		log.new
		log.update
		log.delete

		customer.new
		company.new (not yet logging)
		quote.new
		invoice.new


	#} 1.2.7 added a catcher for dupes... if "quote.new" for example is fired with the same setup within 1 script run, it'll ignore second...

*/
global $zeroBSCRM_IA_ActiveAutomations, $zeroBSCRM_IA_Dupeblocks; $zeroBSCRM_IA_Dupeblocks = array('quote.new','invoice.new','transaction.new');
function zeroBSCRM_FireInternalAutomator($actionStr='',$obj=array()){

	$goodToGo = true;

	global $zbs,$zeroBSCRM_IA_ActiveAutomations, $zeroBSCRM_IA_Dupeblocks;

	#} Some legacy support
	$actionStr = zeroBSCRM_InternalAutomatorLegacyActionCheck($actionStr);

	#} dupe catch
	if (in_array($actionStr,$zeroBSCRM_IA_Dupeblocks)){

		if (gettype($obj) != "string" && gettype($obj) != "String"){
			#$objStr = implode('.',$obj);
			$objStr = json_encode($obj);
			$objStr = md5($objStr);
			$actionHash = $actionStr.$objStr;
		} else 
			$actionHash = $actionStr.md5($obj);
		
		if (isset($zeroBSCRM_IA_ActiveAutomations[$actionHash])) 
			$goodToGo = false; #} DUPE
		else
			$zeroBSCRM_IA_ActiveAutomations[$actionHash] = time();

	}

	#} Internal automator block (Migration routine first use)
	if ($zbs->internalAutomatorBlock) $goodToGo = false;


	if ($goodToGo && !empty($actionStr)){

		#} Action str should be alphanumeric with periods

		#} Checks if there's a global variable (work list) for this $actionStr
		$actionHolderName = 'zeroBSCRM_IA_Action_'.str_replace('.','_',$actionStr);

		#} Exists?
		if (isset($GLOBALS[ $actionHolderName ]) && is_array($GLOBALS[ $actionHolderName ])){

			#} If here, has an array 
			foreach ($GLOBALS[ $actionHolderName ] as $action){

				if (isset($action['act']) && !empty($action['act']) && isset($action['params'])){

					#} ['params'] not used yet... future proofing.

					#} Fire any applicable
					if (function_exists($action['act'])){

						#} call it, (and pass whatever was passed to this)

		

						call_user_func($action['act'],$obj);

					}


				}

			}

		}

	}

	return;	

}


function zeroBSCRM_AddInternalAutomatorRecipe($actionStr='',$functionName='',$paramsObj=array()){

	if (!empty($actionStr) && !empty($functionName)){

		#} Some legacy support
		$actionStr = zeroBSCRM_InternalAutomatorLegacyActionCheck($actionStr);

		#} Action str should be alphanumeric with periods

		#} Checks if there's a global variable (work list) for this $actionStr
		$actionHolderName = 'zeroBSCRM_IA_Action_'.str_replace('.','_',$actionStr);

		#} Init?
		if (!isset($GLOBALS[ $actionHolderName ])) $GLOBALS[ $actionHolderName ] = array();

		#} Append.
		array_push($GLOBALS[ $actionHolderName ],array('act'=>$functionName,'params'=>$paramsObj));

		return true;

	}

	return false;
}

// checks for newer labels + converts
function zeroBSCRM_InternalAutomatorLegacyActionCheck($actionStr = ''){
	
	#} Some legacy support
	switch ($actionStr){

		case 'contact.new':
			$actionStr = 'customer.new';
			break;
		case 'contact.update':
			$actionStr = 'customer.update';
			break;
		case 'contact.status.update':
			$actionStr = 'customer.status.update';
			break;

	}

	return $actionStr;

}
 
/* ======================================================
	/ Internal Automator
   ====================================================== */
