<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['sysutils']['reports'][] = 'Tx_Deprecationloganalyzer_Report_LogReport';
?>