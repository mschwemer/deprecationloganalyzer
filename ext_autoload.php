<?php

$extensionPath = t3lib_extMgm::extPath('deprecationloganalyzer');
$extensionClassesPath = t3lib_extMgm::extPath('deprecationloganalyzer') . 'Classes/';
return array(
	'tx_deprecationloganalyzer_analyzer' => $extensionClassesPath . 'Analyzer.php',
	'tx_deprecationloganalyzer_report_log' => $extensionClassesPath . 'Report/Log.php',
	'tx_deprecationloganalyzer_report_logreport' => $extensionClassesPath . 'Report/LogReport.php',
);
?>