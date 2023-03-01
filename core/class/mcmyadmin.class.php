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
    $cmd = $this->getCmd(null, 'refresh'); //On recherche la commande refresh de l’équipement
    if (is_object($cmd)) { //elle existe et on lance la commande
      $cmd->execCmd();
    }
  }

  // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement
  public function preSave() {
  }

  // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement
  public function postSave() {
    $info = $this->getCmd(null, 'status');
    if (!is_object($info)) {
      $info = new mcmyadminCmd();
      $info->setName(__('status', __FILE__));
    }
    $info->setLogicalId('status');
    $info->setEqLogic_id($this->getId());
    $info->setType('info');
    $info->setSubType('string');
    $info->save();
  
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

  public function getstatus() {
    return "test";
    /*
    $adresse = $this->getConfiguration("adresse");
    $port = $this->getConfiguration("port");
    $utilisateur = $this->getConfiguration("utilisateur");
    $password = $this->getConfiguration("password");
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

  /*     * **********************Getteur Setteur*************************** */
  /*
  import requests
  import json
  data = {}
  headers = {
      "Content-Type": "application/json",
    "Accept": "application/json"
  }
  url = "http://192.168.2.55:8080/data.json?req=login&Username=admin&Password=pass123&Token="
  response = requests.get(url, json=data, headers=headers)
  if response.status_code == 200:
    data = json.loads(response.text)
    url = "http://192.168.2.55:8080/data.json?req=getstatus&Username=admin&Password=pass123&MCMASESSIONID=" + data["MCMASESSIONID"]
    response = requests.get(url, json=data, headers=headers)
    if response.status_code == 200:
      data = json.loads(response.text)
      
      {
      'status': 200,
      'state': 0,
      'failed': False,
      'failmsg': None,
      'maxram': 2048,
      'users': 0,
      'maxusers': 10,
      'userinfo': {},
      'time': '2023-02-18 18:59:30',
      'ram': 0,
      'starttime': '[Not Running]',
      'uptime': None,
      'cpuusage': 0
      }
      jeedom send data
      
      url = "http://192.168.2.55:8080/data.json?req=logout&Username=admin&Password=pass123&MCMASESSIONID=" + data["MCMASESSIONID"]
      response = requests.get(url, json=data, headers=headers)
      */
}