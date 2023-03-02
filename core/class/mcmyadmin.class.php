<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class mcmyadmin extends eqLogic {
  /*     * *************************Attributs****************************** */

  /*
  * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
  * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
  public static $_widgetPossibility = array();
  */

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration du plugin
  * Exemple : "param1" & "param2" seront cryptés mais pas "param3"
  public static $_encryptConfigKey = array('param1', 'param2');
  */

  /*     * ***********************Methode static*************************** */

  /*
  * Fonction exécutée automatiquement toutes les minutes par Jeedom
  */
  public static function cron() {
    foreach (self::byType('mcmyadmin', true) as $mcmyadmin) { //parcours tous les équipements actifs du plugin vdm
      $cmd = $mcmyadmin->getCmd(null, 'refresh'); //retourne la commande "refresh" si elle existe
      if (!is_object($cmd)) { //Si la commande n'existe pas
        continue; //continue la boucle
      }
      $cmd->execCmd(); //la commande existe on la lance
    }
  }
  /*
  * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
  public static function cron5() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
  public static function cron10() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
  public static function cron15() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
  public static function cron30() {}
  */

  /*
  * Fonction exécutée automatiquement toutes les heures par Jeedom
  public static function cronHourly() {}
  */

  /*
  * Fonction exécutée automatiquement tous les jours par Jeedom
  public static function cronDaily() {}
  */

  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  //  $cmd = $this->getCmd(null, 'refresh'); //On recherche la commande refresh de l’équipement
  //  if (is_object($cmd)) { //elle existe et on lance la commande
  //    $cmd->execCmd();
  //  }
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    $status = $this->getCmd(null, 'status');
    if (!is_object($info)) {
      $status = new mcmyadminCmd();
      $status->setName(__('Status', __FILE__));
    }
    $status->setEqLogic_id($this->getId());
    $status->setLogicalId('status');
    $status->setType('info');
    $status->setSubType('string');
    $status->save();
  
    $refresh = $this->getCmd(null, 'refresh');
    if (!is_object($refresh)) {
      $refresh = new mcmyadminCmd();
      $refresh->setName(__('Rafraichir', __FILE__));
    }
    $refresh->setEqLogic_id($this->getId());
    $refresh->setLogicalId('refresh');
    $refresh->setType('action');
    $refresh->setSubType('other');
    $refresh->save();
  }

  // Fonction exécutée automatiquement avant la suppression de l'équipement
  public function preRemove() {
  }

  // Fonction exécutée automatiquement après la suppression de l'équipement
  public function postRemove() {
  }

  /*
  * Permet de crypter/décrypter automatiquement des champs de configuration des équipements
  * Exemple avec le champ "Mot de passe" (password)
  */
  public function decrypt() {
    $this->setConfiguration('password', utils::decrypt($this->getConfiguration('password')));
  }
  public function encrypt() {
    $this->setConfiguration('password', utils::encrypt($this->getConfiguration('password')));
  }

  /*
  * Permet de modifier l'affichage du widget (également utilisable par les commandes)
  public function toHtml($_version = 'dashboard') {}
  */

  /*
  * Permet de déclencher une action avant modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function preConfig_param3( $value ) {
    // do some checks or modify on $value
    return $value;
  }
  */

  /*
  * Permet de déclencher une action après modification d'une variable de configuration du plugin
  * Exemple avec la variable "param3"
  public static function postConfig_param3($value) {
    // no return value
  }
  */

  /*     * **********************Getteur Setteur*************************** */



  public function getstgatus() {
    $adresse = $this->getConfiguration("adresse", "localhost");
    $port = $this->getConfiguration("port", "8080");
    $utilisateur = $this->getConfiguration("utilisateur", "admin");
    $password = $this->getConfiguration("password", "admin");
    $url = "http://" . $adresse . ":" . $port . "/data.json?req=login&Username=" . $utilisateur . "&Password=" . $password . "&Token=";
    return $url;
    /*
    $this->setConfiguration("sessionid","mon_type");
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Content-Type: application/json\r\n" .
                  "Accept: application/json\r\n"
      )
    );
    $context = stream_context_create($opts);
    $url = "http://" . $adresse . ":" . $port . "/data.json?req=login&Username=" . $utilisateur . "&Password=" . $password . "&Token=";
    $data = file_get_contents($url, false, $context);
    @$dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($data);
    libxml_use_internal_errors(false);
    $xpath = new DOMXPath($dom);
    $divs = $xpath->query('//article[@class="art-panel col-xs-12"]//div[@class="panel-content"]//p//a');
    return $divs[0]->nodeValue ;
    */
  }


	public function getFullConfig () {
		return $this->request(array('req' => 'getfullconfig'));
	}
	public function getBackupStatus () {
		return $this->request(array('req' => 'getbackupstatus'));
	}
	public function getDeleteStatus () {
		return $this->request(array('req' => 'getdeletestatus'));
	}
	public function getRestoreStatus () {
		return $this->request(array('req' => 'getrestorestatus'));
	}
	public function getUpdateStatus () {
		return $this->request(array('req' => 'getupdatestatus'));
	}
	public function getStatus () {
		return $this->request(array('req' => 'getstatus'));
	}
	public function getExtensions () {
		return $this->request(array('req' => 'getextensions'));
	}
	public function getPlugins () {
		return $this->request(array('req' => 'getplugins'));
	}
	public function getProviderInfo () {
		return $this->request(array('req' => 'getproviderinfo'));
	}
	public function getServerInfo () {
		return $this->request(array('req' => 'getserverinfo'));
	}
	public function getTip () {
		return $this->request(array('req' => 'gettip'));
	}
	public function getVersions () {
		return $this->request(array('req' => 'getversions'));
	}
	public function getBackupList () {
		return $this->request(array('req' => 'getbackuplist'));
	}
  public function getPlayers() {
    $request = $this->getStatus();
    $playerlist = array();
    if(isset($request->userinfo)) {
      foreach($request->userinfo as $user => $values) {
          $playerlist[] = $user;
        }
    }
    return $playerlist;
	}

	public function getConfig ($key) {
		if($key) {
      return $this->request(array('req' => 'getconfig' , 'key' => $key));
		}
	}
	public function getChat ($since) {
		if($since) {
      return $this->request(array('req' => 'getchat' , 'since' => $since));
		}
	}



	public function setConfig ($key, $value) {
		if($key && $value) {
      return $this->request(array('req' => 'setconfig' , 'key' => $key, 'value' => $value));
		}
	}
	public function sendChat ($message) {
		if($message) {
      return $this->request(array('req' => 'sendchat' , 'message' => $message));
		}
	}



  public function sleepServer () {
    return $this->request(array('req' => 'sleepserver'));
  }
  public function startServer () {
    return $this->request(array('req' => 'startserver'));
  }
  public function stopServer () {
    return $this->request(array('req' => 'stopserver'));
  }
	public function killServer () {
		return $this->request(array('req' => 'killserver'));
	}
	public function reload () {
		return $this->request(array('req' => 'reload'));
	}
	public function restartServer () {
		return $this->request(array('req' => 'restartserver'));
	}
  public function updateMC () {
    return $this->request(array('req' => 'updatemc'));
  }
  public function updateMCMA () {
    return $this->request(array('req' => 'updatemcma'));
  }


  public function doDiagnostics () {
		return $this->request(array('req' => 'dodiagnostics'));
	}
	public function logout () {
		return $this->request(array('req' => 'logout'));
	}
	public function getTokenAuth ($username) {
		if($username) {
      return $this->request(array('req' => 'gettokenauth' , 'username' => $username));
		}
	}
	public function login($user = 'admin',$pass = '',$host = 'localhost',$port = '8080') {
		if(!empty($user) && !empty($pass) && !empty($host) && !empty($port)) {
			$request = $this->request(array('req'=>'login', 'Username'=>$user, 'Password'=>$pass));
            if (isset($request->MCMASESSIONID)) {
                $this->session_id = $request->MCMASESSIONID;
            }
			if($request->success == 1){
				$this->logged_in = true;
			} else {
				throw new Exception('Incorrect config details');
			}
		} else {
			throw new Exception('Not enough Paramters');
		}
	}


  private function request($args = array()) {
		if(empty($this->config['host']) || empty($this->config['port'])) {
			throw new Exception('No host or port has been given');
		}
    if (isset($this->session_id)) {
      $args['MCMASESSIONID'] = $this->session_id;
    } else {
        $args['Token'] = '';
    }
    $param = '';
		if(!empty($args)) {
			$param = http_build_query($args);
		}
    $protocol = 'http';
    $protocol = 'https';
    $url = $protocol . '://' . $this->config['host'] . ':' . $this->config['port'] . '/data.json?' . $param;
		$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER , array('Content-type: application/json','Accept: application/json'));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION , 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Firefox/mozilla McMyAdminClass');
    curl_setopt($ch, CURLOPT_HEADER , 0);
    curl_setopt($ch, CURLOPT_COOKIEJAR , 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE , 'cookie.txt');
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);
    $data = curl_exec($ch);
		if(empty($data)) {
			throw new Exception('No content');
		}
		curl_close($ch);
		$data = json_decode($data);
	  return $data;
	}
}

class mcmyadminCmd extends cmd {
  /*     * *************************Attributs****************************** */

  /*
  public static $_widgetPossibility = array();
  */

  /*     * ***********************Methode static*************************** */


  /*     * *********************Methode d'instance************************* */

  /*
  * Permet d'empêcher la suppression des commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
  public function dontRemoveCmd() {
    return true;
  }
  */

  // Exécution d'une commande
  public function execute($_options = array()) {
    $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
    switch ($this->getLogicalId()) { //vérifie le logicalid de la commande
      case 'refresh': // LogicalId de la commande rafraîchir que l’on a créé dans la méthode Postsave de la classe vdm .
      $info = $eqlogic->getstatus(); //On lance la fonction getstatus() pour récupérer une vdm et on la stocke dans la variable $info
      $eqlogic->checkAndUpdateCmd('status', $info); //on met à jour la commande avec le LogicalId "story"  de l'eqlogic
      break;

    }
  }


  /*     * **********************Getteur Setteur*************************** */
}