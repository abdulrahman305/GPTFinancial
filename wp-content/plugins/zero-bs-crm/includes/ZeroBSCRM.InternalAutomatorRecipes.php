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
  Setup Internal Automator Recipes:
   ====================================================== */

   // Note on contact.vitals.update vs contact.update,
   // ... if contact.vitals.update fires, it will fire AS WELL as contact.update.
   // ... if contact.email.update fires, it will fire AS WELL as contact.update.

	#} Set IA Recipes (CREATED)
	zeroBSCRM_AddInternalAutomatorRecipe('contact.new','zeroBSCRM_IA_NewCustomerClientPortal',array());
	zeroBSCRM_AddInternalAutomatorRecipe('contact.new','zeroBSCRM_IA_NewCustomerLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('contact.status.update','zeroBSCRM_IA_CustomerStatusChangePortalAndLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('contact.status.update','zeroBSCRM_IA_CustomerStatusChangeAutoLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('contact.new','zeroBSCRM_IA_ContactSegmentCompiler',array()); // for edit + new
	zeroBSCRM_AddInternalAutomatorRecipe('contact.update','zeroBSCRM_IA_ContactSegmentCompiler',array()); // for edit + new
	zeroBSCRM_AddInternalAutomatorRecipe('company.new','zeroBSCRM_IA_NewCompanyLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('quote.new','zeroBSCRM_IA_NewQuoteLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('quote.new','zeroBSCRM_IA_quoteSegmentCompiler',array());
	zeroBSCRM_AddInternalAutomatorRecipe('quote.update','zeroBSCRM_IA_quoteSegmentCompiler',array());
	zeroBSCRM_AddInternalAutomatorRecipe('quote.accepted','zeroBSCRM_IA_AcceptedQuoteLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('invoice.new','zeroBSCRM_IA_NewInvoiceLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('invoice.new','zeroBSCRM_IA_invoiceSegmentCompiler',array());
	zeroBSCRM_AddInternalAutomatorRecipe('invoice.update','zeroBSCRM_IA_invoiceSegmentCompiler',array());
	zeroBSCRM_AddInternalAutomatorRecipe('log.new','zeroBSCRM_IA_NewLogCatchContactsDB2',array());
	zeroBSCRM_AddInternalAutomatorRecipe('transaction.new','zeroBSCRM_IA_NewTransactionLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('transaction.new','zeroBSCRM_IA_transactionSegmentCompiler',array());
	zeroBSCRM_AddInternalAutomatorRecipe('transaction.update','zeroBSCRM_IA_transactionSegmentCompiler',array());
	zeroBSCRM_AddInternalAutomatorRecipe('event.new','zeroBSCRM_IA_NewEventLog',array());
	zeroBSCRM_AddInternalAutomatorRecipe('clientwpuser.new','zeroBSCRM_IA_NewClientPortalUserLog',array());

	#} Set IA  Recipes (UPDATED)
	#} - WH commented out, you need to have a corresponding function for any of these you add:
	#// zeroBSCRM_AddInternalAutomatorRecipe('status.change','zeroBSCRM_IA_StatusChange',array());

	// WP Hook tie-ins (for Mike [and 3rd party developers!], mostly).
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.new', 'zeroBSCRM_IA_NewCustomerWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.update', 'zeroBSCRM_IA_EditCustomerWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.status.update', 'zeroBSCRM_IA_EditCustomerWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.vitals.update', 'zeroBSCRM_IA_EditCustomerVitalsWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.email.update', 'zeroBSCRM_IA_EditCustomerEmailWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.delete', 'zeroBSCRM_IA_DeleteCustomerWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'company.new', 'zeroBSCRM_IA_NewCompanyWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'company.delete', 'zeroBSCRM_IA_DeleteCompanyWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'quote.new', 'zeroBSCRM_IA_NewQuoteWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'quote.accepted', 'zeroBSCRM_IA_AcceptedQuoteWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'quote.update', 'zeroBSCRM_IA_EditInvoiceWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'quote.status.update', 'zeroBSCRM_IA_EditInvoiceWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'quote.delete', 'zeroBSCRM_IA_DeleteQuoteWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'invoice.new', 'zeroBSCRM_IA_NewInvoiceWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'invoice.update', 'zeroBSCRM_IA_EditInvoiceWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'invoice.status.update', 'zeroBSCRM_IA_EditInvoiceWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'invoice.delete', 'zeroBSCRM_IA_DeleteInvoiceWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'transaction.new', 'zeroBSCRM_IA_NewTransactionWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'transaction.delete', 'zeroBSCRM_IA_DeleteTransactionWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'event.new', 'zeroBSCRM_IA_NewEventWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'event.update', 'zeroBSCRM_IA_UpdateEventWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'event.delete', 'zeroBSCRM_IA_DeleteEventWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'clientwpuser.new', 'zeroBSCRM_IA_NewClientPortalUserHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'segment.delete', 'zeroBSCRM_IA_DeleteSegmentWPHook', array() );
	zeroBSCRM_AddInternalAutomatorRecipe( 'contact.before.delete', 'zeroBSCRM_IA_BeforeDeleteCustomerWPHook', array() );
	// don't need to expose tbh
	//zeroBSCRM_AddInternalAutomatorRecipe('form.delete','zeroBSCRM_IA_DeleteFormWPHook',array());



// DAL3.0 + can use these:
/* 
	company.new
	company.update
	company.status.update

	quote.new
	quote.update

	log.update
	
	event.new
	event.update

	form.new
	form.update

	invoice.new
	invoice.update
	invoice.status.update

	transaction.new
	transaction.update
	transaction.status.update

	quotetemplate.new
	quotetemplate.update

*/

/* ======================================================
  / Setup Internal Automator Recipes:
   ====================================================== */


/* ======================================================
  Internal Automator Recipe Functions
   ====================================================== */



	#} Adds a "created" log to users (if setting)
	function zeroBSCRM_IA_NewCustomerLog($obj=array()){

		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_customer_new');

		if ($autoLogThis > 0){

			#} Retrieve necessary info:
			$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['id'])) $zbsNoteAgainstPostID = (int)$obj['id'];
			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

				#} First check if an override is passed...
				if (isset($obj['automatorpassthrough']) && is_array($obj['automatorpassthrough']) && isset($obj['automatorpassthrough']['note_override']) && is_array($obj['automatorpassthrough']['note_override']) && isset($obj['automatorpassthrough']['note_override']['type'])){

					#} An overriding note has been passed, just use that

						#} Add log
						$newLogID = zeroBS_addUpdateContactLog($zbsNoteAgainstPostID,-1,-1,$obj['automatorpassthrough']['note_override']);

				} else {

					#} No override, use default processing...



					#} Set Deets
					$newCustomerName = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['customerMeta']) && is_array($obj['customerMeta'])) $newCustomerName = zeroBS_customerName($obj['id'],$obj['customerMeta'],false,true);
					$noteShortDesc = 'Customer Created'; if (!empty($newCustomerName)) $noteShortDesc = $newCustomerName;
					$note_long_description = '';

					// Custom short desc for external source creations
		            if (isset($obj['extsource']) && !empty($obj['extsource'])){

		            	$uid = '';

		            	// We seem to pass either the external source array (woo)
		            	// ... or a string 'pay', so lets select the right one...
		            	if ( is_array( $obj['extsource'] ) && isset( $obj['extsource']['source'] ) ){

		            		$source_key = $obj['extsource']['source'];

		            		// if we have this we also have UID
		            		$uid = $obj['extsource']['uid'];

		            	} else {
		            		$source_key = $obj['extsource'];
		            	}

		                switch ( $source_key ){

		                    case 'pay':

		                        $note_long_description = __( 'Created from PayPal', 'zero-bs-crm' ) . '<i class="fa fa-paypal"></i>';

		                        break;

		                    case 'woo':

		                        $note_long_description = __( 'Created from WooCommerce Order', 'zero-bs-crm' ) . ' <i class="fa fa-shopping-cart"></i>';

		                        break;

		                    case 'env':

		                        $note_long_description = __( 'Created from Envato', 'zero-bs-crm' ) . '<i class="fa fa-envira"></i>';

		                        break;

		                    case 'form':

		                        $note_long_description = __( 'Created from Form Capture', 'zero-bs-crm' ) . '<i class="fa fa-wpforms"></i>';

		                        break;

		                    case 'csv':

		                        $note_long_description = __( 'Created from CSV Import', 'zero-bs-crm' ) . '<i class="fa fa-file-text"></i>';

		                        break;

		                    case 'gra':

		                        $note_long_description = __( 'Created from Gravity Forms', 'zero-bs-crm' ) . '<i class="fa fa-wpforms"></i>';

		                        break;

		                    default:

		                        // Generic fallback (shouldn't ever fire)
		                        $note_long_description = __( 'Created from External Source', 'zero-bs-crm' ) . ' <i class="fa fa-users"></i>';

		                        break;

		                }
                   
		                // allow extension override via filter (e.g. WooSync)
                    $note_long_description = apply_filters( 'jpcrm_new_contact_log', $note_long_description, $source_key, $uid );


		            }


					#} Add log
					$newLogID = zeroBS_addUpdateContactLog($zbsNoteAgainstPostID,-1,-1,array(
						'type' => 'Created',
						'shortdesc' => $noteShortDesc,
						'longdesc' => $note_long_description
					));

				} # / end of if no override

			}

		}


	}
	#} Adds a "created" log to users (if setting)
	function zeroBSCRM_IA_NewCompanyLog($obj=array()){

		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_company_new');

		if ($autoLogThis > 0){

			#} Retrieve necessary info:
			$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['id'])) $zbsNoteAgainstPostID = (int)$obj['id'];
			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

				#} First check if an override is passed...
				if (isset($obj['automatorpassthrough']) && is_array($obj['automatorpassthrough']) && isset($obj['automatorpassthrough']['note_override']) && is_array($obj['automatorpassthrough']['note_override']) && isset($obj['automatorpassthrough']['note_override']['type'])){

					#} An overriding note has been passed, just use that

						#} Add log
						$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,$obj['automatorpassthrough']['note_override'],'zerobs_company');

				} else {

					#} No override, use default processing...



					#} Set Deets
					$newCompanyName = ''; if (is_array($obj) && isset($obj['id']) && isset($obj['companyMeta']) && is_array($obj['companyMeta'])) $newCompanyName = zeroBS_companyName($obj['id'],$obj['companyMeta'],false,true);
					$noteShortDesc = 'Company Created'; if (!empty($newCompanyName)) $noteShortDesc = $newCompanyName;
					$note_long_description = '';

					// Custom short desc for external source creations
		            if (isset($obj['extsource']) && !empty($obj['extsource'])){

		            	// We seem to pass either the external source array (woo)
		            	// ... or a string 'pay', so lets select the right one...
		            	if ( is_array( $obj['extsource'] ) && isset( $obj['extsource']['source'] ) ){

		            		$source_key = $obj['extsource']['source'];

		            		// if we have this we also have UID
		            		$uid = $obj['extsource']['uid'];

		            	} else {
		            		$source_key = $obj['extsource'];
		            	}

		                switch ( $source_key ){

		                    case 'pay':

		                        $note_long_description = 'Created from PayPal <i class="fa fa-paypal"></i>';

		                        break;

		                    case 'woo':

		            			if ( isset( $uid ) ){
		                        	$note_long_description = sprintf( __( 'Created from WooCommerce Order #%s', 'zero-bs-crm' ), $uid ) . ' <i class="fa fa-shopping-cart"></i>';
		                        } else {
		                        	$note_long_description = __( 'Created from WooCommerce Order', 'zero-bs-crm' ) . ' <i class="fa fa-shopping-cart"></i>';
		                        }

		                        break;

		                    case 'env':

		                        $note_long_description = 'Created from Envato <i class="fa fa-envira"></i>';

		                        break;

		                    case 'form':

		                        $note_long_description = 'Created from Form Capture <i class="fa fa-wpforms"></i>';

		                        break;

		                    case 'csv':

		                        $note_long_description = 'Created from CSV Import <i class="fa fa-file-text"></i>';

		                        break;

		                    default:

		                        #} Generic for now (SHOULD NEVER CALL)
		                        $note_long_description = 'Created from External Source <i class="fa fa-users"></i>';

		                        break;

		                }


		            }


					#} Add log
					$newLogID = zeroBS_addUpdateLog($zbsNoteAgainstPostID,-1,-1,array(
						'type' => 'Created',
						'shortdesc' => $noteShortDesc,
						'longdesc' => $note_long_description
					),'zerobs_company');

				} # / end of if no override

			}

		}


	}

	#} Adds a "created" log to customer (of quotes) (if setting)
	function zeroBSCRM_IA_NewQuoteLog($obj=array()){


		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_quote_new');

		if ($autoLogThis > 0){

				// 3.0+
				#} Retrieve necessary info:
				$noteAgainstIDs = array('contacts'=>array(),'companies'=>array()); 
				if (is_array($obj) && isset($obj['againstids']) && is_array($obj['againstids'])){

					// trusting they're correctly passed...
					if (isset($obj['againstids']['contacts']) && is_array($obj['againstids']['contacts'])) $noteAgainstIDs['contacts'] = $obj['againstids']['contacts'];
					if (isset($obj['againstids']['companies']) && is_array($obj['againstids']['companies'])) $noteAgainstIDs['companies'] = $obj['againstids']['companies'];

				}
				$quoteID = ''; if (is_array($obj) && isset($obj['id'])) $quoteID = $obj['id'];
				$quoteTitle = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['title']) && !empty($obj['data']['title'])) $quoteTitle = $obj['data']['title'];
				$quoteValue = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['value']) && !empty($obj['data']['value'])) $quoteValue = zeroBSCRM_formatCurrency($obj['data']['value']);
				$extsource = ''; if (is_array($obj) && is_array($obj['extsource']) && isset($obj['extsource']['source']) && !empty($obj['extsource']['source'])) $extsource = $obj['extsource']['source'];
				$extsourceID = ''; if (is_array($obj) && is_array($obj['extsource']) && isset($obj['extsource']['uid']) && !empty($obj['extsource']['uid'])) $extsourceID = $obj['extsource']['uid'];
				
				// build str
				$note_long_description = '';
				$noteShortDesc = '';
				if (!empty($quoteID)) $noteShortDesc .= '#'.$quoteID;
				if (!empty($quoteTitle)) $noteShortDesc .= $quoteTitle;
				if (!empty($quoteValue)) $noteShortDesc .= ' ('.$quoteValue.')';
				if (!empty($extsource) && !empty($extsourceID)) $note_long_description = __('Created by','zero-bs-crm').' '.zeroBS_getExternalSourceTitle($extsource,$extsourceID);

				if (is_array($noteAgainstIDs['contacts']) && count($noteAgainstIDs['contacts']) > 0) foreach ($noteAgainstIDs['contacts'] as $cID){

					#} Add log
					$newLogID = zeroBS_addUpdateLog($cID,-1,-1,array(
						'type' => __('Quote Created','zero-bs-crm'),
						'shortdesc' => $noteShortDesc,
						'longdesc' => $note_long_description
					),'zerobs_customer');

				}

		}


	}

	// Adds an "accepted" log to contact (of quote) (if setting)
	function zeroBSCRM_IA_AcceptedQuoteLog($obj=array()){


		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_quote_accepted');

		if ($autoLogThis > 0){

			global $zbs;

				// retrieve quote
				$quoteID = ''; if (is_array($obj) && isset($obj['id'])) $quoteID = $obj['id'];
				$quote = $zbs->DAL->quotes->getQuote($quoteID);

				if ( is_array($quote) && count($quote['contact']) > 0 ){

					// get signed by if passed
					$signedBy = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['signed']) && !empty($obj['data']['signed'])) $signedBy = $obj['data']['signed'];
					// could get `ip` but not really user friendly/needed

					// build str
					$note_long_description = '';
					$noteShortDesc = '';
					if (!empty($quoteID)) $noteShortDesc .= '#'.$quoteID;
					if (!empty($quoteTitle)) $noteShortDesc .= $quote['title'];
					if (!empty($quoteValue)) $noteShortDesc .= ' ('.zeroBSCRM_formatCurrency($quote['value']).')';
					if (!empty($signedBy)) $note_long_description = __('Signed','zero-bs-crm').' '.$signedBy;

					if (is_array($quote['contact'])) foreach ($quote['contact'] as $contact){

						#} Add log
						$newLogID = zeroBS_addUpdateLog($contact['id'],-1,-1,array(
							'type' => __('Quote: Accepted','zero-bs-crm'),
							'shortdesc' => $noteShortDesc,
							'longdesc' => $note_long_description
						),'zerobs_customer');

					}

				}

		}


	}


	#} Adds a "created" log to customer (of quotes) (if setting)
	function zeroBSCRM_IA_NewInvoiceLog($obj=array()){

		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_invoice_new');

		if ($autoLogThis > 0){

				#} Retrieve necessary info:
				$noteAgainstIDs = array('contacts'=>array(),'companies'=>array()); 
				if (is_array($obj) && isset($obj['againstids']) && is_array($obj['againstids'])){

					// trusting they're correctly passed...
					if (isset($obj['againstids']['contacts']) && is_array($obj['againstids']['contacts'])) $noteAgainstIDs['contacts'] = $obj['againstids']['contacts'];
					if (isset($obj['againstids']['companies']) && is_array($obj['againstids']['companies'])) $noteAgainstIDs['companies'] = $obj['againstids']['companies'];

				}
				$invoiceID = ''; if (is_array($obj) && isset($obj['id'])) $invoiceID = $obj['id'];
				$invoiceRef = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['id_override']) && !empty($obj['data']['id_override'])) $invoiceRef = $obj['data']['id_override'];
				$invoiceValue = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['total']) && !empty($obj['data']['total'])) $invoiceValue = zeroBSCRM_formatCurrency($obj['data']['total']);
				$extsource = ''; if (is_array($obj) && is_array($obj['extsource']) && isset($obj['extsource']['source']) && !empty($obj['extsource']['source'])) $extsource = $obj['extsource']['source'];
				$extsourceID = ''; if (is_array($obj) && is_array($obj['extsource']) && isset($obj['extsource']['uid']) && !empty($obj['extsource']['uid'])) $extsourceID = $obj['extsource']['uid'];
				
				// build str
				$note_long_description = '';
				$noteShortDesc = '';
				if (!empty($invoiceID) && empty($invoiceRef)) $noteShortDesc .= '#'.$invoiceID;
				if (!empty($invoiceRef)) $noteShortDesc .= $invoiceRef;
				if (!empty($invoiceValue)) $noteShortDesc .= ' ('.$invoiceValue.')';
				if (!empty($extsource) && !empty($extsourceID)) $note_long_description = __('Created by','zero-bs-crm').' '.zeroBS_getExternalSourceTitle($extsource,$extsourceID);

				if (is_array($noteAgainstIDs['contacts']) && count($noteAgainstIDs['contacts']) > 0) foreach ($noteAgainstIDs['contacts'] as $cID){

					#} Add log
					$newLogID = zeroBS_addUpdateLog($cID,-1,-1,array(
						'type' => __('Invoice Created','zero-bs-crm'),
						'shortdesc' => $noteShortDesc,
						'longdesc' => $note_long_description
					),'zerobs_customer');

				}

		}

	}

	#} Adds a "created" log to customer (of trans) (if setting)
	function zeroBSCRM_IA_NewTransactionLog($obj=array()){

		$newLogID = false;

		#} if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_transaction_new');

		if ($autoLogThis > 0){

				#} Retrieve necessary info:
				$noteAgainstIDs = array('contacts'=>array(),'companies'=>array()); 
				if (is_array($obj) && isset($obj['againstids']) && is_array($obj['againstids'])){

					// trusting they're correctly passed...
					if (isset($obj['againstids']['contacts']) && is_array($obj['againstids']['contacts'])) $noteAgainstIDs['contacts'] = $obj['againstids']['contacts'];
					if (isset($obj['againstids']['companies']) && is_array($obj['againstids']['companies'])) $noteAgainstIDs['companies'] = $obj['againstids']['companies'];

				}
				$transactionID = ''; if (is_array($obj) && isset($obj['id'])) $transactionID = $obj['id'];
				$transactionRef = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['ref']) && !empty($obj['data']['ref'])) $transactionRef = $obj['data']['ref'];
				$transactionValue = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['total']) && !empty($obj['data']['total'])) $transactionValue = zeroBSCRM_formatCurrency($obj['data']['total']);
				$extsource = ''; if (is_array($obj) && is_array($obj['extsource']) && isset($obj['extsource']['source']) && !empty($obj['extsource']['source'])) $extsource = $obj['extsource']['source'];
				$extsourceID = ''; if (is_array($obj) && is_array($obj['extsource']) && isset($obj['extsource']['uid']) && !empty($obj['extsource']['uid'])) $extsourceID = $obj['extsource']['uid'];
				
				// build str
				$note_long_description = '';
				$noteShortDesc = '';
				if (!empty($transactionID) && empty($transactionRef)) $noteShortDesc .= '#'.$transactionID;
				if (!empty($transactionRef)) $noteShortDesc .= $transactionRef;
				if (!empty($transactionValue)) $noteShortDesc .= ' ('.$transactionValue.')';
				if (!empty($extsource) && !empty($extsourceID)) $note_long_description = __('Created by','zero-bs-crm').' '.zeroBS_getExternalSourceTitle($extsource,$extsourceID);

				if (is_array($noteAgainstIDs['contacts']) && count($noteAgainstIDs['contacts']) > 0) foreach ($noteAgainstIDs['contacts'] as $cID){

					#} Add log
					$newLogID = zeroBS_addUpdateLog($cID,-1,-1,array(
						'type' => __('Transaction Created','zero-bs-crm'),
						'shortdesc' => $noteShortDesc,
						'longdesc' => $note_long_description
					),'zerobs_customer');

				}

		} // / if autolog


		return $newLogID;

	} 

	#} Adds a "created" log to customer (of task) (if setting)
	function zeroBSCRM_IA_NewEventLog($obj=array()){

		$newLogID = false;

		#} if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_event_new');

		#} if has id
		$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['againstid']) && $obj['againstid'] > 0) $zbsNoteAgainstPostID = (int)$obj['againstid'];

		if ($autoLogThis > 0 && isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

				#} Retrieve necessary info:
				$noteAgainstIDs = array('contacts'=>array(),'companies'=>array()); 
				if (is_array($obj) && isset($obj['againstids']) && is_array($obj['againstids'])){

					// trusting they're correctly passed...
					if (isset($obj['againstids']['contacts']) && is_array($obj['againstids']['contacts'])) $noteAgainstIDs['contacts'] = $obj['againstids']['contacts'];
					if (isset($obj['againstids']['companies']) && is_array($obj['againstids']['companies'])) $noteAgainstIDs['companies'] = $obj['againstids']['companies'];

				}
				$taskID = ''; if (is_array($obj) && isset($obj['id'])) $taskID = $obj['id'];
				$taskTitle = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['title']) && !empty($obj['data']['title'])) $taskTitle = $obj['data']['title'];
				$taskDescription = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['desc']) && !empty($obj['data']['desc'])) $taskDescription = $obj['data']['desc'];
				$taskStart = ''; if (is_array($obj) && is_array($obj['data']) && isset($obj['data']['start']) && !empty($obj['data']['start'])) $taskStart = zeroBSCRM_locale_utsToDatetime($obj['data']['start']);

				// build str
				$note_long_description = '';
				$noteShortDesc = '';
				if (!empty($taskID) && empty($taskTitle)) $noteShortDesc .= '#'.$taskID;
				if (!empty($taskTitle)) $noteShortDesc .= $taskTitle;
				if (!empty($taskDescription)) $note_long_description = $taskDescription;
				if (!empty($taskStart)) {

					// pad if filled
					if (!empty($note_long_description)) $note_long_description .= '<br />';

					// add starting date
					$note_long_description .= __('Starts at ','zero-bs-crm').' '.$taskStart;

				}

				if (is_array($noteAgainstIDs['contacts']) && count($noteAgainstIDs['contacts']) > 0) foreach ($noteAgainstIDs['contacts'] as $cID){

					#} Add log
					$newLogID = zeroBS_addUpdateLog($cID,-1,-1,array(
						'type' => __('Task Created','zero-bs-crm'),
						'shortdesc' => $noteShortDesc,
						'longdesc' => $note_long_description
					),'zerobs_customer');

				}

		}


		return $newLogID;

	} 

/**
 * Catches new logs and updates contact 'last contacted' if contact type log
 *
 * @param arr $obj Array containing log details.
 */
function zeroBSCRM_IA_NewLogCatchContactsDB2( $obj = array() ) {

	global $zbs;

	// for now, only contacts

	if ( is_array( $obj ) && isset( $obj['logagainsttype'] ) && ( $obj['logagainsttype'] === 'zerobs_customer' || $obj['logagainsttype'] === ZBS_TYPE_CONTACT ) ) {

		// check if 'contact' type
		$log_type = '';
		if ( is_array( $obj ) && isset( $obj['logtype'] ) ) {
			$log_type = $obj['logtype'];
		}

		if ( ! empty( $log_type ) && in_array( $log_type, $zbs->DAL->logs->contact_log_types, true ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			// checks out..proceed

			// retrieve
			$contact_id = -1;
			if ( is_array( $obj ) && isset( $obj['logagainst'] ) ) {
				$contact_id = (int) $obj['logagainst'];
			}

			if ( ! empty( $contact_id ) && $contact_id > 0 ) {

				// update contact
				$zbs->DAL->contacts->setContactLastContactUTS( $contact_id, time() ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}
		} // is a contact type log

	} // is log against contact
}


	#} Adds a "created" log to customer (if setting)
	function zeroBSCRM_IA_NewClientPortalUserLog($obj=array()){


		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_clientportal_new');

		if ($autoLogThis > 0){

			#} Retrieve necessary info:
			$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['againstid']) && $obj['againstid'] > 0) $zbsNoteAgainstPostID = (int)$obj['againstid'];
			#TRANSITIONTOMETANO		
			$userID = -1; if (is_array($obj) && isset($obj['id']) && $obj['id'] > 0) $userID = (int)$obj['id'];
			$userEmail = ''; if (is_array($obj) && isset($obj['userEmail'])) $userEmail = $obj['userEmail'];
			$noteShortDesc = ''; if (!empty($userEmail)) $noteShortDesc = __('Created with email',"zero-bs-crm").': '.$userEmail;

			if ($userID > 0)  $noteShortDesc .= ' (#'.$userID.')';

			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

				#} Add log
				$newLogID = zeroBS_addUpdateContactLog($zbsNoteAgainstPostID,-1,-1,array(
					'type' => 'Client Portal User Created',
					'shortdesc' => $noteShortDesc,
					'longdesc' => ''
				));

			}

		}

	}


	#} creates customer client portal user (if setting)
	function zeroBSCRM_IA_NewCustomerClientPortal($obj=array()){

		# if setting
		$autoFireThis = zeroBSCRM_getSetting('portalusers');

		if ($autoFireThis > 0){

			#} Retrieve necessary info:	
			$userID = -1; if (is_array($obj) && isset($obj['id']) && $obj['id'] > 0) $userID = (int)$obj['id'];

			// yup
			$okayToFire = true;


			// Specific status mode ==================================

			#} If using "specific statuses only"
			$statusList = zeroBSCRM_getSetting('portalusers_status');
			if (!is_array($statusList) && (empty($statusList) || $statusList == 'all')){

				// nothing to do

			} else {

				if (is_array($statusList)){

					// generate a list of "Okay" statuses that this'll check later on...
					$zbsStatusStr = zeroBSCRM_getCustomerStatuses();
		            $zbsStatuses = explode(',', $zbsStatusStr);
		            $okayStatuses = array();

		            // cycle through settings + copy "full str" rather than "full_str" that it'll be saved as
			        foreach ($zbsStatuses as $statusStr){

			              // permify
			              $statusKey = strtolower(str_replace(' ','_',str_replace(':','_',$statusStr)));

			              // present?
			              if (in_array($statusKey, $statusList)) $okayStatuses[] = $statusStr;

			        }

			        // is user's status in one of these?
			        $customerStatus = ''; if (isset($obj['customerMeta']) && is_array($obj['customerMeta']) && isset($obj['customerMeta']['status'])) $customerStatus = $obj['customerMeta']['status'];

			        	// if no status, try fill from (whatever was added) to db
			        	if (empty($customerStatus)){

			        		$cMeta = zeroBS_getCustomerMeta($userID);

			        		if (is_array($cMeta) && isset($cMeta['status'])) $customerStatus = $cMeta['status'];

			        	}

			        // check status
			       	if (!empty($customerStatus) && in_array($customerStatus,$okayStatuses)) 
			       		$okayToFire = true;
			       	else
			       		$okayToFire = false; // customer status empty or not in approved list :)

				} else {

					// non-standard val for status list, override it to all
					global $zbs;
					$zbs->settings->update('portalusers_status','all');

					// and let it fire..
				}

			}

			// / Specific status mode ==================================

			if ($okayToFire){

				// this'll check itself if already exists, so no harm in letting it (potentially) multifire
				if ($userID > 0) zeroBSCRM_createClientPortalUserFromRecord($userID); 

			}

		}

	}

	#} Compiles any segments which this contact fits in
	// works for new contacts + contact edits
	function zeroBSCRM_IA_ContactSegmentCompiler($obj=array()){

		# if setting
		$autoCompileSegments = 1;

		if ($autoCompileSegments > 0){

			#} Retrieve necessary info:
			$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['id'])) $zbsNoteAgainstPostID = (int)$obj['id'];
			$contactWasInSegments = array(); if (is_array($obj) && isset($obj['prevSegments']) && is_array($obj['prevSegments'])) $contactWasInSegments = $obj['prevSegments'];

			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID)){

			global $zbs;
					$zbs->DAL->segments->compileSegmentsAffectedByContact($zbsNoteAgainstPostID,$contactWasInSegments);
		}
	}
}

	/*
	* Recompiles any segments affected by quote change
	*
	* Fires on:
	* quote.new
	* quote.update
	*
	*/
	function zeroBSCRM_IA_quoteSegmentCompiler( $obj=array() ){

	global $zbs;

	// if quote passed:
	if ( is_array( $obj ) && isset( $obj['data'] ) ) {
		$zbs->DAL->segments->compileSegmentsAffectedByQuote( $obj['data'] ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}
}

	/*
	* Recompiles any segments affected by invoice change
	*
	* Fires on:
	* invoice.new
	* invoice.update
	*
	*/
	function zeroBSCRM_IA_invoiceSegmentCompiler( $obj=array() ){

	global $zbs;

	// if quote passed:
	if ( is_array( $obj ) && isset( $obj['data'] ) ) {
		$zbs->DAL->segments->compileSegmentsAffectedByInvoice( $obj['data'] ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}
}

	/*
	* Recompiles any segments affected by transaction change
	*
	* Fires on:
	* transaction.new
	* transaction.update
	*
	*/
	function zeroBSCRM_IA_transactionSegmentCompiler( $obj=array() ){

	global $zbs;

	// if quote passed:
	if ( is_array( $obj ) && isset( $obj['data'] ) ) {
		$zbs->DAL->segments->compileSegmentsAffectedByTransaction( $obj['data'] ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	}
}

	#} when customer status changes, portal access can be revoked/added based on status (if setting)
	function zeroBSCRM_IA_CustomerStatusChangePortalAndLog($obj=array()){

		# if setting
		$autoFireThis = zeroBSCRM_getSetting('portalusers');

		if ($autoFireThis > 0){

			#} Retrieve necessary info:	
			$userID = -1; if (is_array($obj) && isset($obj['id']) && $obj['id'] > 0) $userID = (int)$obj['id'];


			#} If using "specific statuses only"
			$statusList = zeroBSCRM_getSetting('portalusers_status');
			if (!is_array($statusList) && (empty($statusList) || $statusList == 'all')){

				// nothing to do - all statuses allowed

			} else {

				if (is_array($statusList)){

					// generate a list of "Okay" statuses that this'll check later on...
					$zbsStatusStr = zeroBSCRM_getCustomerStatuses();
		            $zbsStatuses = explode(',', $zbsStatusStr);
		            $okayStatuses = array();

		            // cycle through settings + copy "full str" rather than "full_str" that it'll be saved as
			        foreach ($zbsStatuses as $statusStr){

			              // permify
			              $statusKey = strtolower(str_replace(' ','_',str_replace(':','_',$statusStr)));

			              // present?
			              if (in_array($statusKey, $statusList)) $okayStatuses[] = $statusStr;

			        }

			        // is user's status in one of these?
			        $customerStatus = ''; if (isset($obj['customerMeta']) && is_array($obj['customerMeta']) && isset($obj['customerMeta']['status'])) $customerStatus = $obj['customerMeta']['status'];

			        	// if no status, try fill from (whatever was added) to db
			        	if (empty($customerStatus)){

			        		$cMeta = zeroBS_getCustomerMeta($userID);

			        		if (is_array($cMeta) && isset($cMeta['status'])) $customerStatus = $cMeta['status'];

			        	}

			        // check status
			       	if (!empty($customerStatus) && in_array($customerStatus,$okayStatuses)){
			       	
			       		// NEEDS account access

			       			// already got?
			       			$portalID = zeroBSCRM_getClientPortalUserID($userID);

			       			if (!empty($portalID) && $portalID > 0){
			       				
			       				$isDisabled = zeroBSCRM_isCustomerPortalDisabled($userID);

			       				// if disabled
			       				if ($isDisabled){


				       				// already got acc, make sure enabled
				       				zeroBSCRM_customerPortalDisableEnable($userID,'enable');

						       			$noteShortDesc = __('Access enabled (by change of status to',"zero-bs-crm").' "'.$customerStatus.'"';

						       			#} Add log
										$newLogID = zeroBS_addUpdateContactLog($userID,-1,-1,array(
											'type' => 'Client Portal Access Changed',
											'shortdesc' => $noteShortDesc,
											'longdesc' => ''
										));

								}

			       			} else {

			       				// make acc
								if ($userID > 0) {

									zeroBSCRM_createClientPortalUserFromRecord($userID); 

					       			$noteShortDesc = __('Access created (by change of status to',"zero-bs-crm").' "'.$customerStatus.'"';

					       			#} Add log
									$newLogID = zeroBS_addUpdateContactLog($userID,-1,-1,array(
										'type' => 'Client Portal Access Changed',
										'shortdesc' => $noteShortDesc,
										'longdesc' => ''
									));

								}

							}

			       	} else {

			       		// SHOULD Not have account

			       			// already got?
			       			$portalID = zeroBSCRM_getClientPortalUserID($userID);

			       			if (!empty($portalID) && $portalID > 0){
			       				
			       				$isDisabled = zeroBSCRM_isCustomerPortalDisabled($userID);

			       				// if not already disabled
			       				if (!$isDisabled){

					       			// disable if found
					       			zeroBSCRM_customerPortalDisableEnable($userID,'disable');

					       			$noteShortDesc = __('Access disabled (by change of status to',"zero-bs-crm").' "'.$customerStatus.'"';

					       			#} Add log
									$newLogID = zeroBS_addUpdateContactLog($userID,-1,-1,array(
										'type' => 'Client Portal Access Changed',
										'shortdesc' => $noteShortDesc,
										'longdesc' => ''
									));

								}

			       			}



			

			       	}

				} else {

					// non-standard val for status list, override it to all
					global $zbs;
					$zbs->settings->update('portalusers_status','all');

					// and... nothing to do - all statuses allowed
				}

			}

			// / Specific status mode ==================================

		} // / if autofire on :)

	}


	#} Adds a "changed" log when customer status change (if setting)
	function zeroBSCRM_IA_CustomerStatusChangeAutoLog($obj=array()){


		# if setting
		$autoLogThis = zeroBSCRM_getSetting('autolog_customer_statuschange');

		if ($autoLogThis > 0){

			#} Retrieve necessary info:
			$zbsNoteAgainstPostID = -1; if (is_array($obj) && isset($obj['againstid']) && $obj['againstid'] > 0) $zbsNoteAgainstPostID = (int)$obj['againstid'];
			#TRANSITIONTOMETANO		

			// I nicely pass these...
			$from = ''; if (is_array($obj) && isset($obj['from']) && !empty($obj['from'])) $from = $obj['from'];
			$to = ''; if (is_array($obj) && isset($obj['to']) && !empty($obj['to'])) $to = $obj['to'];

			if (isset($zbsNoteAgainstPostID) && !empty($zbsNoteAgainstPostID) && isset($to) && !empty($to)){

				$shortDesc = ''; 
				if (!empty($from)) 
					$shortDesc = __('From',"zero-bs-crm").' "'.$from.'" '.__('to',"zero-bs-crm").' "'.$to.'"';
				else
					$shortDesc = __('To',"zero-bs-crm").' "'.$to.'"';

				#} Add log
				$newLogID = zeroBS_addUpdateContactLog($zbsNoteAgainstPostID,-1,-1,array(
					'type' => 'Status Change',
					'shortdesc' => $shortDesc,
					'longdesc' => ''
				));

			}

		}

	}


/* ======================================================
  / Internal Automator Recipe Functions
   ====================================================== */



/* ======================================================
  Internal Automator Recipe Functions - WP HOOK tieins... just middlemen here really
   ====================================================== */

	#} Fires the hook & passes in the obj, for those who still want to use wp_hook's rather than IA Automator

   	#} Fires on 'customer.new' IA 
	function zeroBSCRM_IA_NewCustomerWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) {

			do_action( 'jpcrm_after_contact_insert', $obj['id'] );

			// legacy, use `jpcrm_after_contact_insert` from 5.3+
			do_action( 'zbs_new_customer', $obj['id'] );

		}

	}

/**
 * Fires on 'contact.update', 'contact.email.update', and 'contact.status.update IA.
 *
 * @param array $obj An array holding contact object data.
 */
function zeroBSCRM_IA_EditCustomerWPHook( $obj = array() ) {

	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {

		do_action( 'jpcrm_after_contact_update', $obj['id'] );

		// legacy, use `jpcrm_after_contact_update` from 5.3+
		do_action( 'zbs_edit_customer', $obj['id'] );

	}
}

	#} Fires on 'customer.vitals.edit' IA.
	function zeroBSCRM_IA_EditCustomerVitalsWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_edit_customer_vitals', $obj['id']);

	}

	/**
	 * Fires on 'contact.email.update' IA. Now legacy, redirecting to zeroBSCRM_IA_EditCustomerWPHook
	 *
	 * @param array $obj An array holding contact object data.
	 */
	function zeroBSCRM_IA_EditCustomerEmailWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_edit_customer_email', $obj['id']);

	}

/**
 * Fires on 'contact.delete' IA.
 *
 * @param array $obj An array holding contact object data.
 */
function zeroBSCRM_IA_DeleteCustomerWPHook( $obj = array() ) {

	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {
		// Legacy:
		do_action( 'zbs_delete_customer', $obj['id'] );
	}
}

	/**
	 * Fires on 'contact.before.delete' IA.
	 *
	 * @param array $obj An array holding contact object data.
	 */
	function zeroBSCRM_IA_BeforeDeleteCustomerWPHook($obj=array()){
	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {
		do_action( 'jpcrm_before_delete_contact', $obj );
	}
}

/**
 * Fires on 'company.new' IA.
 *
 * @param array $obj An array holding company object data.
 */
function zeroBSCRM_IA_NewCompanyWPHook( $obj = array() ) {

	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {

		// Legacy:
		do_action( 'zbs_new_company', $obj['id'] );

	}
}

/**
 * Fires on 'company.delete' IA.
 *
 * @param array $obj An array holding company object data.
 */
function zeroBSCRM_IA_DeleteCompanyWPHook( $obj = array() ) {

	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {

		// Legacy:
		do_action( 'zbs_delete_company', $obj['id'] );
	}
}
   	#} Fires on 'quote.new' IA 
	function zeroBSCRM_IA_NewQuoteWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_new_quote', $obj['id']);

	}
   	#} Fires on 'quote.accepted' IA 
	function zeroBSCRM_IA_AcceptedQuoteWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('jpcrm_quote_accepted', $obj['id']);

	}
   	#} Fires on 'quote.delete' IA 
	function zeroBSCRM_IA_DeleteQuoteWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_delete_quote', $obj['id']);

	}

/**
 * Fires on 'invoice.new' IA.
 *
 * @param array $obj An array holding invoice object data.
 */
function zeroBSCRM_IA_NewInvoiceWPHook( $obj = array() ) {

	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {
		do_action( 'zbs_new_invoice', $obj['id'] );
	}
}

/**
 * Fires on 'invoice.update' and 'invoice.status.update' IA.
 *
 * @param array $obj An array holding invoice object data.
 */
function zeroBSCRM_IA_EditInvoiceWPHook( $obj = array() ) {
	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {
		do_action( 'zbs_new_invoice', $obj['id'] );
	}
}

/**
 * Fires on 'invoice.delete' IA.
 *
 * @param array $obj An array holding invoice object data.
 */
function zeroBSCRM_IA_DeleteInvoiceWPHook( $obj = array() ) {

	if ( is_array( $obj ) && isset( $obj['id'] ) && ! empty( $obj['id'] ) ) {
		do_action( 'zbs_delete_invoice', $obj['id'] );
	}
}

   	#} Fires on 'transaction.new' IA 
	function zeroBSCRM_IA_NewTransactionWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_new_transaction', $obj['id']);

	}
   	#} Fires on 'transaction.delete' IA 
	function zeroBSCRM_IA_DeleteTransactionWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_delete_transaction', $obj['id']);

	}
   	#} Fires on 'event.new' IA 
	function zeroBSCRM_IA_NewEventWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_new_event', $obj['id']);

	}
   	#} Fires on 'event.update' IA 
	function zeroBSCRM_IA_UpdateEventWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_update_event', $obj['id']);

	}
   	#} Fires on 'event.delete' IA 
	function zeroBSCRM_IA_DeleteEventWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_delete_event', $obj['id']);

	}
   	#} Fires on 'clientwpuser.new' IA 
	function zeroBSCRM_IA_NewClientPortalUserHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_new_client_portal_user', $obj['id']);

	}
   	#} Fires on 'form.delete' IA 
	function zeroBSCRM_IA_DeleteFormWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_delete_form', $obj['id']);

	}
   	#} Fires on 'segment.delete' IA 
	function zeroBSCRM_IA_DeleteSegmentWPHook($obj=array()){

		if (is_array($obj) && isset($obj['id']) && !empty($obj['id'])) do_action('zbs_delete_segment', $obj['id']);

	}



/* ======================================================
  / Internal Automator Recipe Functions - WP HOOK tieins
   ====================================================== */
