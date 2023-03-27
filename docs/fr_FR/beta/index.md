# Plugin mcmyadmin

ce plugin permet de connecter un serveur minecraft utilisant le panel mcmyadmin.

le panel mcmyadmin est gratuit pour 10 joueurs est peut être telecharger sur le site officiel : https://mcmyadmin.com/
il existe aussi une image sur dockerhub : tekgator/docker-mcmyadmin:latest

le plugin permet à jeedom d'effectuer des actions de base tel que :
- le demarrage, l'arret ou le restart du serveur.
- le poste de message ou commande dans la console du serveur.
- le listing des utilisateur connecter, des derniers messages du chat et la configuration du serveur.


1. configuration
vous pouvez choisir sur cette page le nombre de message du chat recuperer sur chaque serveur.
![plugin](capture/configplugin.png)

2. configuration du compte sur mcmyadmin

creer un compte sur le serveur mcmyadmin.
(ce compte ne doit servir qu'à la communication avec jeedom)
pour ce faire aller sur Configuration > login users
le compte doit disposer au minimun des droits selectionner dans l'image ci-dessous 
![droits](capture/droitsmcmyadmin.png)

3. configuration du serveur sur jeedom

renseigner les information de connection
ip ou nom de domaine si le serveur setrouve en dehors du reseau de jeedom
port du serveur
nom de l'utilisateur creer
mot de passe de l'utilisateur creer
selection http ou https
note : la communication en https n'a pas été tester.(merci de me faire un retour si vous disposer d'un serveur en https)
![objet](capture/configobjet.png)

4. Widget
dans le dashboard, en bas de la tuile de votre objet il y a plusieurs icon, ces icon sont clicable est permet de naviger entre les vues de la tuile(menu, joueurs, chat, backup, config)
4.1. menu
dans menu (page par defaut) vous pouvez acceder au commande du serveur et autre info global
![menu](capture/menu.png)
4.2. joueurs
joueurs, la liste des joueurs connecter avec la possibiliter de les kick du serveur
![joueurs](capture/joueurs.png)
4.3. chat
chat, poster des message dans le chat ou utiliser "/" au debut pour envoyer une commande dans la console
![chat](capture/chat.png)
4.4. backups
backup, liste des backups (pas d'action disponible pour le moment)
![backups](capture/backup.png)
4.5. config
config, liste des config du serveur minecraft et du panel (pas d'action disponible pour le moment)
![config](capture/config.png)
