<?php

/**
* Acest fisier reprezinta partea "Controller" a aplicatiei.
* Aici, vor fi definite toate intrumentele necesare manipularii datelor de intrare/iesire.
* De asemenea, tot in acest fisier de vor defini instrumente pentru a gestiona output-ul (ex. paginare)
* Vom avea doua namespace-uri: InteractionIN si InteractionOUT.
*/

namespace InteractionIN
{
	/**
	* In acest namespace vor fi definite clasele de lucru cu datele de intrare:
	* @ validare caractere
	* @ sanitizare caractere
	* @ validare, existenta, unicitate, etc.
	*/
	require_once "repository.php";
	use Database\DB as DB;

	class Contact
	{
		/**
		* Aceasta clasa se ocupa cu preluarea si prelucrarea datelor din formularul de contact.
		* In cazul in care acestea respecta cerintele, vor si trimise spre Repository si introduse in baza de date.
		* In caz contrar, vor fi afisate mesajele de eroare corespunzatoare.
		*/

		private $contact = array();
		private static $_construct_result;

		function __construct(array $contact)
		{
			$accepted_keys = array("name","email","company","subject","message","csrf_token");
			self::$_construct_result = "accepted";
			foreach ($contact as $key => $value) {
				$value=trim($value);
				if($key=="csrf_token") continue;

				if(!in_array($key,$accepted_keys))
				{
					self::$_construct_result = "not accepted";
					break;	
				}
				else
				{
					if($value!="") $this->contact[$key]=$value;
					else $this->contact[$key]="empty";
				}
			}
		}

		function validateInput()
		{
			# Name
			$name_pattern='/^[a-zA-z]{1,}\s*[a-zA-Z]{1,}$/'; // ex. Adam Smith

			# Company
			$company_pattern='/^[a-zA-z0-9 ]{1,}$/'; // ex. Facebook Corporation

			# using Gmail eMail Pattern
			$gmail_path='[a-zA-Z0-9]+([a-zA-Z0-9]+(\.){0,1}[a-zA-Z0-9]+)*[a-zA-Z0-9]+';
			$gmail_pattern='/^'.$gmail_path.'@'.$gmail_path.'\.[a-z]{2,4}$/';
			$gmail_only_pattern='/^'.$gmail_path.'@gmail\.[a-z]{2,4}$/';
			
			# using Yahoo eMail Pattern
			$yahoo_path='[a-zA-Z]+([a-zA-Z0-9]+(_|\.){0,1}[a-zA-Z0-9]+)*[a-zA-Z0-9]+';
			$yahoo_pattern='/^'.$yahoo_path.'@'.$yahoo_path.'\.[a-z]{2,4}$/';
			$yahoo_only_pattern='/^'.$yahoo_path.'@yahoo\.[a-z]{2,4}$/';

			# Email patterns
			$all_in_email_patterns=array('yahoo' => $yahoo_pattern, 'gmail' => $gmail_pattern);

			# Title, Subject, Header
			$subject_pattern='/^[a-zA-Z0-9-\. ]{1,}$/';

			# Message
			$message_pattern='/^[a-zA-Z0-9\.?! ]{1,}$/';

			$required = array("name","email","message");
			$errors=array(1 => "Campurile marcate cu \" <b>*</b> \" sunt obligatorii !",
							2 => "Dimensiune maxima depasita pentru ",
							4 => "Valoare invalida ! Va rugam sa respectati conditiile de scriere.",
							5 => "Aceasta adresa de email este restrictionata. !",
							6 => "<font color=\"#009900\">Mesajul a fost trimis. Va multumim !</font>" );

			if(self::$_construct_result=="not accepted")	return null;
			else
			{
				/**
				* Valorile pentru Keys sunt corecte.
				* In cadrul functiei __construct() am marcat campurile goale prin "empty".
				* In variabia "required" vom avea campurile care trebuie introduse obligatoriu.
				* Urmeaza sa validam valorile introduse.
				*/

				$error=6;

				foreach ($this->contact as $key => $value) {

					if($error!=6) break;

					if($value=="empty" && in_array($key, $required))
					{
						// Camp obligatoriu necompletat.
						$error=1;
					}
					else if($value=="empty")
					{
						// Camp optional necompletat - e ok.
						$error=6;
					}
					else
					{
						switch($key)
						{
							case 'name':

								if(strlen($value)>60)
								{
									$error=2;
									$errors[3]=ucfirst($key);
								}
								else if(!preg_match($name_pattern, $value))
								{
									$error=4;
								}
								else
								{
									$error=6;
								}

								break;

							case 'email':

								$ok=0;
								foreach ($all_in_email_patterns as $type => $path) {
									$pattern=$path;
									if(preg_match($pattern, $value))
									{
										$ok=1;
										break;
									}
								}

								if(!$ok)
								{
									$error=4;
								}
								else if(strlen($value)>150)
								{
									$error=2;
									$errors[3]=ucfirst($key);
								}
								else
								{
									// Verificam daca adresa de email este restrictionata
									$check = DB::getInstance();
									if(!$check->checkBannedEmail($value))
									{
										// adresa restrictionata
										$error=5;
									}
									else
									{
										$error=6;
									}
								}

								break;

							case 'company':

								if(strlen($value)>100)
								{
									$error=2;
									$errors[3]=ucfirst($key);
								}
								else if(!preg_match($company_pattern, $value))
								{
									$error=4;
								}
								else
								{
									$error=6;
								}

								break;

							case 'subject':

								if(strlen($value)>150)
								{
									$error=2;
									$errors[3]=ucfirst($key);
								}
								else if(!preg_match($subject_pattern, $value))
								{
									$error=4;
								}
								else
								{
									$error=6;
								}
								
								break;

							case 'message':
								
								if(strlen($value)>500)
								{
									$error=2;
									$errors[3]=ucfirst($key);
								}
								else if(!preg_match($message_pattern, $value))
								{	
									$error=4;
								}
								else
								{
									$error=6;
								}

								break;
						}
					}
				}

				if($error==6)
				{
					// Totul a mers bine. Urmeaza sa trimitem datele in Baza de date.

					$insert = DB::getInstance();
					$insert->Contact($this->contact);

					return $errors[$error];
				}
				else if($error==2) return $errors[$error].$errors[3].".";
				else return $errors[$error];
			}
		}
	}
}
?>
