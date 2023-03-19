# jeedom_mcmyadmin

un appel à l'api se fait de la manière ci-dessous :

http://__ip_ou_dns__:__port_interface_admin__/__chemin(facultatif)__?req=__requete__&__arguments_requis_en_fonction_de_la_requete_choisis__&__MCMASESSIONID(sur toute les commande sauf login)__&__Token=(a utiliser sur la commande login)__

TOUTES les commandes require l'utilisation d'un token reçu en retour de la commande "login" (MCMASESSIONID)

| req | arguments | description | pris en charge sur le plugin |
|:---------|:-------------:|:------------------|:-----:|
| dodiagnostics | na |      |   |
| getbackuplist | na |      | X |
| getbackupstatus | na |      |   |
| getchat | since(int) |      | X |
| getconfig | key(str) |      |   |
| getdeletestatus | na |      |   |
| getfullconfig | na |      | X |
| getplugins | na |      |   |
| getproviderinfo | na |      |   |
| getrestorestatus | na |      |   |
| getserverinfo | na |      |   |
| getstatus | na |      | X |
| gettip | na |      |   |
| getupdatestatus | na |      |   |
| getversions | na |      | X |
| killserver | na |      | X |
| logout | na |      |   |
| reload | na |      | X |
| restartserver | na |      | X |
| sendchat | message(str) |      | X |
| setconfig | key(str), value(str) |      |   |
| startserver | na |      | X |
| stopserver | na |      | X |
| updatemc | na |      |   |
| updatemcma | na |      |   |
| gettokenauth | username(str) |      |   |
| login | Username(str), Password(str) |      | X |
| getExtensions | na |      |   |
| sleepServer | na |      | X |
