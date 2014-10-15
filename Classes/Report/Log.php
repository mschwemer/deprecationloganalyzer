<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Georg Ringer <typo3@ringerge.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Log report
 *
 * @package TYPO3
 * @subpackage tx_deprecationloganalyzer
 */
class Tx_Deprecationloganalyzer_Report_Log implements tx_reports_Report {

	/**
	 * @var tx_reports_Module
	 */
	protected $reportsModule;

	public function __construct($reportsModule) {
	// public function __construct(tx_reports_Module $reportsModule) {
		$this->reportsModule = $reportsModule;
	}

	/**
	 * Render main report
	 *
	 * @return string
	 */
	public function getReport() {
		$content = '';

		if (!$this->checkForActiveDeprecationLog()) {
			$content .= t3lib_div::makeInstance(
				't3lib_FlashMessage',
				$GLOBALS['LANG']->sL('LLL:EXT:deprecationloganalyzer/Resources/Private/Language/locallang.xml:log-disabled.description', TRUE),
				$GLOBALS['LANG']->sL('LLL:EXT:deprecationloganalyzer/Resources/Private/Language/locallang.xml:log-disabled.title', TRUE),
				t3lib_FlashMessage::NOTICE
			)->render();
		}

		$analyzer = t3lib_div::makeInstance('Tx_Deprecationloganalyzer_Analyzer');

		try {
			$simpleLogFileContent = $analyzer->getShortLog();

			$content .= 'Duplicates: ' . $analyzer->duplicates . '<br />Final ones: ' . count($simpleLogFileContent) . '<br /><br />';

			foreach($simpleLogFileContent as $msg) {
				$content .= $this->renderSingleLine($msg);
			}
		} catch (Exception $e) {
			$content .= t3lib_div::makeInstance(
				't3lib_FlashMessage',
				$GLOBALS['LANG']->sL('LLL:EXT:deprecationloganalyzer/Resources/Private/Language/locallang.xml:' . $e->getMessage(), TRUE),
				'',
				t3lib_FlashMessage::ERROR
			)->render();
		}
		return $content;
	}

	protected function showLogFileSize() {
		$logFileSize = $this->getLogSize();
		$logFileSizeStatus = ($logFileSize > 100000000) ? t3lib_FlashMessage::WARNING : t3lib_FlashMessage::NOTICE;

		$content = t3lib_div::makeInstance(
				't3lib_FlashMessage',
				sprintf($GLOBALS['LANG']->sL('LLL:EXT:deprecationloganalyzer/Resources/Private/Language/locallang.xml:log-size.description', TRUE), $this->convert($logFileSize)),
				$GLOBALS['LANG']->sL('LLL:EXT:deprecationloganalyzer/Resources/Private/Language/locallang.xml:log-size.title', TRUE),
				$logFileSizeStatus
			)->render();

		return $content;
	}

	public function getLogSize() {
		$logFile = $this->getLogFile();
		return filesize($logFile);
	}

	/**
	 * Render single line
	 *
	 * @param array $msg
	 * @return string
	 */
	protected function renderSingleLine(array $msg) {
//		$content = '<strong>Count: #' . $msg['count'] . '</strong>, Last call: ' . $msg['time'] .
//					'<br />
//					<pre>' . htmlspecialchars($msg['msg']) . '</pre><br />';
		$content = '<span style="display:block;font-family:courier;margin-bottom:14px;">' . htmlspecialchars($msg['msg']) . '</span>';

		return $content;
	}

	/**
	 * Check if deprecation log is enabled
	 *
	 * @return boolean
	 */
	protected function checkForActiveDeprecationLog() {
		$log = $GLOBALS['TYPO3_CONF_VARS']['SYS']['enableDeprecationLog'];

			// legacy values (no strict comparison, $log can be boolean, string or int)
		if ($log === TRUE || $log == '1') {
			$log = 'file';
		}

		if (stripos($log, 'file') !== FALSE) {
			return TRUE;
		}
		return FALSE;
	}



	/**
	 * Convert a size to readable date
	 * @param integer $size
	 * @return string
	 */
	protected function convert($size) {
		$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
		return @round($size/pow(1024, ($i = floor(log($size,1024)))), 2) . ' ' . $unit[$i];
	}
}

?>