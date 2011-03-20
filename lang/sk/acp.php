<?php
//
// kleeja language file, admin
// Slovak
// Translated By:emsit , Contact: www.emsit.info , Website: www.emsit.sk
//

if (!defined('IN_COMMON'))
	exit;

if (empty($lang) || !is_array($lang))
	$lang = array();


$lang = array_merge($lang, array(
	'U_NOT_ADMIN' 			=> 'Nemáte administrátorské oprávnenie',
	'UPDATE_CONFIG' 		=> 'Aktualizovať nastavenia',
	'NO_CHANGE' 			=> 'Ostané nezmenené',
	'CHANGE_MD5' 			=> 'Zmeniť pomocou MD5',
	'CHANGE_TIME' 			=> 'Zmeniť pomocou TIME',
	'SITENAME' 				=> 'Názov služby',
	'SITEMAIL' 				=> 'E-mailová adresa',
	'SITEMAIL2' 			=> 'E-mailová adresa pre správy',
	'SITEURL' 				=> 'Adresa URL služby so symbolom / na konci',
	'FOLDERNAME' 			=> 'Názov zložky pre nahraté súbory',
	'PREFIXNAME' 			=> 'Predpona súboru <small>( Môžete tiež použiť {rand:4} , {date:d_Y})</small>',
	'FILESNUM' 				=> 'Počet vstupných polí pre súbory',
	'FILESNUM_SHOW' 		=> 'Zobraziť všetky vstupné polia pre súbory',
	'SITECLOSE' 			=> 'Vypnutie služby',
	'CLOSEMSG' 				=> 'Správa o vypnutí',
	'DECODE' 				=> 'Zmena názvu súborou',
	'SEC_DOWN' 				=> 'Koľko sekúnd čakať pred začatím stiahnutia',
	'STATFOOTER' 			=> 'Štatistiky web stránky v päte',
	'GZIP' 					=> 'Použiť gzip',
	'GOOGLEANALYTICS' 		=> '<a href="http://www.google.com/analytics" target="_kleeja"><span style="color:orange">Google</span> Analytics</a>',
	'WELCOME_MSG' 			=> 'Uvítacia správa',
	'USER_SYSTEM' 			=> 'Užívateľský systém',
	'ENAB_REG' 				=> 'Povoliť registráciu',
	'TOTAL_SIZE' 			=> 'Maximálna veľkosť služby[MB]',
	'THUMBS_IMGS' 			=> 'Povoliť miniatúry obrázkov',
	'WRITE_IMGS' 			=> 'Povoliť vodoznak na obrázkoch',
	'ID_FORM' 				=> 'ID súborov',
	'IDF' 					=> 'ID súborov v databáze',
	'IDFF' 					=> 'Názov súboru',
	'IDFD' 					=> 'Priama cesta',
	'DEL_URL_FILE' 			=> 'Povoliť mazanie súborov pomocou odkazov',
	'WWW_URL' 				=> 'Povoliť nahrávanie z URL',
	'ALLOW_STAT_PG' 		=> 'Povoliť štatistky stránky',
	'ALLOW_ONLINE' 			=> 'Povoliť kto je prihlásený',
	'MOD_WRITER' 			=> 'Mod Rewrite',
	'MOD_WRITER_EX' 		=> 'HTML odkazy..',
	'DEL_F_DAY' 			=> 'Odstrániť nesťahované súbory po',
	'NUMFIELD_S' 			=> 'V niektorých oblastiach môžete používať len čísla!',
	'CONFIGS_UPDATED' 		=> 'Nastavenia úspešne aktualizované.',
	'UPDATE_EXTS' 			=> 'Aktualizovať typy súborov',
	'SIZE_G' 				=> 'Veľkosť [ <font style="color:red">Hostia</font> ]',
	'SIZE_U' 				=> 'Veľkosť [ <font style="color:green">Užívatelia</font> ]',
	'ALLOW_G' 				=> 'Povoliť <br />[Hosťom]',
	'ALLOW_U' 				=> 'Povoliť <br />[Užívateľom]',
	'E_EXTS' 				=> 'Poznámka: Veľkosť je udávaná v kilobajtoch [KB].</i>',
	'UPDATED_EXTS' 			=> 'Typy súborov úspešné aktualizované',
	'UPDATE_REPORTS' 		=> 'Aktualizovať hlásenia',
	'E_CLICK' 				=> 'Zvoľte jedno ktoré budete vidieť tu',
	'REPLY' 				=> 'Odpoveď',
	'REPLY_REPORT' 			=> 'Odpoveď na hlásenie ',
	'U_REPORT_ON' 			=> 'Vaše hlásenie z ',
	'BY_EMAIL' 				=> 'Z e-mailovej adresy ',
	'ADMIN_REPLIED' 		=> 'Odpoveď administrátora',
	'CANT_SEND_MAIL' 		=> 'nemôže odoslať odpoveď na e-mail',
	'IS_SEND_MAIL' 			=> 'Odpoveď bola odoslaná.',
	'REPORTS_UPDATED' 		=> 'Hlásenia boli aktualizované.',
	'UPDATE_CALSS' 			=> 'Aktualizovať správy',
	'REPLY_CALL' 			=> 'Odpovedať na správu ',
	'REPLIED_ON_CAL' 		=> 'Na Vašu správu z ',
	'CALLS_UPDATED' 		=> 'Správy úspešne aktualizované.',
	'IS_ADMIN' 				=> 'Administrátor',
	'UPDATE_USERS' 			=> 'Aktualizovať užívateľov',
	'USERS_UPDATED' 		=> 'Užívatelia úspešné aktualizovaný.',
	'E_BACKUP' 				=> 'Vyberte tabuľky, ktoré chcete zálohovať:',
	'TAKE_BK' 				=> 'Záloha databázy',
	'REPAIRE_TABLE' 		=> '[Tabuľky] Opravené: ',
	'REPAIRE_F_STAT' 		=> '[Štatistiky] celkový počet súborov bol prestavovaný.',
	'REPAIRE_S_STAT' 		=> '[Štatistiky] celková veľkosť súborov bola prestavovaná.',
	'REPAIRE_CACHE' 		=> '[Pamäť Cache] bola zmazaná  ..',
	'KLEEJA_CP' 			=> '[ Kleeja ] Administrácia',
	'GENERAL_STAT' 			=> 'Základné štatistiky',
	'OTHER_INFO' 			=> 'Ostatné informácie',
	'AFILES_NUM' 			=> 'Celkový počet súborov',
	'AFILES_SIZE' 			=> 'Celková veľkosť súborov',
	'AFILES_SIZE_SPACE' 	=> 'Priestor ktorý bol zaplnený',
	'AUSERS_NUM' 			=> 'Celkový počet užívateľov',
	'LAST_GOOGLE' 			=> 'Posledná návšteva Google bota',
	'GOOGLE_NUM' 			=> 'Počet návštev Google bota',
	'LAST_YAHOO' 			=> 'Posledná návšteva Yahoo bota',
	'YAHOO_NUM' 			=> 'Počet návštev Yahoo bota',
	'KLEEJA_CP_W' 			=> 'Ahoj ! [ %s ] , Vitaj v <b>Kleeja</b> Administrátorskom paneli',
	'PHP_VER' 				=> 'php verzia',
	'MYSQL_VER' 			=> 'mysql verzia',
	'LOGOUT_CP_OK' 			=> 'Vaša administrátorská sekcia bola vyčistená..',
	'R_CONFIGS' 			=> 'Všeobecné nastavenia',
	'R_CPINDEX' 			=> 'Admin úvodná obrazovka',
	'R_EXTS' 				=> 'Nastavenia typov súborov',
	'R_FILES' 				=> 'Správa súborov',
	'R_REPORTS' 			=> 'Hlásenia',
	'R_CALLS' 				=> 'Správy',
	'R_USERS' 				=> 'Užívateľské ovládanie',
	'R_BCKUP' 				=> 'Záloha databázy',
	'R_REPAIR' 				=> 'Celková oprava',
	'R_LGOUTCP' 			=> 'Vymazať pamäť Cache',
	'R_BAN' 				=> 'Ban kontrolný panel',
	'BAN_EXP1' 				=> 'Upraviť zablokované IP adresy a pridať nové tu..',
	'BAN_EXP2' 				=> 'Použite symbol hviezdičky (*) ako náhradu za čísla (príklad 127.0.0.*|127.*.*.*) .... pre oddelenie IP adries použite symbol |',
	'UPDATE_BAN' 			=> 'Uložiť zmeny',
	'BAN_UPDATED' 			=> 'Zmeny úspešné uložené.',
	'R_RULES' 				=> 'Podmienky',
	'RULES_EXP' 			=> 'Tu môžete upravovať podmienky svojej služby',
	'UPDATE_RULES' 			=> 'Aktualizovať',
	'RULES_UPDATED' 		=> 'Podmienky boli úspešné aktualizované..',
	'R_SEARCH' 				=> 'Pokročilé vyhľadávanie',
	'SEARCH_FILES' 			=> 'Vyhľadávanie súborov',
	'SEARCH_SUBMIT' 		=> 'Vyhľadať teraz',
	'LAST_DOWN' 			=> 'Posledné stiahnuté',
	'WAS_B4' 				=> 'Bolo pred',
	'SEARCH_USERS' 			=> 'Hľadať medzi užívateľmi',
	'R_IMG_CTRL' 			=> 'Správa obrázkov',
	'ENABLE_USERFILE' 		=> 'Povoliť užívateľom vidieť ich súbory',
	'R_EXTRA' 				=> 'Doplnky šablóny',
	'EX_HEADER_N' 			=> 'Zvlášť hlavička.. Čo sa ukáže v dolnej časti pôvodného záhlavia',
	'EX_FOOTER_N' 			=> 'Zvlášť päta.. Čo sa ukáže v hornej časti pôvodnej päty',
	'UPDATE_EXTRA' 			=> 'Aktualizovať doplnky šablóny',
	'EXTRA_UPDATED' 		=> 'Doplnky šablóny úspešné aktualizované',
	'R_STYLES' 				=> 'Šablóny',
	'STYLES_EXP' 			=> 'Vyberte šablónu z ponuky k odstráneniu alebo aktualizácii',
	'SHOW_TPLS' 			=> 'Zobraziť šablóny',
	'TPL_UPDATED' 			=> 'Šablóna aktualizovaná..',
	'TPL_DELETED' 			=> 'Šablóna vymazaná..',
	'NO_TPL_SHOOSED' 		=> 'Nevybrali ste žiadnu šablónu!',
	'NO_TPL_NAME_WROTE' 	=> 'Prosím, zadajte názov šablóny!',
	'ADD_NEW_STYLE' 		=> 'Vytvoriť novú šablónu',
	'EXPORT_AS_XML' 		=> 'Exportovať ako XML',
	'NEW_STYLES_EXP' 		=> 'Nahrať šablónu zo XML súboru',
	'NEW_STYLE_ADDED' 		=> 'Šablóna bola úspešné pridaná',
	
	'ERR_IN_UPLOAD_XML_FILE' 		=> '(ERR:XML) Chyba nahrávania..',
	'ERR_UPLOAD_XML_FILE_NO_TMP' 	=> '(ERR:NOTMP) Chyba nahrávania..',
	'ERR_UPLOAD_XML_NO_CONTENT' 	=> 'Vybraný súbor je prázdny!',
	'ERR_XML_NO_G_TAGS' 			=> 'Niektoré požadované značky (tagy) v súbore chýbajú!',
	'STYLE_DELETED' 				=> 'Šablóna bola úspešné odstránená',
	'STYLE_1_NOT_FOR_DEL' 			=> 'Nemôžete odstrániť predvolenú šablónu!',
	'ADD_NEW_TPL' 					=> 'Vložiť novu šablónu',
	'ADD_NEW_TPL_EXP' 				=> 'Zadajte názov novej šablóny',
	'TPL_CREATED' 					=> 'Nová šablóna bola úspešné vytvorená..',
	'R_LANGS' 						=> 'Slová a frázy',
	'WORDS_UPDATED' 				=> 'Slová a frázy boli úspešné aktualizovane..',
	'R_PLUGINS' 					=> 'Rozšírenia',
	'PLUGINS_EX' 				=> 'Tu môžete vymazať alebo aktualizovať rozšírenia..',
	'ADD_NEW_PLUGIN' 			=> 'Vložiť rozšírenie',
	'ADD_NEW_PLUGIN_EXP' 		=> 'Nahrať rozšírenie zo XML súboru',
	'PLUGIN_DELETED' 			=> 'Rozšírenie vymazané..',
	'PLGUIN_DISABLED_ENABLED' 	=> 'Rozšírenie zapnúť / vypnúť',
	'NO_PLUGINS' 				=> 'Neboli nájdené žiadne rozšírenia',
	'NEW_PLUGIN_ADDED' 			=> 'Rozšírenie vložené ... <br /> Upozornenie: Niektoré rozšírenia potrebujú pre svoju funkčnosť ďalšie súbory, tie je potrebné nahrať do hlavného priečinka Kleeja.',
	'PLUGIN_EXISTS_BEFORE' 		=> 'Toto rozšírenie už existuje v rovnakej, alebo vyššej verzií. Nie je potrebné ho aktualizovať!',
	'PLUGIN_UPDATED_SUCCESS' 	=> 'Toto rozšírenie bolo úspešné aktualizované..',
	'R_CHECK_UPDATE' 			=> 'Skontrolovať aktualizácie',
	'ERROR_CHECK_VER' 			=> 'Chyba: V tejto chvíli nemožno získať žiadne informácie o aktualizáciách, skúste to neskôr prosím!',
	'UPDATE_KLJ_NOW' 			=> 'Musíte aktualizovať svoju verziu! Navštívte Kleeja.com pre viac informácii.',
	'U_LAST_VER_KLJ' 			=> 'Používate najnovšiu verziu Kleeja..',
	'U_USE_PRE_RE' 				=> 'Používate beta verziu, Kliknite <a href="http://www.kleeja.com/bugs/">sem</a> a nahláste akékoľvek chyby alebo zneužitie.',
	'STYLE_IS_DEFAULT'			=> 'Predvolená šablóna',
	'MAKE_AS_DEFAULT'			=> 'Nastaviť ako predvolenú',
	'TPLS_RE_BASIC'				=>	'Základné šablóny', 
	'TPLS_RE_MSG'				=>	'Oznámenie šablóny', 
	'TPLS_RE_USER'				=>	'Užívateľská šablóna', 
	'TPLS_RE_OTHER'				=>	'Ostatné šablóny',
	'STYLE_NOW_IS_DEFAULT' 		=> 'Šablóna "%s" bola nastavená ako predvolená',
	'STYLE_DIR_NOT_WR'			=>	'Zložka so štýlmi %s nemôže byt editovaná, pokiaľ nenastavíte atribúty CHMOD na 777.',
	'TPL_PATH_NOT_FOUND' 		=> 'Šablóna %s nebola nájdená !',
	'NO_CACHED_STYLES'			=> 'Šablóna nie je v súčasnosti v pamäti Cache!',
	'SEARCH_FOR'				=> 'Hľadať',
	'REPLACE_WITH'				=> 'Nahradiť za',
	'REPLACE_TO_REACH'			=> 'Kým sa nedostanete na ďalší kód',
	'ADD_AFTER'					=> 'Vložiť potom',
	'ADD_AFTER_SAME_LINE'		=> 'Pridať za ten istý riadok',
	'ADD_BEFORE'				=> 'Pridať pred',
	'ADD_BEFORE_SAME_LINE'		=> 'Pridať pred ten istý riadok',
	'ADD_IN'					=> 'Prejsť doňho po vytvorení',
	'CACHED_STYLES_DELETED'		=>'Šablóna z pamäte Cache vymazaná.',
	'CACHED_STYLES'				=>' Cache šablóny',
	'DELETE_CACHED_STYLES'		=>'Vymazať cache šablóny',
	'CACHED_STYLES_DISC'		=>	'Šablóny sú uložené, zostávajúce úpravy príloh neboli pridané buď z dôvodu oprávnenia alebo nedostatku vhodných vyhľadávacích slov, preto musia byť inštalované ručne %s .',
	'UPDATE_NOW_S'				=>	'Používate starú verziu Kleeja. Preto by ste ju mali aktualizovať. Vaša aktuálna verzia je %1$s a posledná aktuálna je %2$s',
	'ADD_NEW_EXT'				=> 'Vložiť nový typ súboru',
	'ADD_NEW_EXT_EXP'			=> 'Zadajte typ a zvoľte kategóriu',
	'EMPTY_EXT_FIELD'			=>	'Musíte zadať typ súboru!', 
	'NEW_EXT_ADD'				=>	'Nový typ súboru bol pridaný.',
	'NEW_EXT_EXISTS_B4'			=>	'Typ súboru %s už existuje!',
	'NOT_SAFE_FILE'				=> 'Súbor "%s" nevyzerá bezpečné!',
	'CONFIG_WRITEABLE'			=> 'Súbor config.php je v súčasnej dobe zapisovateľný, dôrazne odporúčame aby boli zmenené atribúty CHMOD na 640 alebo aspoň 644.',
	'NO_KLEEJA_COPYRIGHTS'		=> 'Zdá sa, že ste náhodne odstránili autorské práva z päty, prosím dajte ich späť. Len tak budete aj naďalej môcť používať Kleeja bez poplatku, môže si tiež zakúpiť autorské práva a následné ich odstrániť %s .',
	'USERS_NOT_NORMAL_SYS'		=> 'Aktuálny užívateľský systém nie je normálny, čo znamená že súčasný užívatelia nemôžu byt upravovaný odtiaľto, ale skriptom ktorý bol integrovaný s Kleeja. Títo užívatelia používajú normálne členstvo v systéme.',
	'DIMENSIONS_THMB'			=> 'Rozmery miniatúry',
	'ADMIN_DELETE_FILE_ERR'		=> 'Nastala chyba pri pokuse o zmazanie užívateľských súborov. ',
	'ADMIN_DELETE_FILE_OK'		=> 'Hotovo! ',
	'ADMIN_DELETE_FILES'		=> 'Odstrániť všetky užívateľské súbory',
	
	'KLJ_MORE_PLUGINS'			=> array('Chcete získať viac rozšírení z Kleeja centra rozšírení? <a target="_blank" href="http://www.kleeja.com/plugins/">Kliknite sem</a> .',
								'Ste vývojár? Vyvinuli ste nejaké rozšírenie pre Kleeja a chcete ho zverejniť v Kleeja centre rozšírení? <a target="_blank" href="http://www.kleeja.com/plugins/">Kliknite sem</a>. ',
								),
	'KLJ_MORE_STYLES'			=> array('Ak chcete získať viac šablón z Kleeja galérie ,<a target="_blank" href="http://www.kleeja.com/styles/">Kliknite sem</a> .',
								'Ste dizajnér? Chcete predviesť svoju šablónu v Kleeja galérii pre každého? <a target="_blank" href="http://www.kleeja.com/styles/">Kliknite sem</a> .',
								),
	'BCONVERTER' 				=> 'Prevod veľkosti dát',
	'NO_HTACCESS_DIR_UP'		=> 'Nebol nájdený .htaccess súbor v zložke "%s" ! Čo znamená že Vaša web stránka môže byť infikovaná a napadnutá hackermi!',
	'NO_HTACCESS_DIR_UP_THUMB'	=> 'Nebol nájdený .htaccess súbor v zložke pre miniatúry "%s" ! Čo znamená že Vaša web stránka môže byť infikovaná a napadnutá hackermi!',
	'COOKIE_DOMAIN' 			=> 'Cookies doména',
	'COOKIE_NAME' 				=> 'Cookies predpona',
	'COOKIE_PATH' 				=> 'Cookies path',
	'COOKIE_SECURE'				=> 'Zabezpečiť cookies',
	'ADMINISTRATORS'			=> 'Administrátori',
	'DELETEALLRES'				=> 'Vymazať všetky výsledky',
	'ADMIN_DELETE_FILES_OK'     => 'Súbor %s bol úspešné vymazaný',
	'ADMIN_DELETE_FILES_NOF'	=> 'Žiadne súbory na vymazanie',
	'NOT_EXSIT_USER'			=> 'Je nám ľúto, užívateľ ktorého hľadáte neexistuje v našej databáze.. Možno sa snažíte nájsť už zmazaného užívateľa!',
	'ADMIN_DELETE_NO_FILE'		=> 'Tento užívateľ nemá žiadne súbory na zmazanie!',
	'CONFIG_KLJ_MENUS_OTHER'	=> 'Ostatné nastavenia',
	'CONFIG_KLJ_MENUS_GENERAL'	=> 'Hlavné nastavenia',
	'CONFIG_KLJ_MENUS_ALL'		=> 'Zobraziť všetky nastavenie',
	'CONFIG_KLJ_MENUS_UPLOAD'	=> 'Nastavenia nahrávania',
	'CONFIG_KLJ_MENUS_INTERFACE'=> 'Rozhrania a dizajn nastavenia',
	'CONFIG_KLJ_MENUS_ADVANCED' => 'Pokročilé nastavenia',
	'DELF_CAUTION'				=> '<span class="delf_caution">Pozor: Táto funkcia môže byť nebezpečná pri použití malého čísla.</span>',
	'PLUGIN_N_CMPT_KLJ'			=> 'Toto rozšírenie nie je kompatibilné s aktuálnou verziou Kleeja.',
	'PHPINI_FILESIZE_SMALL'		=> 'Maximálna veľkosť súboru povolená pre Vašu službu je "%1$s" zatiaľ čo upload_max_filesize na Vašom servery je nastavený na "%2$s" !',
	'PHPINI_MPOSTSIZE_SMALL'	=> 'Máte možnosť nahrávania "%1$s" súborov naraz, musíte použiť väčšiu hodnotu pre post_max_size na Vašom serveri, niečo ako "%2$s" pre lepší výkon.',
	'NUMPER_REPORT' 			=> 'Počet hlásení',
	'NO_UP_CHANGE_S'			=> 'Žiadne zmeny..',
	'ADD_HEADER_EXTRA' 			=> 'Zvlášť hlavička',
	'ADD_FOOTER_EXTRA' 			=> 'Zvlášť päta',
	'ADMIN_USING_IE6'			=> 'Používate Internet Explorer 6, aktualizujte prosím Váš prehliadač alebo použite Mozilla Firefox!',
	'FOOTER_TXTS'				=> array('PLUGINS'=> 'Rozšírenia', 'STYLES'=>'Šablóny', 'BUGS'=>'Hlásenie chýb'),
	'T_ISNT_WRITEABLE'			=> 'Nemôžete upravovať <strong>%s</strong> šablónu. (Nezapisovateľná)',
	'T_CLEANING_FILES_NOW'		=> 'Odstránenie dočasných súborov môže trvať dlhšiu dobu v závislosti na veľkosti súborov.',
	'HOW_UPDATE_KLEEJA'			=> 'Ako aktualizovať Kleeja?',
	'HOW_UPDATE_KLEEJA_STEP1'	=> 'Navštívte oficiálnu web stránku <a target="_blank" href="http://www.kleeja.com/">Kleeja.com</a> potom prejdite na stránku „Na stiahnutie“ a stiahnite si najnovšiu verziu skriptu, alebo si stiahnite aktualizáciu ak je k dispozícii.',
	'HOW_UPDATE_KLEEJA_STEP2'	=> 'Rozbaľte súbor, nahrajte obsah na svoj server a nahraďte staré súbory novými <b>S výnimkou config.php</b>.',
	'HOW_UPDATE_KLEEJA_STEP3'	=> 'Keď budete hotoví, prejdite na nasledujúcu adresu URL k aktualizácii databázy.',
	'RETURN_TEMPLATE_BK'		=> 'Obnoviť niektorú zálohu šablóny',
	'RETURN_TEMPLATE_BK_EXP'	=> 'Vyberte si niektorú zálohu šablóny pre jej obnovenie, tieto šablóny patria do pôvodneho štýlu Kleeja.',
	'TPL_BK_RETURNED'			=> 'Záložňa kopia obnovená zo šablóny %s.',
	'REPLACE_WHOLW_TPL'			=> 'Nahradiť celú šablónu',
	'DEPEND_ON_NO_STYLE_ERR'	=> 'Tento štýl je založený na "%s" štýle', 
	'PLUGINS_REQ_NO_STYLE_ERR'	=> 'Tento štýl vyžaduje, aby prídavné doplnky / rozšírenia [ %s ] boli nainštalované, nainštalujete ich a skúste to znova!', 
	'PLUGIN_REQ_BY_STYLE_ERR'	=> 'Súčasná predvolená šablóna vyžaduje tento doplnok pre jej správnu funkciu, aby mohla byť odstránená alebo upravená je potrebne najprv zmeniť šablónu.', 
	'KLJ_VER_NO_STYLE_ERR'		=> 'Tato šablóna vyžaduje Kleeja verziu %s alebo vyššiu',
	'KLJ_STYLE_INFO'			=> 'Informácie o šablóne',
	'STYLE_NAME'				=> 'Meno šablóny',
	'STYLE_COPYRIGHT'			=> 'Autorské práva',
	'STYLE_VERSION'				=> 'Verzia šablóny',
	'STYLE_DEPEND_ON'			=> 'Na základe',
	'MESSAGE_NONE'				=> 'Zatiaľ nemáte žiadne nove správy..',
	'KLEEJA_TEAM'				=> 'Kleeja vývojový tým',
	'ERR_SEND_MAIL'				=> 'Chyba pri odosielaní pošty, zopakujte akciu neskôr prosím !',
	'FIND_IP_FILES' 			=> 'Nájdené',
	'ALPHABETICAL_ORDER_FILES'	=> 'Zoradiť súbory podľa abecedy', 
	'ORDER_SIZE'				=> 'Zoradiť súbory podľa veľkosti od najväčšieho k najmenšiemu',
	'ORDER_TOTAL_DOWNLOADS'		=> 'Zoradiť súbory podľa počtu stiahnutí', 
	'COMMA_X'					=> '<p class="live_xts">oddeľujte čiarkou (<font style="font-size:large"> , </font>)</p>',
	'NO_SEARCH_WORD'			=> 'Nezadali ste žiadny typ do vyhľadávacieho formulára!',
	'GUESTSECTOUPLOAD'			=> 'Čas (počet sekúnd) medzi každým nahrávacím procesom zo strany hosťa',
	'USERSECTOUPLOAD'			=> 'Čas (počet sekúnd) medzi každým nahrávacím procesom zo strany užívateľa',
	'ADM_UNWANTED_FILES'		=> 'Zdá sa, že ste urobili aktualizáciu zo staršej verzie. Vzhľadom na rozdiely v názvoch niektorých súborov, vznikli problémy s tlačidlami v ovládacom paneli. <br /> Ak chcete vyriešiť problém, zmažte všetky súbory v priečinku "includes/ADM" a nahrajte ich znova.',
	'ADVANCED_SETTINGS_CATUION' => 'Pozor: Neupravujte tieto nastavenia pokiaľ neviete čo znamenajú!',
	'HTML_URLS_ENABLED_NO_HTCC'	=> 'Na to aby ste mohli používať Mod Rewrite [ HTML odkazy.. ] je potrebne premiestniť súbor .htaccess.txt ktorý nájdete v zložke docs/.htaccess.txt do rootu inštalácie Kleeja (hlavná zložka) a premenovať súbor .htaccess.txt na “.htaccess“. Ak je Vám niečo nejasne kontaktujte technickú podporu Kleeja alebo vypnite Mod Rewrite [ HTML odkazy.. ].',	
	'PLUGIN_WT_FILE_METHOD'		=> 'Niektoré doplnky vyžadujú zmenu súborov alebo pridávať nové súbory, vyberte akým spôsobom chcete prevziať súbory:',
	'PLUGIN_ZIP_FILE_METHOD'	=> 'Dajte nové a zmenené súbory, tiež ich môžete nahrať a nahradiť ručné.',
	'PLUGIN_FTP_FILE_METHOD'	=> 'Použiť metódu FTP.',	
	'PLUGIN_FTP_EXP'			=> 'Môžete meniť súbory bez prístupu na FTP, napíšte prístupové informácie na FTP nižšie, a nainštalujte rozšírenie.',
	'PLUGIN_FTP_HOST'			=> 'FTP server',
	'PLUGIN_FTP_USER'			=> 'Užívateľské meno pre FTP',
	'PLUGIN_FTP_PASS'			=> 'Heslo pre FTP',
	'PLUGIN_FTP_PATH'			=> 'Cesta k FTP',
	'PLUGIN_FTP_PORT'			=> 'Port FTP <small> (často je 21, takže ak ste si nie istí, nechajte ho tak ako je.)</small>',
	'PLUGIN_CONFIRM_ADD'		=> 'Pozor, niektoré obrázky môžu byť škodlivé, takže ak ste si nie istý zdrojom rozšírenia alebo ste ho nestiahli z webu Kleeja.com bude lepšie myslieť najprv na bezpečnosť Vášho webu. Naozaj chcete nainštalovať rozšírenie?',
	'PLUGIN_ADDED_ZIPPED'		=> 'Rozšírenie nainštalované. Na dokončenie procesu inštalácie %2$s je potrebne stiahnuť %1$s upravené súbory a nahradiť ich ručne. Zabudnutie alebo ignorovanie nahradenia súborov môže viesť k poruche rozšírenia.',
	'PLUGIN_ADDED_ZIPPED_INST'	=> 'Rozšírenie nainštalované. Na dokončenie procesu inštalácie %2$s je potrebne stiahnuť %1$s upravené súbory a nahradiť ich ručne. Zabudnutie alebo ignorovanie nahradenia súborov môže viesť k poruche rozšírenia. </ br> Mali by ste si tiež precitať inštrukcie pre viac informácii. Nájdete ich na stránke rozšírení.',
	'PLUGIN_DELETED_ZIPPED'		=> 'Rozšírenie bolo vymazané, na dokončenie procesu mazania 1%$sdownload2%$s je potrebné upraviť súbory a nahradiť ich aktuálnymi súbormi v Kleeja ručné.', 
	'PLUGINS_CHANGES_FILES'		=> 'Modifikované súbory v dôsledku inštalácie rozšírenia.', 
	'PLUGINS_CHANGES_FILES_EXP'	=> 'Tieto komprimované súbory, ktoré obsahujú upravené súbory, zmenili niektoré rozšírenia. Musíte si ich preto stiahnuť a nahradiť ich. Keď budete hotový môžete odstrániť ZIP súbory.',
	'LOADING'					=> 'Loading',
	'ERROR_AJAX'				=> 'There is an error, try again!.',
	'MORE'						=> 'More',
	'MENU'						=> 'Menu',
	'WELCOME'					=> 'Welcome',
	'ENABLE_CAPTCHA'			=> 'Enable Captcha in Kleeja',
));
