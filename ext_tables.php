<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {
		// Registering the report
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['reports']['tx_' . $_EXTKEY]['index'] = array(
		'title'       => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:report.title',
		'description' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang.xml:report.description',
		'report'      => 'tx_' . $_EXTKEY . '_report_log',
		'icon'        => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/report_icon.png'
	);
}
?>