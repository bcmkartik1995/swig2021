<?php
class DatabaseAccess
{
	private $dbHost;
	private $dbUser;
	private $dbPass;
	private $dbName;

	protected $dbConn;

    function __construct()
    {
		if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '10.1.1.5') {
			$this->dbHost = 'localhost';
			$this->dbUser = 'fusioni';
			$this->dbPass = 'F#usioni3';
			$this->dbName = 'bdm_swig';			
		} else {
			/*
			$this->dbHost = 'swigtv.cgo3jvgdqurl.us-east-1.rds.amazonaws.com';
			$this->dbUser = 'swigtv_master';
			$this->dbPass = 'Ojm84ftyv2igSKiW8Zc8';
			$this->dbName = 'swig_tv_backend'; 
			*/
			
			$this->dbHost = 'localhost';
			$this->dbUser = 'swigmedi_samgr';
			$this->dbPass = 'zN7j.Sm1RP]-';
			$this->dbName = 'swigmedi_swigappmanager'; 
		}

        // Connect to database
		$this->dbConn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);

        // Connection error handling
		if ($this->dbConn->connect_error) {
			die("Connection failed: " . $this->dbConn->connect_error);
		}         
    }

    // Close database connection
    function __destruct() 
	{
        $this->dbConn->close();
    }

    protected function selectRecordCount($query)
    {
		$resultArray = array();
		$res = @mysqli_query($this->dbConn, $query);
		$resultArray = @mysqli_fetch_array($res, MYSQLI_ASSOC);
		return $resultArray;
    }

    protected function selectRecord($query)
    {
		$res = @mysqli_query($this->dbConn, $query);
		$numOfRows = @mysqli_num_rows($res);
		$resultArray = array();

		if ($numOfRows >= 1) {
			while ($row = @mysqli_fetch_array($res, MYSQLI_ASSOC)) {
				array_push($resultArray, $row);
			}
		}

		return $resultArray;
    }

    protected function executeQuery($query, $opr = '')
    {
		$val = '';
		$result = @mysqli_query($this->dbConn, $query);
		//$result = $this->dbConn->query($query);

		switch ($opr) {
			case 'Add': $val = $this->dbConn->insert_id; 
			break;
			case 'Mod': $val = $this->dbConn->affected_rows; 
			break;
			case 'Del': $val = $this->dbConn->affected_rows; 
			break;
		}

		return $val;
    }    
}