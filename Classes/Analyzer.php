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
class Tx_Deprecationloganalyzer_Analyzer {

	/**
	 * @var integer
	 */
	public $duplicates = 0;
	
	/**
	 * Shrink the log file
	 *
	 * @return array
	 */
	public function getShortLog() {
		$final = array();

		$logFile = $this->getLogFile();

		if (!is_file($logFile)) {
			throw new Exception('error_no-logfile-found');
		}

		$all = array();
		$all2 = array();
		$handle = fopen($logFile, 'r');
		$hashMap = array();
		$found = array();

//		echo 'first: ' . $this->convert(memory_get_usage()) . '<br />';

		if ($handle) {
			while (!feof($handle)) {
				$line = trim(fgets($handle, 4096));
				if (empty($line)) {
					continue;
				}

				$line2 = substr($line, 16);
				$line2 = $this->stripBackTrace($line2);

				$time = substr($line, 0,14);

				$h2 = md5($line2);
				if (isset($found[$h2])) {
					$found[$h2]['count']++;
					$this->duplicates++;
					continue;
				} else {
					$found[$h2] = array(
						'msg' => $line2,
						'count' => 1
					);
				}

				$time2 = strtotime($time);
				if ($time2) {
					$line2 = substr($line, 16);
//					$hash = md5($line2);
					if (!isset($hashMap[$hash])) {
						$all2[] = array(
							'msg' => $line2,
							'count' => 1,
							'time' => $time
						);
//						$hashMap[$hash] = 1;
					} else {
						$this->duplicates++;
						$all2[] = array();
					}
				} else {
					$c = count($all2);
					$all2[$c-1]['msg'] .= $line;
				}

			}
			fclose($handle);
		} else {
			throw new Exception('error_logfile-not-readable');
		}

		return $all2;
	}

	/**
	 * Try to remove backtrace to get less duplicates
	 *
	 * @param string $line
	 * @return string
	 */
	protected function stripBackTrace($line) {
		$keys = array(' - require_once#', ' - require#', ' - include#', ' - t3lib_div::callUserFunction#', ' - SC_alt_doc->processData#', ' - SC_alt_doc->main#');
		$found = FALSE;

		foreach($keys as $key) {
			if (!$found) {
				$pos = strpos($line, $key);
				if ($pos !== FALSE) {
					$found = TRUE;
					$line = substr($line, 0, $pos);
				}

			}
		}
		return $line;
	}

	/**
	 * Path to logfile
	 *
	 * @return string
	 */
	public function getLogFile() {
		$logFile = t3lib_div::getDeprecationLogFileName();
		return $logFile;
	}
}
?>