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
 * Log report for ext:sysutils
 *
 * @package TYPO3
 * @subpackage tx_deprecationloganalyzer
 */
class Tx_Deprecationloganalyzer_Report_LogReport extends Tx_Sysutils_Report_AbstractReport implements Tx_Sysutils_Report_ReportInterface {

	/**
	 * Fills data of the Report
	 *
	 * @return void
	 */
	public function execute() {
		try {
			$analyzer = t3lib_div::makeInstance('Tx_Deprecationloganalyzer_Analyzer');
			$log = $analyzer->getShortLog();

			$this->setGroup('info');
			$this->setLabel('Deprecation Log');
			$this->setValue(sprintf('Filtered %s different messages with %s duplicates', count($log), $analyzer->duplicates));
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

}

?>