<?php
/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

class Language extends Database
{	
	private $lang;
	private $dictionary = array();
	
	/**
	 * Festlegen der Sprache im Konstruktor
	 * @param string $language ISO-639-1
	 * @return void
	 */
	public function __construct($language = '')
	{		
		global $config;
		if ($language == "") {
			$this->lang = $this->clean($config["language"]);
		} else {
			$this->lang = $this->clean($language);
		}		
		
		if ($this->lang != "de" && !file_exists($config["system_path"] . "/sqlite/language.db")) {
			$install = new Install();
			$install->createLanguageDB();
		}
		
		parent::__construct($config["system_path"] . "/sqlite/language.db");
			

		/***********************
		 * Dictionary erstellen 
		***********************/
				
		// Wenn noch kein Dictionary existiert, muss es gefüllt werden
		if (count($this->dictionary) == 0) {
			// Wenn die Sprache von 'de' abweicht und die Sprache existiert, dann füllen wir das Wörterbuch
			if ($this->lang != "de" && $this->languageExists($this->lang)) {
				// Auslesen aller Spracheinträge
				$result = $this->result("SELECT de,{$this->lang} FROM language");
				
				// Durchlaufen aller Ergebnisse und einfügen der Wörter ins Wörterbuch
				while ($row = $this->fetchAssoc($result)) {
					if ($row["de"] != "") {
						$this->dictionary[mb_strtolower($row["de"],'UTF-8')] = trim($row[$this->lang]);
					}
				}
			} elseif (!$this->languageExists($this->lang)) {
				//throw new Exception("LanguageException: Language '{$this->lang}' does not exist.");
				$this->lang = "de";
			}
		}
	}

	
	/**
	 * Auslesen aller Spracheinträge
	 * @return MySQLResult
	 */
	public function fetch()
	{
		$result = $this->result("SELECT * FROM language");
		return $result;
	}
	

	/**
	 * Übersetzt einen Text-String in die aktuelle Sprache
	 * @param string $string
	 * @return string
	 */
	public function translate($string, $lang = "")
	{		
		$lang = (trim($lang) == "") ? $this->lang : $this->clean($lang);
		$string = trim(strip_tags($string, "<b><i><u>"));
		$mbLowerString = mb_strtolower($string, 'UTF-8');
		
		// Wenn $language 'de' ist, dann braucht nichts übersetzt werden !!
		if ($lang == "de") {
			return $string;
		}
		
		// Anderenfalls im Wörterbuch nachschauen, ob es eine Übersetzung gibt !
		if (isset($this->dictionary[$mbLowerString]) && trim($this->dictionary[$mbLowerString]) != "") {			
			return $this->dictionary[$mbLowerString];
		} elseif (isset($this->dictionary[$mbLowerString]) && trim($this->dictionary[$mbLowerString]) == "") {
			// Wenn der Eintrag existiert, aber bislang nicht übersetzt wurde, gib den Text deutsch zurück
			return $string;
		} else {
			// Wenn es noch keinen Eintrag gibt, lege ihn an
			if (!empty($string)) {
				$query = $this->query("INSERT INTO language ([de]) VALUES ('{$string}')", false);
				$this->refreshDictionary();		// Dictionary neu einlesen
			}			
			return $string;
		}
	}
	
	
	/**
	 * Gibt die aktuelle Sprache zurück
	 * @return string
	 */
	public function getLang()
	{
		return $this->lang;
	}
	
	
	/**
	 * Überprüft, ob eine Sprache in der DB existiert
	 * @param string $lang
	 * @return Boolean
	 */
	public function languageExists($lang)
	{		
		$lang = $this->clean($lang);
		
		if ($lang != "de" && $lang != "") {
			$result = $this->result("SELECT * FROM sqlite_master WHERE type='table' AND tbl_name = 'language' AND sql LIKE '%[{$lang}] TEXT%'");
			
			if ($this->numRows($result) != 0) {
				return true;
			}
			return false;		
		}
		
		return true;
	}
	
	
	/**
	 * Liest das Dictionary aus der Datenbank neu ein
	 * @return void
	 */
	public function refreshDictionary()
	{
		// Leere das Dictionary
		$this->dictionary = array();
		// Der Konstruktor beläd das Dictionary neu
		$this->__construct($this->lang);
	}
	

	/**
	 * Gibt eine Liste mit ISO 639-1 Sprachkürzeln zurück
	 * @return array
	 */
	private function getLanguageCodes()
	{		
		return array(		
			"Afar" => "aa",
			"Abkhazian" => "ab",
			"Afrikaans" => "af",
			"Amharic" => "am",
			"Arabic" => "ar",
			"Assamese" => "as",
			"Aymara" => "ay",
			"Azerbaijani" => "az",
			"Bashkir" => "ba",
			"Byelorussian" => "be",
			"Bulgarian" => "bg",
			"Bihari" => "bh",
			"Bislama" => "bi",
			"Bengali" => "bn",
			"Tibetan" => "bo",
			"Breton" => "br",
			"Catalan" => "ca",
			"Corsican" => "co",
			"Czech" => "cs",
			"Welch" => "cy",
			"Danish" => "da",
			"German" => "de",
			"Bhutani" => "dz",
			"Greek" => "el",
			"English" => "en",
			"Esperanto" => "eo",
			"Spanish" => "es",
			"Estonian" => "et",
			"Basque" => "eu",
			"Persian" => "fa",
			"Finnish" => "fi",
			"Fiji" => "fj",
			"Faeroese" => "fo",
			"French" => "fr",
			"Frisian" => "fy",
			"Irish" => "ga",
			"Scots Gaelic" => "gd",
			"Galician" => "gl",
			"Guarani" => "gn",
			"Gujarati" => "gu",
			"Hausa" => "ha",
			"Hindi" => "hi",
			"Hebrew" => "he",
			"Croatian" => "hr",
			"Hungarian" => "hu",
			"Armenian" => "hy",
			"Interlingua" => "ia",
			"Indonesian" => "id",
			"Interlingue" => "ie",
			"Inupiak" => "ik",
			"former Indonesian" => "in",
			"Icelandic" => "is",
			"Italian" => "it",
			"Inuktitut (Eskimo)" => "iu",
			"former Hebrew" => "iw",
			"Japanese" => "ja",
			"former Yiddish" => "ji",
			"Javanese" => "jw",
			"Georgian" => "ka",
			"Kazakh" => "kk",
			"Greenlandic" => "kl",
			"Cambodian" => "km",
			"Kannada" => "kn",
			"Korean" => "ko",
			"Kashmiri" => "ks",
			"Kurdish" => "ku",
			"Kirghiz" => "ky",
			"Latin" => "la",
			"Lingala" => "ln",
			"Laothian" => "lo",
			"Lithuanian" => "lt",
			"Latvian, Lettish" => "lv",
			"Malagasy" => "mg",
			"Maori" => "mi",
			"Macedonian" => "mk",
			"Malayalam" => "ml",
			"Mongolian" => "mn",
			"Moldavian" => "mo",
			"Marathi" => "mr",
			"Malay" => "ms",
			"Maltese" => "mt",
			"Burmese" => "my",
			"Nauru" => "na",
			"Nepali" => "ne",
			"Dutch" => "nl",
			"Norwegian" => "no",
			"Occitan" => "oc",
			"(Afan) Oromo" => "om",
			"Oriya" => "or",
			"Punjabi" => "pa",
			"Polish" => "pl",
			"Pashto, Pushto" => "ps",
			"Portuguese" => "pt",
			"Quechua" => "qu",
			"Rhaeto-Romance" => "rm",
			"Kirundi" => "rn",
			"Romanian" => "ro",
			"Russian" => "ru",
			"Kinyarwanda" => "rw",
			"Sanskrit" => "sa",
			"Sindhi" => "sd",
			"Sangro" => "sg",
			"Serbo-Croatian" => "sh",
			"Singhalese" => "si",
			"Slovak" => "sk",
			"Slovenian" => "sl",
			"Samoan" => "sm",
			"Shona" => "sn",
			"Somali" => "so",
			"Albanian" => "sq",
			"Serbian" => "sr",
			"Siswati" => "ss",
			"Sesotho" => "st",
			"Sudanese" => "su",
			"Swedish" => "sv",
			"Swahili" => "sw",
			"Tamil" => "ta",
			"Tegulu" => "te",
			"Tajik" => "tg",
			"Thai" => "th",
			"Tigrinya" => "ti",
			"Turkmen" => "tk",
			"Tagalog" => "tl",
			"Setswana" => "tn",
			"Tonga" => "to",
			"Turkish" => "tr",
			"Tsonga" => "ts",
			"Tatar" => "tt",
			"Twi" => "tw",
			"Uigur" => "ug",
			"Ukrainian" => "uk",
			"Urdu" => "ur",
			"Uzbek" => "uz",
			"Vietnamese" => "vi",
			"Volapuk" => "vo",
			"Wolof" => "wo",
			"Xhosa" => "xh",
			"Yiddish" => "yi",
			"Yoruba" => "yo",
			"Zhuang" => "za",
			"Chinese" => "zh",
			"Zulu" => "zu"
		);
	}
}
?>