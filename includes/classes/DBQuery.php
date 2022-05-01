<?php
include_once('DBAccess.php');

class DBQuery extends DatabaseAccess
{
	public function getRecordCount($trace = 0, $tblName, $whereClause = '', $selectClause = '')
	{
		$whereStr = '';

		if (@is_array($whereClause)) {
			$whereStr = '1';
			foreach ($whereClause as $key => $value) {
				$whereStr .= " AND $key = '" . trim($value) . "'";
			}

		} elseif ($whereClause != '') {
			$whereStr = $whereClause;
		}

		if (!$selectClause) $selectClause = '*';

		$query = "SELECT COUNT($selectClause) AS rcdCount FROM $tblName";

		if ($whereStr) $query .= " WHERE $whereStr";

		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit;
		}
		else {
			$objDbAccess = new DatabaseAccess();
			$resultArray = $objDbAccess->selectRecordCount($query);
			return $resultArray['rcdCount'];
		}
	}

	public function getRecord($trace = 0, $fields, $tblName, $whereClause = '', $offset = '', $rowCount = '', $orderByCol = '', $sortOrder = '', $groupBy = '')
	{
		$whereStr = '';

		if (@is_array($fields)) {
			$fieldStr = implode(",", $fields);
		} elseif ($fields != '*') {
			$fieldStr = $fields;
		} else {
			$fieldStr = " * ";
		}

		if (@is_array($whereClause)) {
			$whereStr = '1';
			foreach ($whereClause as $key => $value) {
				$whereStr .= " AND $key = '" . trim($value) . "'";
			}
		} elseif ($whereClause != '') {
			$whereStr = $whereClause;
		}

		$query = "SELECT $fieldStr FROM $tblName";

		if ($whereStr) $query .= " WHERE $whereStr";

		//if ($groupBy) $query .= " GROUP BY";

		if ($orderByCol) $query .= " ORDER BY $orderByCol";

		if ($sortOrder) $query .= " $sortOrder";

		if ($rowCount) $query .= " LIMIT $offset,$rowCount";

		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit;
		} else {
			$objDbAccess = new DatabaseAccess();
			$resultArray = $objDbAccess->selectRecord($query);
			unset($objDbAccess);
			return $resultArray;
		}
	}

	public function addRecord($trace=0,$setClause,$tblName)
	{
		$setStr = '';

		if (@is_array($setClause)) {
			$i = 1;
			$fieldCnt = sizeof($setClause);

			foreach ($setClause as $key => $value) {
				$setStr .= " $key = '" . addslashes(trim($value)) . "'";
				if($i < $fieldCnt) $setStr .= ", ";
				$i++;
			}
		} else {
			$setStr = $setClause;
		}

		$query = "INSERT INTO $tblName SET $setStr";
		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit;
		} else {
			$objDbAccess = new DatabaseAccess();
			$insertId = $objDbAccess->executeQuery($query,'Add');
			unset($objDbAccess);
			return $insertId;
		}
	}

	public function updateRecord($trace=0,$dataClause,$tblName,$whereClause)
	{
		$whereStr = '';
		$setClause = '';

		if (is_array($dataClause)) {
			$i = 1;
			$fieldCnt = sizeof($dataClause);

			foreach ($dataClause as $key => $value) {
				$setClause .= " $key = '" . addslashes(trim($value)) . "'";
				if($i < $fieldCnt) $setClause .= ", ";
				$i++;
			}
		} else {
			$setClause = $dataClause;
		}

		if (@is_array($whereClause)) {
			$whereStr = '1';
			foreach ($whereClause as $key => $value) {
				$whereStr .= " AND $key = '" . trim($value) . "'";
			}
		} elseif ($whereClause != '') {
			$whereStr = $whereClause;
		}

		$query = "UPDATE $tblName SET $setClause";

		if ($whereStr) $query .= " WHERE $whereStr";

		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit;
		} else {
			$objDbAccess = new DatabaseAccess();
			$affectedRows = $objDbAccess->executeQuery($query,'Mod');
			unset($objDbAccess);
			return $affectedRows;
		}
	}

	public function deleteRecord($trace=0,$tblName,$whereClause)
	{
		$whereStr = '';

		if (@is_array($whereClause)) {
			$whereStr = '1';

			foreach ($whereClause as $key => $value) {
				$whereStr .= " AND $key = '" . trim($value) . "'";
			}
		} elseif ($whereClause != '') {
			$whereStr = $whereClause;
		}

		$query = "UPDATE $tblName SET isDeleted = 'Y'";

		if ($whereStr) $query .= " WHERE $whereStr";

		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit;
		} else {
			$objDbAccess = new DatabaseAccess();
			$affectedRows = $objDbAccess->executeQuery($query,'Mod');
			unset($objDbAccess);
			return $affectedRows;
		}
	}

	public function dropRecord($trace=0,$tblName,$whereClause)
	{
		$whereStr = '';

		if (@is_array($whereClause)) {
			$whereStr = '1';
			foreach ($whereClause as $key => $value) {
				$whereStr .= " AND $key = '".trim($value)."'";
			}
		} elseif ($whereClause != '') {
			$whereStr = $whereClause;
		}

		$query = "DELETE FROM $tblName";

		if ($whereStr) $query .= " WHERE $whereStr";

		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit;
		} else {
			$objDbAccess = new DatabaseAccess();
			$affectedRows = $objDbAccess->executeQuery($query,'Del');
			unset($objDbAccess);
			return $affectedRows;
		}
	}
	
	public function executeSQL($trace=0,$query)
	{
		if ($trace) {
			echo "<br>" . $query . "<br>";
			exit; 
		} else {
			$objDbAccess = new DatabaseAccess();
			$insertId = $objDbAccess->executeQuery($query, 'Add');
			unset($objDbAccess);
			return $insertId;
		}
	}

	public function escapeSpecialCharForSql($value)
	{
		$objDbAccess = new DatabaseAccess();
		$value = mysqli_real_escape_string($objDbAccess->dbConn, $value);
		unset($objDbAccess);		
		return $value;
	}

	public function trackActivity($trace, $activityCode, $activityInfo, $ipAddress) 
	{		
		$this->addRecord($trace, array('activityCode' => $activityCode, 'userCode' => $_SESSION['userDetails']['userCode'], 'activityDetail' => $activityInfo, 'ipAddress' => $ipAddress), 'tbl_activities');
	}

	public function getEmailFormat($trace = 0, $appCode, $subjectAbbr)
	{		
		$appInfoArr = $this->getRecord(0, '*', 'tbl_apps', "appCode = '$appCode'");
		$emailGenerateFrom = $appInfoArr[0]['emailGenerateFrom'];

		$whereCls = "subjectAbbr = '$subjectAbbr'";
		if ($emailGenerateFrom == 'O') $whereCls .= "AND appCode_FK = '$appCode'";

		$emailFormatInfoArr = $this->getRecord($trace, '*', 'tbl_email_format', $whereCls);
		return $emailFormatInfoArr;
	}


	public function customsqlquery($query)
	{
        $objDbAccess = new DatabaseAccess();
		$resultArray = $objDbAccess->selectRecord($query);
		unset($objDbAccess);
		return $resultArray;
	}
}