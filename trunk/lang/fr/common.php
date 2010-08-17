<?php
//
// kleeja language file
// French
// By: 2lo.in , Email: webmaster@2lo.in
//

if (!defined('IN_COMMON'))
        exit;

if (empty($lang) || !is_array($lang))
        $lang = array();



$lang = array_merge($lang, array(
        //language inforamtion
        'DIR'                                   => 'ltr',
        'LANG_SMALL_NAME'               => 'fr-fr',

        'HOME'                                  => 'Menu',
        'INDEX'                                 => 'Index',
        'SITE_CLOSED'                   => 'Le site est fermé.',
        'STOP_FOR_SIZE'                 => 'Le service est suspendu.',
        'SIZES_EXCCEDED'                => 'Nous avons manqué de place ... nous serons bientôt de retour',
        'ENTER_CODE_IMG'                => 'Entrez le code de vérification.',
        'SAFE_CODE'                     => 'Activer le code de sécurité pour les téléchargements',
        'LAST_VISIT'                    => 'Votre dernière visite',
        'FLS_LST_VST_SEARCH'    => 'Afficher tous les fichiers depuis votre dernière visite ?',
        'IMG_LST_VST_SEARCH'    => 'Afficher tous les fichiers depuis votre dernière visite ?',
        'NEXT'                                  => 'Suivant &raquo;',
        'PREV'                                  => '&laquo; Précédent',
        'INFORMATION'                   => 'Instructions',
        'WELCOME'                               => 'Bienvenue',
        'KLEEJA_VERSION'                => 'Version Kleeja ',
        'NUMBER_ONLINE'                 => 'Utilisateurs connectés',
        'NUMBER_UONLINE'                => 'Utilisateurs',
        'NUMBER_VONLINE'                => 'Invités',
        'USERS_SYSTEM'                  => 'Système Utilisateurs',
        'ERROR_NAVIGATATION'    => 'Erreur sur la redirection ..',
        'LOGIN'                                 => 'Connexion',
        'USERNAME'                              => 'Pseudo',
        'PASSWORD'                              => 'Mot de passe',
        'EMPTY_USERNAME'                => 'Veuillez entrer votre pseudo',
        'EMPTY_PASSWORD'                => 'Veuillez entrer votre mot de passe  ',
        'LOSS_PASSWORD'                 => 'Mot de passe oublié?',
        'LOGINED_BEFORE'                => 'Vous êtes déjà connecté.',
        'LOGOUT'                                => 'Quitter ',
        'EMPTY_FIELDS'                  => 'Erreur ... Champs manquants!',
        'LOGIN_SUCCESFUL'               => 'Vous êtes connecté avec succès.',
        'LOGIN_ERROR'                   => 'Erreur ... Connexion impossible!',
        'REGISTER_CLOSED'               => 'Désolé, les inscriptions sont pour le moment fermée.',
        'PLACE_NO_YOU'                  => 'Accès non autorisé',
        'REGISTERED_BEFORE'     => 'déjà',
        'REGISTER'                              => 'Inscription',
        'EMAIL'                                 => 'Adresse Email',
        'VERTY_CODE'                    => 'Code de Sécurité',
        'WRONG_EMAIL'                   => 'Adresse Email incorrecte!',
        'WRONG_NAME'                    => 'Le pseudo doit contenir plus de 4 caractères!',
        'WRONG_LINK'                    => 'Lien Incorrect ..',
        'EXIST_NAME'                    => 'Quelqu\'un a déjà été enregistré avec ce nom d\'utilisateur!',
        'EXIST_EMAIL'                   => 'Quelqu\'un a déjà enregistré avec cette adresse Email!',
        'WRONG_VERTY_CODE'              => 'Code de Sécurité Incorrect!',
        'CANT_UPDATE_SQL'               => 'Impossible de mettre à jour la base!',
        'CANT_INSERT_SQL'               => 'Impossible d\'ajouter des données dans la base!',
        'REGISTER_SUCCESFUL'    => 'Merci pour votre inscription.ً',
        'LOGOUT_SUCCESFUL'              => 'Déconnection réussie.',
        'LOGOUT_ERROR'                  => 'Erreur sur la déconnection!',
        'FILECP'                                => 'Gestion des fichiers',
        'DEL_SELECTED'                  => 'Supprimer la sélection',
        'EDIT_U_FILES'                  => 'Mise à jour des fichiers',
        'FILES_UPDATED'                 => 'Les fichiers ont été mis à jour.',
        'PUBLIC_USER_FILES'     => 'Dossier fichier public ',
        'FILEUSER'                              => 'Dossier fichier utilisateur ',
        'GO_FILECP'                     => 'Cliquer pour gérer les fichiers',
        'YOUR_FILEUSER'                 => 'Votre dossier',
        'COPY_AND_GET_DUD'              => 'Copier cette URL et partager vos fichiers avec vos amis ',
        'CLOSED_FEATURE'                => 'Fonctionnalitée fermée',
        'USERFILE_CLOSED'               => 'Dossier Utilisateur fermé !',
        'PFILE_4_FORUM'                 => 'Allez dans votre panneau de contrôle pour changer les détails',
        'USER_PLACE'                    => 'Secteur Utilisateurs',
        'PROFILE'                               => 'Profil Utilisateur',
        'EDIT_U_DATA'                   => 'Mettre à jour vos détails',
        'PASS_ON_CHANGE'                => 'Mot de passe (Seulement si vous voulez le changer).',
        'OLD'                                   => 'Ancien',
        'NEW'                                   => 'Nouveau',
        'NEW_AGAIN'                     => 'Confirmer',
        'UPDATE'                                => 'Mise à Jour',
        'PASS_O_PASS2'                  => 'L\'ancien mot de passe est nécessaire avant d\'entrez le nouveau mot de passe.',
        'DATA_CHANGED_O_LO'     => 'Vos détails ont été mis à jour.',
        'DATA_CHANGED_NO'               => 'Aucun nouveau renseignement.',
        'LOST_PASS_FORUM'               => 'Allez dans le forum pour changer vos détails ?',
        'GET_LOSTPASS'                  => 'Obtenir votre mot de passe',
        'E_GET_LOSTPASS'                => 'Entrez votre adresse Email pour recevoir votre mot de passe.',
        'WRONG_DB_EMAIL'                => 'L\'adresse e-mail indiquée ne peut être trouvée dans notre base de données!',
        'GET_LOSTPASS_MSG'              => "Vous avez demandé votre mot de passe. Celui-ci va être remis à zéro, mais pour éviter le spam cliquez sur le lien ci-dessous pour confirmation : \r\n %1\$s \r\n Nouveau mot de passe : %2\$s",
        'CANT_SEND_NEWPASS'     => 'Erreur... le nouveau mot de passe ne peut pas être envoyé!',
        'OK_SEND_NEWPASS'               => 'Nous vous avons envoyé votre nouveau mot de passe',
        'OK_APPLY_NEWPASS'              => 'Nouveau mot de passe. Vous pouvez maintenant vous connecter à votre compte.',
        'GUIDE'                                 => 'Extensions autorisées',
        'GUIDE_VISITORS'                => 'Extensions autorisées pour les invités:',
        'GUIDE_USERS'                   => 'Extensions autorisées pour les utilisateurs:',
        'EXT'                                   => 'Extension',
        'SIZE'                                  => 'Taille',
        'REPORT'                                => 'Rapport',
        'YOURNAME'                              => 'Votre nom',
        'URL'                                   => 'Lien',
        'REASON'                                => 'Raison',
        'NO_ID'                                 => 'Aucun fichier sélectionné ..!!',
        'NO_ME300RES'                   => 'La raison de votre rapport ne doit pas contenir plus de 300 caractères!!',
        'THNX_REPORTED'                 => 'Nous avons reçu votre rapport.',
        'RULES'                                 => 'Termes',
        'NO_RULES_NOW'                  => 'Aucun termes spécifiés pour le moment.',
        'E_RULES'                               => 'Ci-dessous les termes de nos services',
        'CALL'                                  => 'Contactez-nous',
        'SEND'                                  => 'Envoyer',
        'TEXT'                                  => 'Commentaires',
        'NO_ME300TEXT'                  => 'Votre commentaire ne doit pas contenir plus de 300 caractères!!',
        'THNX_CALLED'                   => 'Envoyé ... nous vous répondrons dès que possible.',
        'NO_DEL_F'                              => 'Désolé, mais la suppression du fichier par URL ne peut pas se faire. FOnction désactivée par l\'administration',
        'E_DEL_F'                               => 'Suppression du fichier par URL',
        'WRONG_URL'                     => 'Erreur: Cette URL ne semble pas valide !',
        'CANT_DEL_F'                    => 'Erreur: Impossible de supprimer le fichier .. Il serait peut-être déjà supprimé!',
        'CANT_DELETE_SQL'               => 'Le fichier ne peut pas être supprimé dans la base de données!',
        'DELETE_SUCCESFUL'              => 'Suppression réussie.',
        'STATS'                                 => 'Statistiques',
        'STATS_CLOSED'                  => 'Les stéatsiqtiques sont désactivées parl\'administrateur.',
        'FILES_ST'                              => 'Téléchargé',
        'FILE'                                  => 'Fichier',
        'USERS_ST'                              => 'Total Utilisateurs',
        'USER'                                  => 'utilisateur',
        'SIZES_ST'                              => 'Taille totale des fichiers téléchargés',
        'LSTFLE_ST'                     => 'Dernier téléchargement',
        'LSTDELST'                              => 'Dernière vérification des fichiers non téléchargés',
        'S_C_T'                                 => 'Invités aujourd\'hui',
        'S_C_Y'                                 => 'Invités d\'hier',
        'S_C_A'                                 => 'Total des invités',
        'LAST_1_H'                              => 'Statistiques depuis 1 heure',
        'DOWNLAOD'                              => 'Télécharger',
        'FILE_FOUNDED'                  => 'Fichié trouvé .. ',
        'WAIT'                                  => 'Veuillez patienter ..',
        'CLICK_DOWN'                    => 'Cliquez ici pour le télécharger',
        'JS_MUST_ON'                    => 'Activer Javascript dans votre navigateur!',
        'FILE_INFO'                     => 'Info fichier',
        'FILENAME'                              => 'Nom du fichier',
        'FILESIZE'                              => 'Taille du fichier',
        'FILETYPE'                              => 'Type du fichier',
		'FILEDATE'                              => 'Date du fichier',
        'LAST_DOWN'                     => 'Dernier téléchargement',
        'FILEUPS'                               => 'Nombre de téléchargements',
        'FILEREPORT'                    => 'Rapport sur les abus.',
        'FILE_NO_FOUNDED'               => 'Fichier introuvable ..!!',
        'IMG_NO_FOUNDED'                => 'Image introuvable ..!!',
        'NOT_IMG'                               => 'Ce n\'est pas une image!!',
        'MORE_F_FILES'                  => 'Champs maximum',
        'DOWNLOAD_F'                    => '[ Transfert en Local ]',
        'DOWNLOAD_T'                    => '[ Transfert par Lien ]',
        'PAST_URL_HERE'                 => '[ Copier votre lien ici ]',
        'SAME_FILE_EXIST'               => 'Le fichier "%s" existe déjà, veuillez le renommer et recommencer.',
        'NO_FILE_SELECTED'              => 'Sélectionner un fichier !!',
        'WRONG_F_NAME'                  => 'Le fichier "%s" contient des caractères non autorisés.',
        'FORBID_EXT'                    => 'L\'Extension "%s" n\'est pas autorisée.',
        'SIZE_F_BIG'                    => 'La taille du fichier est de "%1$s". Elle doit être inférieure à %2$s .',
        'CANT_CON_FTP'                  => 'COnnexion impossible à ',
        'URL_F_DEL'                     => 'Lien pour supprimer le fichier',
        'URL_F_THMB'                    => 'Lien pour la vignette',
        'URL_F_FILE'                    => 'Lien du fichier',
        'URL_F_IMG'                     => 'Lien de l\'image',
        'URL_F_BBC'                     => 'Lien pour les forums',
        'IMG_DOWNLAODED'                => 'Image uploaded successfully.',
        'FILE_DOWNLAODED'               => 'Fichier téléchargé avec succès.',
        'CANT_UPLAOD'                   => 'Erreur: le fichier "%s" ne peut pas être télécgargé. Pour des raisons inconnues!',
        'NEW_DIR_CRT'                   => 'Nouveau dossier créé',
        'PR_DIR_CRT'                    => 'Le dossier n\'est pas ré-inscriptible (CHMOD)',
        'CANT_DIR_CRT'                  => 'Le dossier n\'a pas été créé automatiquement, vous devez le faire manuellement.',
        'AGREE_RULES'                   => 'J\'Accepte les Termes du Service',
        'CHANG_TO_URL_FILE'     => 'Changer la méthode de téléchargement',
        'URL_CANT_GET'                  => 'erreur lors du transfert de ce fichier à partir d\'url..',
        'ADMINCP'                               => 'Panneau de Contôle',
        'JUMPTO'                                => 'Naviguez vers',
        'GO_BACK_BROWSER'               => 'Retour',
        'U_R_BANNED'                    => 'Votre IP a été bannie.',
        'U_R_FLOODER'                   => 'Système Ansti-Spam ...',
        'YES'                                   => 'Oui',
        'NO'                                    => 'Non',
        'LANGUAGE'                              => 'Language',
        'STYLE'                                 => 'Service style',
        'NORMAL'                                => 'Normal',
        'W_PHPBB'                               => 'Attaché à phpbb',
        'W_MYSBB'                               => 'Attaché à MySmartBB',
        'W_VBB'                                 => 'Attaché à vb',
        'GROUP'                                 => 'Catégorie',
        'UPDATE_FILES'                  => 'Mise à Jour des Fichiers',
        'BY'                                    => 'Par',
        'FILDER'                                => 'Dossier',
        'DELETE'                                => 'Supprimer',
        'GUST'                                  => 'Invité',
        'NAME'                                  => 'Nom',
        'CLICKHERE'                     => 'Cliquer ici',
        'TIME'                                  => 'Heure',
        'IP'                                    => 'IP',
        'N_IMGS'                                => 'Images',
        'N_ZIPS'                                => 'Fichiers ZIP',
        'N_TXTS'                                => 'Fichier TXT',
        'N_DOCS'                                => 'DOCS',
        'N_RM'                                  => 'RealMedia',
        'N_WM'                                  => 'WindowsMedia',
        'N_SWF'                                 => 'Flash Files',
        'N_QT'                                  => 'QuickTime',
        'N_OTHERFILE'                   => 'Autres fichiers',
        'RETURN_HOME'                   => 'Retour',
        'TODAY'                                 => 'Aujourd\'hui',
        'DAYS'                                  => 'Jours',
        'BITE'                                  => 'Octet',
        'SUBMIT'                                => 'Envoyer',
        'EDIT'                                  => 'Editer',
        'DISABLE'                                       => 'Désactiver',
        'ENABLE'                                        => 'Activer',    
        'OPEN'                                          => 'Ouvert',
        'KILOBYTE'                                      =>      'Kilo-octets',
        'NOTE'                                          =>      'Note',
        'WARN'                                          =>      'Attention',
        'ARE_YOU_SURE_DO_THIS'          => 'Etes-vous sur de vouloir faire cela?',
        'SITE_FOR_MEMBER_ONLY'          => 'Ce centre est réservé aux membres, enregistrez-vous ou connectez-vous pour télécharger les fichiers.',
        'AUTH_INTEGRATION_N_UTF8_T'     => '%s n\'est pas en utf8',
        'AUTH_INTEGRATION_N_UTF8'       => '%s database doit être en utf8 pour être intégrée avec Kleeja !.',
        'SCRIPT_AUTH_PATH_WRONG'        => 'Le Chemin %s n\'est pas valide, veuillez le changer.',
        'SHOW_MY_FILECP'                        => 'Afficher mes fichiers',
        'PASS_ON_CHANGE'                        => 'Changer le mot de passe',
        'MOST_EVER_ONLINE'                      => 'Maximum des utilisateurs en ligne ',
        'ON'                                            => 'le',
        'LAST_REG'                                      => 'Nouveau membre',
        'NEW_USER'                                      => 'Nouvel utilisateur',
        'LIVEXTS'                                       => 'Live extensions',
        'ADD_UPLAD_A'                           => 'Ajouter d\{autres champs',
        'ADD_UPLAD_B'                           => 'Supprimer des champs',
        'COPYRIGHTS_X'                          => 'Tous droits réservés',
        'CHECK_ALL'                                     => 'Vérifier tous',
        'BROSWERF'                                      => 'Fichiers utilisateurs',
        'REMME'                                         => 'Rappel connexion',
        'HOUR'                                          => 'une heure',
        '5HOURS'                                        => '5 heures',
        'DAY'                                           => 'un jour',
        'WEEK'                                          => 'une semaine',
        'MONTH'                                         => 'un mois',
        'YEAR'                                          => 'un an',
        'INVALID_FORM_KEY'                      => 'Formulaire vide, ou votre session a expirée',
		'INVALID_GET_KEY'			=> 'Sorry, The requested link is expired, and is blocked for secuirty reason, go back and try again.',
        'REFRESH_CAPTCHA'                       => 'Cliquer ici',
        'CHOSE_F'                                       => 'S\'il vous plaît sélectionnez au moins un fichier',
        'NO_REPEATING_UPLOADING'        => 'La page ne devrait pas être actualisé après un transfert!.',
        'NOTE_CODE'                             => 'Entrez les caractères montrés dans l\'image avec précision',
        'USER_LOGIN'                            => ' Connexion (membre seulement) ',
        'FILES_DELETED'                         => 'Les fichiers ont été supprimés.',
        'GUIDE_GROUPS'                      => 'Groupe',
        'ALL_FILES'                         => 'Total de fichiers dans votre compte',
        'NO_FILE_USER'                          => 'Aucun fichier dans votre compte!',
        'SHOWFILESBYIP'                         => 'Affichier les fichiers par IP',
        'WAIT_LOADING'                          => 'Veuillez attendre, les fichiers vont être transférés vers le serveur...',
        'NOTICECLOSED'                          => 'Notice: Le site est fermé',
        'UNKNOWN'                                       => 'Inconnu',
        'WE_UPDATING_KLEEJA_NOW'        => 'Fermé pour maintenance, revenez plus tard...',
        'ERROR_TRY_AGAIN'                       => 'Erreur, recommencer.',
        'VIEW'                                          => 'Vue',
        'NONE'                                          => 'Aucun',
        'USER_STAT'                                     => 'Stats Utilisateurs',
        'SEARCH_STAT'                           => 'Stats Robots de recherche',
        'NOTHING'                                       => 'Il n\'y a aucun fichier ou photo .. !!',
        'YOU_HAVE_TO_WAIT'                      => 'Veuillez attendre %s secondes .. avant de télécharger un autre fichier',
		'REPEAT_PASS'				=> 'Repeat Password',
		'PASS_NEQ_PASS2'			=> 'Passwords are not equal !',
		'LOAD_IS_HIGH_NOW'			=> 'Our website facing very high load right now !, please wait and try refresh this page again.',
	

        //last line of this file ...                                    
        'S_TRANSLATED_BY'                       => 'Translated By <a href="http://2lo.in/" target="_blank">2lo.in</a>',
       
));

#<-- EOF
