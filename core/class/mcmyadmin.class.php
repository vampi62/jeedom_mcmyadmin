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
  public function set_limit_element($cmd,$mini,$maxi){
    $element = $this->getCmd(null, $cmd);
    if (!is_object($element)) {
      return false;
    }
    if ($element->getConfiguration('maxValue', $value))
    $element->setConfiguration('maxValue', $maxi);
    $element->getConfiguration('minValue', $mini);
    $element->setEqLogic_id($this->getId());
    $element->save();
  }


  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  private function create_element($newcmd,$newname,$newtype,$newsubtype){
    $newelement = $this->getCmd(null, $newcmd);
    if (!is_object($newelement)) {
      $newelement = new mcmyadminCmd();
      $newelement->setName(__($newname, __FILE__));
      $newelement->setEqLogic_id($this->getId());
      $newelement->setLogicalId($newcmd);
      $newelement->setType($newtype);
      $newelement->setSubType($newsubtype);
      $newelement->save();
    }
  }

  public function postSave() {
    $this->create_element('refresh','Rafraichir','action','other');
    $this->create_element('sleepserver','sleep','action','other');
    $this->create_element('startserver','start','action','other');
    $this->create_element('stopserver','stop','action','other');
    $this->create_element('killserver','kill','action','other');
    $this->create_element('reload','reload','action','other');
    $this->create_element('restartserver','restart','action','other');
    $this->create_element('refresh_chat','refresh_chat','action','other');

    $this->create_element('sendchat','send','action','message');
    $this->create_element('chat','chat','info','string');

    $this->create_element('cpuusage','cpuusage','info','string');
    $this->create_element('ram','ram','info','string');
    $this->create_element('users','users','info','string');

    $this->create_element('state','state','info','string');


    $this->create_element('failed','failed','info','string');
    $this->create_element('failmsg','failmsg','info','string');
    $this->create_element('userinfo','userinfo','info','string');
    $this->create_element('time','time','info','string');
    $this->create_element('starttime','starttime','info','string');
    
    $this->create_element('backend','backend','info','string');
    $this->create_element('mc','mc','info','string');
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
  
	public function getStatusServ($url) {
		$status =  $this->request($url . "&req=getstatus");
		$version =  $this->request($url . "&req=getversions");
    return array($status,$version);
	}
	public function getChat($url,$since) {
		if($since >= 0) {
      return $this->request($url . "&req=getchat&since=" . $since);
		}
	}

	public function sendChat($url,$message) {
		if($message) {
      return $this->request($url . "&req=sendchat&message=" . $message);
		}
	}
  public function exec_command($url,$commandname) {
    return $this->request($url . "&req=" . $commandname);
  }
  
  public function login($url) {
    $result = $this->request($url . "&req=login" . "&Token=");
    if($result['status'] == 200){
      return $result['MCMASESSIONID'];
    }
    return "";
  }
  public function request($url) {
    log::add('mcmyadmin','debug',$url);
    $request_http = new com_http($url);
    $request_http->setHeader(array('Content-type: application/json','Accept: application/json'));
    $result=$request_http->exec();
    log::add('mcmyadmin','debug',$result);
    $result = json_decode($result,true);
    return $result;
  }
  protected $retrylogin = true;
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
    $adresse = $eqlogic->getConfiguration("adresse", "localhost");
    $port = $eqlogic->getConfiguration("port", "8080");
    $utilisateur = $eqlogic->getConfiguration("utilisateur", "admin");
    $password = $eqlogic->getConfiguration("password", "admin");
    $protocol = $eqlogic->getConfiguration("protocol", "http");
    $chattimestamp = $eqlogic->getConfiguration("chattimestamp", 0);
    
    $args = array();
    $args['Username'] = $utilisateur;
    $args['Password'] = $password;
    $param = http_build_query($args);
    $url = $protocol . '://' . $adresse . ':' . $port . '/data.json?' . $param;
    $MCMASESSIONID = $eqlogic->login($url); // login si pas de token
    if($MCMASESSIONID == "") {
      $eqlogic->checkAndUpdateCmd('state', "hors ligne");
      return false;
    }
    switch ($this->getLogicalId()) { //vérifie le logicalid de la commande
      default:
        $info = $eqlogic->exec_command($url . "&MCMASESSIONID=" . $MCMASESSIONID,$this->getLogicalId());
        if ($info['status'] != 200) {
          break;
        }
        sleep(5); // on attend 5 secondes pour laisser le temps au serveur de traiter la commande
      case 'refresh':
        $info = $eqlogic->getStatusServ($url . "&MCMASESSIONID=" . $MCMASESSIONID);
        if ($info[0]['status'] != 200) {
          break;
        }
        $playerlist = array();
        if(count($info[0]['userinfo']) > 0) {
          foreach($info[0]['userinfo'] as $user => $values) {
            $playerlist[] = $values['Name'] . " : " . $values['IP'] . " : " . date("Y-m-d H:i:s", substr($values['ConnectTime'],6,-2));
          }
        }
        $eqlogic->checkAndUpdateCmd('state', $info[0]['state']);
        $eqlogic->checkAndUpdateCmd('failed', $info[0]['failed']);
        $eqlogic->checkAndUpdateCmd('failmsg', $info[0]['failmsg']);
        $eqlogic->set_limit_element('users',0,$info[0]['maxusers']);
        $eqlogic->checkAndUpdateCmd('users', count($info[0]['userinfo']));
        $playerlist = implode("<br/>", $playerlist);
        $eqlogic->checkAndUpdateCmd('userinfo', $playerlist);
        $eqlogic->checkAndUpdateCmd('time', $info[0]['time']);
        $eqlogic->set_limit_element('ram',0,$info[0]['maxram']);
        $eqlogic->checkAndUpdateCmd('ram', $info[0]['ram']);
        $eqlogic->checkAndUpdateCmd('starttime', $info[0]['starttime']);
        $eqlogic->checkAndUpdateCmd('cpuusage', $info[0]['cpuusage']);
        if ($info[1]['status'] != 200 && $info[1]['status'] != 500) { // 500 = ?bug api mais retour data
          break;
        }
        $eqlogic->checkAndUpdateCmd('backend', $info[1]['backend']);
        $eqlogic->checkAndUpdateCmd('mc', $info[1]['mc']);
      case 'refresh_chat': // exec commande si refresh_chat ou refresh
        log::add('mcmyadmin','debug',$chattimestamp);
        if ($chattimestamp < 10) {
          $chattimestamp = 0;
        }
        else {
          $chattimestamp = $chattimestamp - 10;
        }
        $info = $eqlogic->getChat($url . "&MCMASESSIONID=" . $MCMASESSIONID,$chattimestamp);
        if ($info['status'] != 200) {
          break;
        }
        $chatlist = array();
        if(isset($info['chatdata'])) {
          for($j = 0; $j < count($info['chatdata']); $j++) {
            if ($info['chatdata'][$j]['isChat']) {
              $chatlist[] = $info['chatdata'][$j]['user'] . ' : ' . $info['chatdata'][$j]['message'] . ' : ' . date("Y-m-d H:i:s", substr($info['chatdata'][$j]['time'],6,-2));
            }
          }
        }
        $chatlist = implode("<br/>", $chatlist);
        $eqlogic->checkAndUpdateCmd('chat', $chatlist);
        $eqlogic->setConfiguration("chattimestamp", $info["timestamp"]);
        $eqlogic->save();
      break;
      case 'sendchat':
        $info = $eqlogic->sendChat($url . "&MCMASESSIONID=" . $MCMASESSIONID,$_options['message']);
        if ($info['status'] != 200) {
          break;
        }
        sleep(1);
        if ($chattimestamp < 10) {
          $chattimestamp = 0;
        }
        else {
          $chattimestamp = $chattimestamp - 10;
        }
        $info = $eqlogic->getChat($url . "&MCMASESSIONID=" . $MCMASESSIONID,$chattimestamp);
        if ($info['status'] != 200) {
          break;
        }
        $chatlist = array();
        if(isset($info['chatdata'])) {
          for($j = 0; $j < count($info['chatdata']); $j++) {
            if ($info['chatdata'][$j]['isChat']) {
              $chatlist[] = $info['chatdata'][$j]['user'] . ' : ' . $info['chatdata'][$j]['message'] . ' : ' . date("Y-m-d H:i:s", substr($info['chatdata'][$j]['time'],6,-2));
            }
          }
        }
        $chatlist = implode("<br/>", $chatlist);
        $eqlogic->checkAndUpdateCmd('chat', $chatlist);
        $eqlogic->setConfiguration("chattimestamp", $info["timestamp"]);
        $eqlogic->save();
      break;
    }
  }


  /*     * **********************Getteur Setteur*************************** */
}