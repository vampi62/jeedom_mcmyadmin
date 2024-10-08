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
    foreach (self::byType('mcmyadmin', true) as $mcmyadmin) { //parcours tous les équipements actifs du plugin
      $cmd = $mcmyadmin->getCmd(null, 'refresh');
      if (!is_object($cmd)) {
        continue;
      }
      $cmd->execCmd();
    }
  }
  /*     * *********************Méthodes d'instance************************* */

  // Fonction exécutée automatiquement avant la création de l'équipement
  public function preInsert() {
    $this->setDisplay('height', '402px');
    $this->setDisplay('width', '458px');
    $this->setIsEnable(1);
    $this->setIsVisible(1);
  }

  // Fonction exécutée automatiquement après la création de l'équipement
  public function postInsert() {
  }

  // Fonction exécutée automatiquement avant la mise à jour de l'équipement
  public function preUpdate() {
  }

  // Fonction exécutée automatiquement après la mise à jour de l'équipement
  public function postUpdate() {
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function set_limit_element($cmd,$mini,$maxi){
    $element = $this->getCmd(null, $cmd);
    if (is_object($element)) {
      if (($element->getConfiguration('maxValue', "") != $maxi) || ($element->getConfiguration('minValue', "") != $mini)) {
        $element->setConfiguration('maxValue', $maxi);
        $element->setConfiguration('minValue', $mini);
        $element->save();
      }
    }
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  private function create_element($newcmd,$newname,$newtype,$newsubtype,$newunit = "",$newtemplate = 'default'){
    $newelement = $this->getCmd(null, $newcmd);
    if (!is_object($newelement)) {
      $newelement = new mcmyadminCmd();
      $newelement->setName(__($newname, __FILE__));
    }
    $newelement->setEqLogic_id($this->getId());
    $newelement->setLogicalId($newcmd);
    $newelement->setType($newtype);
    $newelement->setSubType($newsubtype);
    $newelement->setTemplate('dashboard',$newtemplate);
    if ($newtype == 'info') {
      $newelement->setGeneric_type('GENERIC_INFO');
    } else {
      $newelement->setGeneric_type('GENERIC_ACTION');
    }
    if ($newunit != "") {
      $newelement->setUnite($newunit);
    }
    $newelement->save();
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
    $this->create_element('refresh_version','refresh_version','action','other');
    $this->create_element('refresh_config','refresh_config','action','other');
    $this->create_element('refresh_backuplist','refresh_backuplist','action','other');
    
    $this->create_element('sendchat','send','action','message');
    $this->create_element('chat','chat','info','string');
    $this->create_element('chatoffset','chatoffset','info','numeric');
    $element = $this->getCmd(null, 'chatoffset');
    if (is_object($element)) {
      $element->setIsVisible(0);
      $element->save();
    }

    $this->create_element('cpuusage','cpuusage','info','string', '%');
    $this->create_element('ram','ram','info','string','Mo');
    $this->create_element('rammax','rammax','info','string','Mo');
    $this->create_element('users','users','info','string');
    $this->create_element('usermax','usermax','info','string');
    $element = $this->getCmd(null, 'sendchat');
    if (is_object($element)) {
      $element->setDisplay('title_disable', '1');
      $element->save();
    }

    $this->create_element('state','state','info','string');
    $this->create_element('state_text','state_text','info','string');
    

    $this->create_element('failed','failed','info','string');
    $this->create_element('failmsg','failmsg','info','string');
    $this->create_element('userlist','userlist','info','string');
    $this->create_element('configlist','configlist','info','string');
    $this->create_element('backuplist','backuplist','info','string');
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
  
	public function getmcstatus($url) {
		return $this->request($url . "&req=getstatus");
	}
	public function getmcversion($url) {
		return $this->request($url . "&req=getversions");
	}
	public function getfullconfig($url) {
		return $this->request($url . "&req=getfullconfig");
	}
	public function getbackuplist($url) {
		return $this->request($url . "&req=getbackuplist");
	}
	public function getChat($url,$since) {
		if($since >= 0) {
      return $this->request($url . "&req=getchat&since=" . $since);
		}
	}
	public function sendChat($url,$message) {
		if($message) {
      return $this->request($url . "&req=sendchat&message=" . urlencode($message));
		}
	}
  public function exec_command($url,$commandname) {
    return $this->request($url . "&req=" . $commandname);
  }
  
  public function login($url) {
    $result = $this->request($url . "&req=login" . "&Token=");
    if ($result == "") {
      return "";
    }
    if($result['status'] == 200){
      return $result['MCMASESSIONID'];
    }
    if($result['status'] == 403){
      return "403"; // Forbidden
    }
    if($result['status'] == 429){
      return "429"; // Too many requests
    }
    return "";
  }
  public function request($url) {
    $request_http = new com_http($url);
    $request_http->setNoReportError(true);
    $request_http->setHeader(array('Content-type: application/json','Accept: application/json'));
    $result=$request_http->exec();
    if ($result == "") {
      return "";
    }
    log::add('mcmyadmin','debug',$url);
    log::add('mcmyadmin','debug',$result);
    return json_decode($result,true);
  }
  public function toHtml($_version = 'dashboard') {
    $replace = $this->preToHtml($_version);
    if (!is_array($replace)) {
      return $replace;
    }
    $version = jeedom::versionAlias($_version);
    foreach ($this->getCmd('info') as $cmd) {
      if (!is_object($cmd)) {
        continue;
      }
      $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
      $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
      $replace['#' . $cmd->getLogicalId() . '_valueDate#']= date('d-m-Y H:i:s',strtotime($cmd->getValueDate()));
      $replace['#' . $cmd->getLogicalId() . '_collectDate#'] =date('d-m-Y H:i:s',strtotime($cmd->getCollectDate()));
      $replace['#' . $cmd->getLogicalId() . '_updatetime#'] =date('d-m-Y H:i:s',strtotime( $this->getConfiguration('updatetime')));
      $replace['#lastCommunication#'] =date('d-m-Y H:i:s',strtotime($this->getStatus('lastCommunication')));
      $replace['#numberTryWithoutSuccess#'] = $this->getStatus('numberTryWithoutSuccess', 0);
      if ($cmd->getIsHistorized() == 1) {
        $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
      }
    }
    foreach ($this->getCmd('action') as $cmd) {
      if (!is_object($cmd)) {
        continue;
      }
      $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
      if ($cmd->getConfiguration('listValue', '') != '') {
        $listOption = '';
        $elements = explode(';', $cmd->getConfiguration('listValue', ''));
        $foundSelect = false;
        foreach ($elements as $element) {
          list($item_val, $item_text) = explode('|', $element);
          //$coupleArray = explode('|', $element);
          $cmdValue = $cmd->getCmdValue();
          if (is_object($cmdValue) && $cmdValue->getType() == 'info') {
            if ($cmdValue->execCmd() == $item_val) {
              $listOption .= '<option value="' . $item_val . '" selected>' . $item_text . '</option>';
              $foundSelect = true;
            } else {
              $listOption .= '<option value="' . $item_val . '">' . $item_text . '</option>';
            }
          } else {
            $listOption .= '<option value="' . $item_val . '">' . $item_text . '</option>';
          }
        }
        //if (!$foundSelect) {
        //	$listOption = '<option value="">Aucun</option>' . $listOption;
        //}
        //$replace['#listValue#'] = $listOption;
        $replace['#' . $cmd->getLogicalId() . '_id_listValue#'] = $listOption;
        $replace['#' . $cmd->getLogicalId() . '_listValue#'] = $listOption;
      }
    }
    $parameters = $this->getDisplay('parameters');
    if (is_array($parameters)) {
      foreach ($parameters as $key => $value) {
        $replace['#' . $key . '#'] = $value;
      }
    }
    $replace['#heightconfiglist#'] = strval(intval($replace['#height#'])-70);
    $replace['#widthconfiglist#'] = strval(intval($replace['#width#']));
    $replace['#heightbackuplist#'] = strval(intval($replace['#height#'])-70);
    $replace['#widthbackuplist#'] = strval(intval($replace['#width#']));
    $replace['#heightchat#'] = strval(intval($replace['#height#'])-150);
    $replace['#widthchat#'] = strval(intval($replace['#width#']));
    $replace['#heightuserlist#'] = strval(intval($replace['#height#'])-70);
    $replace['#widthuserlist#'] = strval(intval($replace['#width#']));
    $widgetType = getTemplate('core', $version, 'box', __CLASS__);
		return $this->postToHtml($_version, translate::exec(template_replace($replace, $widgetType), 'plugins/mcmyadmin/core/template/' . $version . '/box.html'));
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
    $adresse = $eqlogic->getConfiguration("adresse", "localhost");
    $port = $eqlogic->getConfiguration("port", "8080");
    $utilisateur = $eqlogic->getConfiguration("utilisateur", "admin");
    $password = $eqlogic->getConfiguration("password", "admin");
    $protocol = $eqlogic->getConfiguration("protocol", "http");

    $cmd = $eqlogic->getCmd(null, 'chatoffset');
    if (is_object($cmd)) {
      $chatoffset = intval($cmd->execCmd());
    }
    else {
      $chatoffset = 0;
    }
    $args = array();
    $args['Username'] = $utilisateur;
    $args['Password'] = $password;
    $param = http_build_query($args);
    $url = $protocol . '://' . $adresse . ':' . $port . '/data.json?' . $param;
    if(isset($_options['MCMASESSIONID'])) {
      $MCMASESSIONID = $_options['MCMASESSIONID'];
    }
    else {
      $MCMASESSIONID = $eqlogic->login($url); // login si pas de token
    }
    if($MCMASESSIONID == "") {
      $eqlogic->checkAndUpdateCmd('state', "-1");
      $eqlogic->checkAndUpdateCmd('state_text', "{{hors ligne}}");
      $eqlogic->checkAndUpdateCmd('users', 0);
      $eqlogic->checkAndUpdateCmd('userlist', "");
      $eqlogic->checkAndUpdateCmd('ram', 0);
      $eqlogic->checkAndUpdateCmd('starttime', "[ unknown ]");
      $eqlogic->checkAndUpdateCmd('cpuusage', 0);
      $eqlogic->checkAndUpdateCmd('chat', "");
      $eqlogic->checkAndUpdateCmd('failed', "");
      $eqlogic->checkAndUpdateCmd('failmsg', "");
      return false;
    }
    if($MCMASESSIONID == "403" || $MCMASESSIONID == "429") {
      $eqlogic->checkAndUpdateCmd('state', "-1");
      $eqlogic->checkAndUpdateCmd('state_text', "{{identifiants incorrects}}");
      return false;
    }
    switch ($this->getLogicalId()) { //vérifie le logicalid de la commande
      default:
        $info = $eqlogic->exec_command($url . "&MCMASESSIONID=" . $MCMASESSIONID,$this->getLogicalId());
        if ($info['status'] != 200) {
          break;
        }
        sleep(5); // on attend 5 secondes pour laisser le temps au serveur de traiter la commande
      //break; // pas de break pour executer refresh
      case 'refresh':
        $info = $eqlogic->getmcStatus($url . "&MCMASESSIONID=" . $MCMASESSIONID);
        if ($info['status'] != 200) {
          break;
        }
        $playerlist = array();
        if(count($info['userinfo']) > 0) {
          $eqlogic->checkAndUpdateCmd('users', count($info['userinfo']));
          foreach($info['userinfo'] as $user => $values) {
            $playerlist[] = $values['Name'] . "-:-" . $values['IP'] . "-:-" . date("Y-m-d H:i:s", substr($values['ConnectTime'],6,-5));
          }
          $playerlist = implode("-!-", $playerlist);
        }
        else {
          $eqlogic->checkAndUpdateCmd('users', 0);
          $playerlist = "";
        }
        $eqlogic->checkAndUpdateCmd('state', $info['state']);
        switch($info['state'])
        {
          case "0":
            $eqlogic->checkAndUpdateCmd('state_text', "{{arrêté}}");
          break;
          case "10":
            $eqlogic->checkAndUpdateCmd('state_text', "{{démarrage}}");
          break;
          case "20":
            $eqlogic->checkAndUpdateCmd('state_text', "{{démarré}}");
          break;
          case "30":
            $eqlogic->checkAndUpdateCmd('state_text', "{{arrêt}}");
          break;
          case "40":
            $eqlogic->checkAndUpdateCmd('state_text', "{{redémarrage}}");
          break;
          case "50":
            $eqlogic->checkAndUpdateCmd('state_text', "{{mise à jour}}");
          break;
          default:
            $eqlogic->checkAndUpdateCmd('state_text', "{{inconnu}}");
          break;
        }
        $eqlogic->checkAndUpdateCmd('failed', $info['failed']);
        $eqlogic->checkAndUpdateCmd('failmsg', $info['failmsg']);
        //$eqlogic->set_limit_element('users',0,$info['maxusers']);
        $eqlogic->checkAndUpdateCmd('usermax', $info['maxusers']);
        $eqlogic->checkAndUpdateCmd('userlist', $playerlist);
        $eqlogic->checkAndUpdateCmd('time', $info['time']);
        //$eqlogic->set_limit_element('ram',0,$info['maxram']);
        $eqlogic->checkAndUpdateCmd('ram', $info['ram']);
        $eqlogic->checkAndUpdateCmd('rammax', $info['maxram']);
        $eqlogic->checkAndUpdateCmd('starttime', $info['starttime']);
        $eqlogic->checkAndUpdateCmd('cpuusage', $info['cpuusage']);
        $cmd = $eqlogic->getCmd(null, 'refresh_version');
        if (is_object($cmd)) {
          $cmd->execCmd(['MCMASESSIONID'=>$MCMASESSIONID]);
        }
        $cmd = $eqlogic->getCmd(null, 'refresh_backuplist');
        if (is_object($cmd)) {
          $cmd->execCmd(['MCMASESSIONID'=>$MCMASESSIONID]);
        }
        $cmd = $eqlogic->getCmd(null, 'refresh_config');
        if (is_object($cmd)) {
          $cmd->execCmd(['MCMASESSIONID'=>$MCMASESSIONID]);
        }
        $cmd = $eqlogic->getCmd(null, 'refresh_chat');
        if (is_object($cmd)) {
          $cmd->execCmd(['MCMASESSIONID'=>$MCMASESSIONID]);
        }
      break;
      case 'refresh_version':
        $info = $eqlogic->getmcVersion($url . "&MCMASESSIONID=" . $MCMASESSIONID);
        if ($info['status'] != 200 && $info['status'] != 500) { // 500 = ?bug api mais retour data
          break;
        }
        $eqlogic->checkAndUpdateCmd('backend', $info['backend']);
        $eqlogic->checkAndUpdateCmd('mc', $info['mc']);
      break;
      case 'refresh_backuplist': // exec commande si refresh_chat ou refresh
        $info = $eqlogic->getbackuplist($url . "&MCMASESSIONID=" . $MCMASESSIONID);
        if ($info['status'] != 200) {
          break;
        }
        $list = array();
        if(count($info['backuplist']) > 0) {
          foreach($info['backuplist'] as $user => $values) {
            //$list[] = $values['Name'] . "-:-" . $values['IP'] . "-:-" . date("Y-m-d H:i:s", substr($values['ConnectTime'],6,-5));
          }
          $list = implode("-!-", $list);
        }
        else {
          $list = "";
        }
        $eqlogic->checkAndUpdateCmd('backuplist', $list);
      break;
      case 'refresh_config': // exec commande si refresh_chat ou refresh
        $info = $eqlogic->getfullconfig($url . "&MCMASESSIONID=" . $MCMASESSIONID);
        if ($info['status'] != 200) {
          break;
        }
        $list = array();
        if(count($info['settings']) > 0) {
          foreach($info['settings'] as $user => $values) {
            $list[] = $user . "-:-" . $values;
          }
          $list = implode("-!-", $list);
        }
        else {
          $list = "";
        }
        $eqlogic->checkAndUpdateCmd('configlist', $list);
      break;
      case 'refresh_chat': // exec commande si refresh_chat ou refresh
        $info = $eqlogic->getChat($url . "&MCMASESSIONID=" . $MCMASESSIONID,$chatoffset);
        if ($info['status'] != 200) {
          break;
        }
        $chatlist = array();
        if(count($info['chatdata']) > 0) {
          $nbr_chat_impr = 0;
          $nbr_ligne_max = config::byKey('nbrchatlineshow', 'mcmyadmin');
          foreach(array_reverse($info['chatdata']) as $user => $values) { // reverse pour afficher les derniers messages en premier
            if ($values['isChat']) {
              $chatlist[] = date("Y-m-d H:i:s", substr($values['time'],6,-5)) . '-:-' . $values['user'] . '-:-' . $values['message'];
              $nbr_chat_impr++;
              if ($nbr_chat_impr >= $nbr_ligne_max) {
                $eqlogic->checkAndUpdateCmd("chatoffset", intval($values["timestamp"]-1));
                break;
              }
            }
          }
          if ($nbr_chat_impr < $nbr_ligne_max) {
            $eqlogic->checkAndUpdateCmd("chatoffset", 0);
          }
          $chatlist = implode("-!-", array_reverse($chatlist));
        }
        else {
          $chatlist = "";
        }
        $eqlogic->checkAndUpdateCmd('chat', $chatlist);
        if (intval($info["timestamp"]) < $chatoffset) {
          $eqlogic->checkAndUpdateCmd("chatoffset", 0);
          $cmd = $eqlogic->getCmd(null, 'refresh_chat');
          if (is_object($cmd)) {
            $cmd->execCmd(['MCMASESSIONID'=>$MCMASESSIONID]);
          }
        }
      break;
      case 'sendchat':
        $info = $eqlogic->sendChat($url . "&MCMASESSIONID=" . $MCMASESSIONID,$_options['message']);
        //if ($info['status'] != 200) {
        //  break;
        //}
        sleep(1); // on attend 1 seconde pour laisser le temps au serveur de traiter la commande
        $cmd = $eqlogic->getCmd(null, 'refresh_chat');
        if (is_object($cmd)) {
          $cmd->execCmd(['MCMASESSIONID'=>$MCMASESSIONID]);
        }
      break;
    }
  }

  /*     * **********************Getteur Setteur*************************** */
}