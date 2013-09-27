<?php

/**
* Acest fisier reprezinta partea "Model" a aplicatiei.
* Aici, vor fi definite toate intrumentele necesare relationarii dintre aplicatie si "magazie".
* "Magazia" reprezinta Baza de date, fisierele de pe server, etc.
*/

namespace Database
{
	/**
	* In cadrul acestui Namespace vor fi definite toate intrumentele necesare relationarii cu baza de date
	*/

	class DB
	{
		/**
		* Aceasta clasa este construita conform patternului Singleton
		* Conectare si relationare cu baza de date
		*/

		private static $DSN="mysql:dbname=database;host=localhost";
		private static $USER="root";
		private static $PASSWORD="";
		private static $_instance = null;
		private static $dbObject = null;

		private function __construct()
		{
			try { 
				$db_conn = new \PDO(self::$DSN, self::$USER, self::$PASSWORD);
				self::$dbObject = $db_conn;
			} 
			catch (PDOException $e) {
				echo "Could not connect to database";
				exit;
			}
		}

		private function __clone()
		{
			// do nothing
		}

		public static function getInstance()
		{
			if (!(self::$_instance instanceof DB)) {
				self::$_instance = new DB();
			}
			return self::$_instance;
		}

		public function Contact(array $contact)
		{
			/**
			* Se vor introduce datele in tabelul inbox.
			*/

			$msg_id=md5(time());
			$inbox_values=array();

			$fields="msg_id,";
			$inbox_values[]=$msg_id;
			$values_holders="?,";

			$step=0;
			foreach ($contact as $key => $value) {
				$step++;
				$fields.="{$key}";
				if($value=='empty') $value="-";
				$inbox_values[]=$value;
				$values_holders.="?";
				if(count($contact)-$step>0)
				{
					$fields.=",";
					$values_holders.=",";
				}
			}

			$sql = "INSERT INTO inbox({$fields},date) VALUES({$values_holders},NOW())";

			try {
				$stmt = self::$dbObject->prepare($sql);
				if($stmt) {

					$result=$stmt->execute($inbox_values);
					if(!$result) {
						$error = $stmt->errorInfo();
						return "Query failed with message: " . $error[2];
					}
				}
			} 
			catch (PDOException $e) {
				return "A database problem has occurred: " . $e->getMessage();
			}
		}

		public function checkBannedEmail($email)
		{
			$sql = " SELECT email FROM banned_list WHERE email=:value ";

			try {
				$stmt = self::$dbObject->prepare($sql);
				if($stmt) {

					$result=$stmt->execute(array('value' => $email));
					if(!$result) {
						$error = $stmt->errorInfo();
						return "Query failed with message: " . $error[2];
					}
					else
					{
						if($stmt->rowCount()!=0) return 0;
						else return 1;
					}
				}
			} 
			catch (PDOException $e) {
				return "A database problem has occurred: " . $e->getMessage();
			}
		}
	}
}
?>
