<?php
//
// kleeja language
// Kurdish
//By : Kurdhome
//

if (!defined('IN_COMMON'))
	exit;

if (empty($lang) || !is_array($lang))
	$lang = array();



	$lang = array_merge($lang, array(
	'HOME' => 'سه‌ره‌تا',
	'DIR' => 'rtl',
	'INDEX' => 'سه‌ره‌تا',
	'SITE_CLOSED' => 'ناوه‌ند داخراوه‌!',
	'STOP_FOR_SIZE' => 'ئێستا وه‌ستێنراوه‌!',
	'SIZES_EXCCEDED' => 'قه‌باره‌ی هه‌مووه‌کی پڕبووه‌.. به‌زووترین کات ده‌گه‌ڕێینه‌وه‌',
	'ENTER_CODE_IMG' => 'ئه‌وه‌ی له‌وێنه‌که‌دا ده‌یبینیت بینوسه‌ره‌وه',
	'SAFE_CODE' => 'چالاککردنی کۆدی پاراستن له‌کاتی بارکردندا',
	'LAST_VISIT' => 'دوا سه‌ردانت',
	'FLS_LST_VST_SEARCH' => 'نیشاندانی ئه‌و په‌ڕگانه‌ی له‌دوا سه‌ردانته‌وه‌ بارکراون‌',
	'IMG_LST_VST_SEARCH' => 'نیشاندانی ئه‌و وێنانه‌ی له‌دوا سه‌ردانته‌وه‌ بارکراون',
	'NEXT' => 'داهاتوو',
	'PREV' => 'پێشوو',
	'INFORMATION' => 'ڕێنماییه‌کان',
	'WELCOME' => 'به‌خێربێیت',
	'KLEEJA_VERSION' => 'وه‌شانی كليجا',
	'NUMBER_ONLINE' => 'ژماره‌ی ئاماده‌بووان',
	'NUMBER_UONLINE' => 'ئه‌ندام',
	'NUMBER_VONLINE' => 'میوان',
	'USERS_SYSTEM' => 'سیسته‌می به‌کارهێنه‌ران',
	'ERROR_NAVIGATATION' => 'هه‌ڵه‌ له‌ئاڕاسته‌کردندا..',
	
	'LOGIN' => 'چوونه‌ژووره‌وه',
	'USERNAME' => 'ناوی به‌کارهێنه‌ر',
	'PASSWORD' => 'ووشه‌ی تێپه‌ڕبوون',
	'EMPTY_USERNAME' => 'خانه‌ی ناوی به‌کارهێنه‌ر به‌تاڵه',
	'EMPTY_PASSWORD' => 'خانه‌ی ووشه‌ی تێپه‌ڕبوون به‌تاڵه',
	'LOSS_PASSWORD' => 'ووشه‌ی تێپه‌ڕبوونت بیرچووه‌ته‌وه‌؟',
	'LOGINED_BEFORE' => 'تۆ له‌ژووره‌وه‌یت',
	'LOGOUT' => 'چوونه‌ده‌ره‌وه‌',
	'EMPTY_FIELDS' => 'هه‌ڵه‌ ..خانه‌ت بجێهێشتووه‌!',
	'LOGIN_SUCCESFUL' => 'به‌سه‌رکه‌وتووی چوویته‌ ژووره‌وه',
	'LOGIN_ERROR' => 'هه‌ڵه‌ .. ناتوانیت بچیته‌ژووره‌وه!',
	
	'REGISTER_CLOSED' => 'ببوره‌ ..خۆتۆمارکردن ناچالاکه‌ له‌کاتی ئێستادا',
	'PLACE_NO_YOU' => 'ئه‌م په‌ڕگه‌یه‌ ڕێپێنه‌دراوه‌ بۆ تۆ',
	'REGISTERED_BEFORE' => 'پێشتر خۆت تۆمارکردووه‌',
	'REGISTER' => 'خۆتۆمارکردن',
	'EMAIL' => 'ئیمه‌یڵ',
	'VERTY_CODE' => 'کۆدی پاراستن',
	'WRONG_EMAIL' => 'ئیمه‌یڵ هه‌ڵه‌یه‌',
	'WRONG_NAME' => 'ناو ده‌بێت درێژتربێت له‌ 4 پیت و نابێت زۆریش درێژبێت',
	'WRONG_LINK' => 'به‌سته‌ر هه‌ڵه‌یه‌ ..',
	'EXIST_NAME' => 'ئه‌م ناوه‌ پێشتر بوونی هه‌یه',
	'EXIST_EMAIL' => 'ئه‌م ئیمه‌یڵه‌ پێشتر بوونی هه‌یه',
	'WRONG_VERTY_CODE' => 'كۆدی پاراستن هه‌ڵه‌یه',
	'CANT_UPDATE_SQL' => 'ناتوانرێت بنکه‌ی زانیاری نوێبکرێته‌وه‌',
	'CANT_INSERT_SQL' => 'ناتوانرێت زانیاریه‌کانی بنکه‌ی زانیاری وه‌ناوبخرێت',
	'REGISTER_SUCCESFUL' => 'سوپاس بۆ خۆتۆمارکردنت',
	'LOGOUT_SUCCESFUL' => 'به‌سه‌رکه‌وتووی چوویته‌ده‌ره‌وه‌',
	'LOGOUT_ERROR' => 'کێشه‌ هه‌یه‌ له‌چوونه‌ده‌ره‌ووه‌تدا',
	
	'FILECP' => 'به‌ڕێوه‌بردنی په‌ڕگه‌کان',
	'DEL_SELECTED' => 'سڕینه‌وه‌ی دیاریکراو',
	'EDIT_U_FILES' => 'نوێکردنه‌وه‌ی په‌ڕگه‌کانت',
	'FILES_UPDATED' => 'په‌ڕگه‌کان به‌سه‌رکه‌وتووی نوێکرانه‌وه‌',
	'PUBLIC_USER_FILES' => 'بوخچه‌ی په‌ڕگه‌کانی ئه‌ندام ',
	'FILEUSER' => 'بوخچه‌ی په‌ڕگه‌کانی ئه‌ندام',
	'GO_FILECP' => 'کرته‌ لێره‌ بکه‌ بۆ به‌ڕێوه‌بردنی په‌ڕگه‌کانت',
	'YOUR_FILEUSER' => 'بوخچه‌که‌ت',
	'COPY_AND_GET_DUD' => 'ئه‌م به‌سته‌ره‌ کۆپی بکه‌ و بیده‌ره‌ هاوڕێکانت بۆ ئه‌وه‌ی به‌ئاسانی هه‌موو په‌ڕگه‌کانت بدۆزنه‌وه',
	'CLOSED_FEATURE' => 'ئه‌مه‌ ناچالاکه',
	'USERFILE_CLOSED' => 'خاسیه‌تی بوخچه‌ی ئه‌ندامان ناچالاکه‌!',
	
	'PFILE_4_FORUM' => 'بڕۆ بۆ یانه‌ یاخود مه‌کۆ بۆ گۆڕینی زانیاریه‌کانت',
	'USER_PLACE' => 'ناوچه‌ی ئه‌ندامان',
	
	'PROFILE' => 'زانیاریه‌کانت..',
	'EDIT_U_DATA' => 'نوێکردنه‌وه‌ی زانیاریه‌کانت',
	'PASS_ON_CHANGE' => 'ووشه‌ی تێپه‌ڕبوون.. ته‌نها له‌کاتی گۆڕیندا بینوسه‌',
	'OLD' => 'کۆن',
	'NEW' => 'نوێ',
	'NEW_AGAIN' => 'دووباره‌کردنه‌وه‌ی نوێ',
	'UPDATE' => 'نوێکردنه‌وه‌',
	'PASS_O_PASS2' => 'ووشه‌ی تێپه‌ڕبوونی پێشوو گرنگه‌و ووشه‌ی تێپه‌ڕبوونی نوێ دووجار به‌جوانی بنوسه‌ ',
	'DATA_CHANGED_O_LO' => 'زانیاریه‌کانت نوێکرانه‌وه‌ له‌چوونه‌ژووره‌وه‌ی داهاتوو به‌کاری بهێنه‌',
	'DATA_CHANGED_NO' => 'نوێبوونه‌وه‌ ئه‌نجام نه‌درا بۆیه‌ زانیاریه‌کانت ناگۆڕێن',
	'LOST_PASS_FORUM' => 'بڕۆ بۆ یانه‌ یاخود مه‌کۆ بۆ به‌ده‌ستهێنانه‌وه‌ی ووشه‌ی تێپه‌ڕبوون',
	'GET_LOSTPASS' => 'به‌ده‌ستهێنانه‌وه‌ی ووشه‌ی تێپه‌ڕبوون',
	'E_GET_LOSTPASS' => 'بۆ به‌ده‌ست هێنانه‌وه‌ی ووشه‌ی تێپه‌ڕبوون ده‌بێت ئه‌و ئیمه‌یڵه‌ت بنوسیت که‌لامان تۆماره',
	'WRONG_DB_EMAIL' => 'له‌بنکه‌ی زانیاریه‌کانماندا ئه‌م ئیمه‌یڵه‌ بوونی نییه‌',
	'GET_LOSTPASS_MSG' => "لقد قمت بطلب  إستعادة كلمة مرورك , لكن لتجنب السبام قم بالظغط على الرابط التالي لتأكيدها : \r\n %1\$s \r\n كلمة المرور الجديده : %2\$s",
	'CANT_SEND_NEWPASS' => 'هه‌ڵه‌ ..ووشه‌ی تێپه‌ڕبوونی نوێ نه‌نێردرا!',
	'OK_SEND_NEWPASS' => 'ووشه‌ی تێپه‌ڕبوونی نوێ نێردرا..',
	'OK_APPLY_NEWPASS' => 'تم ظبط كلمة المرور الجديده , يمكنك الآن الدخول بها .',
	'GUIDE' => 'په‌ڕگه‌ ڕێپێدراوه‌کان',
	'GUIDE_VISITORS' => 'درێژکراوه‌ی ڕێپێدراو به‌میوانان و قه‌باره‌کانیان:',
	'GUIDE_USERS' => 'درێژکراوه‌ی ڕێپێدراو به‌ئه‌ندامان و قه‌باره‌کانیان:',
	'EXT' => 'درێژکراوه‌',
	'SIZE' => 'قه‌باره‌',
	'REPORT' => 'ڕاپۆرتدان',
	'YOURNAME' => 'ناو',
	'URL' => 'ماڵپه‌ڕ',
	'REASON' => 'هۆکار',
	'NO_ID' => 'په‌ڕگه‌ت دیاری نه‌کردووه‌..!!',
	'NO_ME300RES' => 'تکایه‌ .. خانه‌ی هۆکار نابێت 300 پیت زیاتر له‌خۆی بگرێت!!',
	'THNX_REPORTED' => 'ڕاپۆرت نێردرا .. سوپاس بۆ هاوکاریت',
	'RULES' => 'یاساکان',
	'NO_RULES_NOW' => 'له‌کاتی ئێستادا  هیچ یاسایه‌ک دانه‌نراوه',
	'E_RULES' => 'یاساکانی ناوه‌ندی بارکردن',
	'CALL' => 'په‌یوه‌ندی',
	'SEND' => 'بنێره‌',
	'TEXT' => 'په‌یام',
	'NO_ME300TEXT' => 'تکایه‌ .. خانه‌ی په‌یام نابێت 300 پیت زیاتر له‌خۆی بگرێت!!',
	'THNX_CALLED' => 'په‌یامه‌که‌ت نێردرا. . له‌نزیکترین بواردا وه‌ڵام وه‌رده‌گریت',
	'NO_DEL_F' => 'ببوره. خاسیه‌تی سڕینه‌وه‌ی ڕاسته‌وخۆ له‌لایه‌ن به‌ڕێوه‌به‌ره‌وه‌ له‌کار خراوه‌',
	'E_DEL_F' => 'سڕینه‌وه‌ی ڕاسته‌وخۆ',
	'WRONG_URL' => 'هه‌ڵه‌ .. له‌به‌سته‌ردا ..',
	'CANT_DEL_F' => 'هه‌ڵه‌ ..ناتوانیت په‌ڕگه‌ بسڕیته‌وه‌ .. له‌وانه‌یه‌ زانیاریه‌کانت هه‌ڵه‌بێت یان پێشتر ئه‌و په‌ڕگه‌یه‌ سڕاوه‌ته‌وه‌',
	'CANT_DELETE_SQL' => 'ناتوانرێت له‌ بنکه‌ی زانیاریه‌کان بسڕێته‌وه‌',
	'DELETE_SUCCESFUL' => 'به‌سه‌رکه‌وتووی سڕایه‌وه‌',
	'STATS' => 'ئاماره‌کانی ناوه‌ند',
	'STATS_CLOSED' => 'لاپه‌ڕه‌ی ئاماره‌کان ناچالاککراوه‌ له‌لایه‌ن به‌ڕێوه‌به‌ره‌وه‌!',
	'FILES_ST' => 'بارکراوه',
	'FILE' => 'په‌ڕگه‌',
	'USERS_ST' => 'ژماره‌ی ئه‌ندامان',
	'USER' => 'ئه‌ندام',
	'SIZES_ST' => 'قه‌باره‌ی هه‌موو په‌ڕگه‌کان',
	'LSTFLE_ST' => 'دوا په‌ڕگه‌ی بارکراو',
	'LSTDELST' => 'دوا پشکنینی په‌ڕگه‌ دانه‌گیراوه‌کان',
	'S_C_T' => 'میوانانی ئه‌مڕۆ',
	'S_C_Y' => 'میوانانی دوێنێ',

	'S_C_A' => 'ژماره‌ی هه‌موو میوانان',
	'LAST_1_H' => 'ئه‌م ئاماره‌ ئاماری به‌ر له‌کاتژمێرێکه‌',
	'DOWNLAOD' => 'داگرتن',
	'FILE_FOUNDED' => 'په‌ڕگه‌ دۆزرایه‌وه‌..',
	'WAIT' => 'تکایه‌ چاوه‌ڕوانبه‌..',
	'CLICK_DOWN' => 'کرته‌ لێره‌ بکه‌ بۆ داگرتنی په‌گه‌',
	'JS_MUST_ON' => 'ده‌بێت جاڤا سکریپت چالاک بکه‌یت له‌وێبگه‌ڕه‌که‌ت!!',
	'FILE_INFO' => 'زانیاریه‌کانی په‌ڕگه',
	'FILENAME' => 'ناوی په‌ڕگه‌',
	'FILESIZE' => 'قه‌باره‌ی په‌ڕگه‌',
	'FILETYPE' => 'جۆری په‌ڕگه‌',
	'FILEDATE' => 'ڕێکه‌وتی بارکردن‌',
	'FILEUPS' => 'جار داگیراوه‌',
	'FILEREPORT' => 'ڕاپۆرت: په‌ڕگه‌ مه‌رجه‌کانی یاسای تێدا نییه‌',
	'FILE_NO_FOUNDED' => 'په‌ڕگه‌ نه‌دۆزرایه‌وه‌..!!',
	'IMG_NO_FOUNDED' => 'وێنه‌ نه‌دۆزرایه‌وه‌..!!',
	'NOT_IMG' => 'ئه‌م په‌ڕگه‌یه‌.. وێنه‌ نییه‌!!',
	'MORE_F_FILES' => 'ئه‌مه‌ دوائاسته‌ که‌بتوانیت باری بکه‌یت له‌یه‌ک کاتدا',
	'DOWNLOAD_F' => '[ بارکردن ]',
	'DOWNLOAD_T' => '[ بارکردن له‌به‌سته‌ره‌وه‌ ]',
	'PAST_URL_HERE' => '[ به‌سته‌ره‌که‌ لێره‌ دابنێ ]',
	'SAME_FILE_EXIST' => 'ئه‌م په‌ڕگه‌یه‌ پێشتر بوونی نییه‌',
	'NO_FILE_SELECTED' => 'په‌ڕگه‌ت دیاری نه‌کردووه‌!!',
	'WRONG_F_NAME' => 'ناوی په‌ڕگه‌که‌ پیتی ڕێپێنه‌دراوی تێدایه',
	'FORBID_EXT' => 'ئه‌م درێژکراوه‌یه‌ ڕێپێنه‌دراوه',
	'SIZE_F_BIG' => 'قه‌باره‌ی په‌ڕگه‌ ده‌بێت که‌متربێت له',
	'CANT_CON_FTP' => 'ناتوانرێت په‌یوه‌ندی بکرێت به‌',
	'URL_F_DEL' => 'به‌سته‌ری سڕینه‌وه‌',
	'URL_F_THMB' => 'به‌سته‌ری بچوککراوه‌',
	'URL_F_FILE' => 'به‌سته‌ری په‌ڕگه‌',
	'URL_F_IMG' => 'به‌سته‌ری وێنه‌',
	'URL_F_BBC' => 'به‌سته‌ر بۆ یانه‌ و مه‌کۆکان',
	'IMG_DOWNLAODED' => 'وێنه‌ به‌سه‌رکه‌وتووی بارکرا',
	'FILE_DOWNLAODED' => 'په‌ڕگه‌ به‌سه‌رکه‌وتووی بارکرا',
	'CANT_UPLAOD' => 'هه‌ڵه‌... به‌هۆی هۆکارێکی نه‌زانراوه‌وه‌ په‌ڕگه‌ بارنه‌کرا',
	'NEW_DIR_CRT' => 'بوخچه‌یه‌کی نوێ دروستکرا',
	'PR_DIR_CRT' => 'ڕێدان نه‌درا به‌بوخچه‌',

	'CANT_DIR_CRT' => 'بوخچه‌ خۆکاری دروستنه‌کرا.. خۆت دروستی بکه‌',
	'AGREE_RULES' => 'ڕه‌زامه‌ندم له‌سه‌ر یاساکانی ناوه‌ند',
	'CHANG_TO_URL_FILE' => 'گۆڕینی شێوازی بارکردن.. به‌سته‌ر یان وه‌ناوخستن',

	'URL_CANT_GET' => 'هه‌ڵه‌ له‌هێنانی په‌ڕگه‌ له‌به‌سته‌ره‌وه‌',
	'ADMINCP' => 'کۆنترۆڵ په‌نێڵ',
	'JUMPTO' => 'بڕۆ بۆ',
	'GO_BACK_BROWSER' => 'گه‌ڕانه‌وه‌',
	'U_R_BANNED' => 'ئه‌م ئایپییه‌ ڕێپێنه‌دراوه‌..',
	'U_R_FLOODER' => 'له‌ئاستی دیاریکراو زیاتر لاپه‌ڕه‌که‌ت هه‌ڵداوه‌ته‌وه‌ له‌کاتێکی دیاریکراودا...',
	'U_NOT_ADMIN' => 'ده‌بێت مافه‌کانی به‌ڕێوه‌به‌رت هه‌بێت',
	'UPDATE_CONFIG' => 'نوێکردنه‌وه‌ی هه‌ڵبژاردنه‌کان',
	'YES' => 'به‌ڵێ',
	'NO' => 'نه‌خێر',
	'NO_CHANGE' => 'به‌بێ گۆڕین',	'CHANGE_MD5' => 'گۆڕین له‌گه‌ڵ MD5',
	'CHANGE_TIME' => 'گۆڕین له‌گه‌ڵ TIME',
	'SITENAME' => 'ناوی ناوه‌ند',
	'SITEMAIL' => 'ئیمه‌یڵی ناوه‌ند',
	'SITEURL' => 'به‌سته‌ری ناوه‌ند(له‌گه‌ڵ /)',
	'FOLDERNAME' => 'ناوی بوخچه‌ی بارکردن',
	'PREFIXNAME' => 'سه‌ره‌تای ناوی په‌ڕگه‌کان',
	'FILESNUM' => 'ژماره‌ی خانه‌کانی بارکردن',
	'SITECLOSE' => 'داخستنی ناوه‌ند',
	'CLOSEMSG' => 'په‌یامی داخستن',
	'LANGUAGE' => 'زمان',
	'DECODE' => 'گۆڕینی ناوی په‌ڕگه‌',
	'STYLE' => 'ڕوخساری ناوه‌ند',
	'SEC_DOWN' => 'چرکه‌ چاوه‌ڕێ کردن پێش داگرتن',
	'STATFOOTER' => 'نیشاندانی ئاماره‌کان له‌فوته‌ردا',
	'GZIP' => 'gzip',
	'GOOGLEANALYTICS' => '<a href="http://www.google.com/analytics" target="_kleeja"><span style="color:orange">Google</span> Analytics</a>',
	'WELCOME_MSG' => 'په‌یامی به‌خێرهاتن',
	'USER_SYSTEM' => 'سیسته‌می ئه‌ندامێتی',
	'NORMAL' => 'ئاسای',
	'W_PHPBB' => 'به‌ستراوه‌ به‌ phpbb',
	'W_MYSBB' => 'به‌ستراو به‌ MySmartBB',
	'W_VBB' => 'به‌ستراو به‌ vb',
	'ENAB_REG' => 'کردنه‌وه‌ی تۆماربوون',
	'TOTAL_SIZE' => 'دوا ئاستی هه‌مووه‌کی به‌میگا',
	'THUMBS_IMGS' => 'چالاککردنی بچوکراوه‌ی وێنه‌',
	'WRITE_IMGS' => 'چالاککردنی مۆری وێنه‌',
	'ID_FORM' => 'Id form',
	'IDF' => 'File id in database',
	'IDFF' => 'File name',
	'IDFD' => 'Directly',
	'DEL_URL_FILE' => 'چالاککردنی به‌سته‌ری سڕینه‌وه‌ی ڕاسته‌وخۆ',
	'WWW_URL' => 'چالاککردنی بارکردن له‌به‌سته‌ره‌وه‌',
	'ALLOW_STAT_PG' => 'چالاککردنی لاپه‌ڕه‌ی ئاماره‌کان',
	'ALLOW_ONLINE' => 'چالاککردنی نیشاندانی ئاماده‌بووانی ئێستا',
	'DEL_F_DAY' => 'سڕینه‌وه‌ی په‌ڕگه‌ دانه‌گیراوه‌کان پاش ئه‌وه‌نه‌ ڕۆژ',
	'MOD_WRITER' => 'Mod Rewrite',
	'MOD_WRITER_EX' => 'به‌سته‌ره‌کان وه‌ک هتمل..',
	'NUMFIELD_S' => 'تکایه‌ .. خانه‌ی ژماره‌یی .. ده‌بێت ژماره‌یی بێت!!',
	'CONFIGS_UPDATED' => 'هه‌ڵبژاردنه‌کان به‌سه‌رکه‌وتووی نوێکرانه‌وه‌',
	'UPDATE_EXTS' => 'نوێکردنه‌وه‌ی درێژکراوه‌کان',
	'GROUP' => 'گروپ',
	'SIZE_G' => 'قه‌باره‌ [م]',
	'SIZE_U' => 'قه‌باره‌ [ئ]',
	'ALLOW_G' => 'ڕێدان [م]',
	'ALLOW_U' => 'ڕێدان [ئ]',
	'E_EXTS' => '<b>م</b>:واتا میوان <br /> <b>ئ</b>: واتا ئه‌ندام  <br />قه‌باره‌کان به‌کیلۆبایت دیاریکراوه.',
	'UPDATED_EXTS' => 'درێژکراوه‌کان به‌سه‌رکه‌وتووی نوێکرانه‌وه‌',
	'UPDATE_FILES' => 'نوێکردنه‌وه‌ی په‌ڕگه‌کان',
	'BY' => 'له‌لایه‌ن',
	'FILDER' => 'بوخچه‌',
	'DELETE' => 'سڕینه‌وه‌',
	'GUST' => 'میوان',
	'UPDATE_REPORTS' => 'نوێکردنه‌وه‌ی ڕاپۆرته‌کان',
	'NAME' => 'ناو',
	'CLICKHERE' => 'کرته‌ لێره‌بکه‌',
	'TIME' => 'کات',
	'E_CLICK' => 'کرته‌ له‌یه‌کێکیان بکه‌ بۆ ئه‌وه‌ی لێره‌دا نیشان بدرێت!',
	'IP' => 'ئایپی',
	'REPLY' => '[ وه‌ڵامدانه‌وه‌ ]',
	'REPLY_REPORT' => 'وه‌ڵامدانه‌وه‌ی ڕاپۆرت',
	'U_REPORT_ON' => 'به‌هۆی ڕاپۆرتدانت له‌ ',

	'BY_EMAIL' => 'له‌ڕێگه‌ی ئیمه‌یڵی ',
	'ADMIN_REPLIED' => 'به‌ڕێوه‌به‌ر ئه‌م وه‌ڵامه‌ی دایته‌وه‌',
	'CANT_SEND_MAIL' => 'ناتوانرێت وه‌ڵامی ئیمه‌یڵ بدرێته‌وه‌',
	'IS_SEND_MAIL' => 'په‌یام به‌ئیمه‌یڵ نێردرا',
	'REPORTS_UPDATED' => 'ڕاپۆرته‌کان نوێکرانه‌وه‌',
	'UPDATE_CALSS' => 'نوێکردنه‌وه‌ی په‌یامه‌کان',

	'REPLY_CALL' => 'وه‌ڵامدانه‌وه‌ی په‌یام',
	'REPLIED_ON_CAL' => 'سه‌باره‌ت به‌په‌یامه‌که‌ت ',
	'CALLS_UPDATED' => 'په‌یامه‌کان نوێکرانه‌وه‌',
	'IS_ADMIN' => 'به‌ڕێوه‌به‌ر',
	'UPDATE_USERS' => 'نوێکردنه‌وه‌ی به‌کارهێنه‌ران',
	'USERS_UPDATED' => 'به‌کارهێنه‌ران نوێکرانه‌وه‌',
	'E_BACKUP' => 'ئه‌و خشتانه‌ ده‌ستنیشان بکه‌ که‌ده‌ته‌وێت کۆپیه‌کیان لێ هه‌ڵگریت و کرته‌ له‌داگرتن بکه‌',
	'TAKE_BK' => 'داگرتن',
	'REPAIRE_TABLE' => '[خشته‌] نوێکرایه‌وه‌ ',
	'REPAIRE_F_STAT' => '[ئاماره‌کان] ژماره‌ی په‌ڕگه‌کان ژمێردرانه‌وه‌',
	'REPAIRE_S_STAT' => '[ئاماره‌کان] قه‌باره‌ی په‌ڕگه‌کان ژمێردرانه‌وه‌ ',
	'REPAIRE_CACHE' => '[كاش] سڕایه‌وه‌  ..',
	'KLEEJA_CP' => 'کۆنترۆڵ په‌نێڵ [ كليجا ]',
	'GENERAL_STAT' => 'ئاماره‌ گشتیه‌کان',
	'SIZE_STAT' => 'ئاماره‌کانی قه‌باره‌ی به‌کارهاتوو‌',
	'OTHER_INFO' => 'زانیاری تر',
	'AFILES_NUM' => 'ژماره‌ی هه‌موو په‌ڕگه‌کان',
	'AFILES_SIZE' => 'قه‌باره‌ی هه‌موو په‌ڕگه‌کان',
	'AUSERS_NUM' => 'ژماره‌ی ئه‌ندامان',
	'LAST_GOOGLE' => 'دوا سه‌ردانی گۆگڵ',
	'GOOGLE_NUM' => 'سه‌ردانه‌کانی گۆگڵ',
	'LAST_YAHOO' => 'دوا سه‌ردانی یاهوو',
	'YAHOO_NUM' => 'سه‌ردانه‌کانی یاهوو',
	'KLEEJA_CP_W' => 'به‌خێرهاتیت بۆ کۆنترۆڵ په‌نێڵی به‌ڕێوه‌به‌رایه‌تی',
	'USING_SIZE' => 'قه‌باره‌ی به‌کارهاتوو',
	'PHP_VER' => 'وه‌شانی php',
	'MYSQL_VER' => 'وه‌شانی mysql',
	'N_IMGS' => 'وێنه‌',
	'N_ZIPS' => 'په‌ڕگه‌کانی په‌ستاندن',
	'N_TXTS' => 'په‌ڕگه‌کانی نوسین',
	'N_DOCS' => 'دۆکومێنته‌کان',
	'N_RM' => 'RealMedia',
	'N_WM' => 'WindowsMedia',
	'N_SWF' => 'په‌ڕگه‌کانی فلاش',
	'N_QT' => 'QuickTime',
	'N_OTHERFILE' => 'په‌ڕگه‌کانی تر',
	'LOGOUT_CP_OK' => 'له‌م چوونه‌ژووره‌وه‌یه‌تدا مافه‌کانی به‌ڕێوه‌به‌رت نه‌ماو مافه‌کانی ترت ماوه‌..',
	'RETURN_HOME' => '&lt;&lt;  گه‌ڕانه‌وه‌ بۆ ناوه‌ند',
	'R_CONFIGS' => 'ڕێکخستنه‌کانی ناوه‌ند',
	'R_CPINDEX' => 'سه‌ره‌تای کۆنترۆڵ پانێڵ',
	'R_EXTS' => 'ڕێکخستنی درێژکراوه‌کان',
	'R_FILES' => 'به‌ڕێوه‌بردنی په‌ڕگه‌کان',
	'R_REPORTS' => 'به‌ڕێوه‌بردنی ڕاپۆرته‌کان',
	'R_CALLS' => 'په‌یامه‌ هاتووه‌کان',
	'R_USERS' => 'به‌ڕێوه‌بردنی به‌کارهێنه‌ران',
	'R_BCKUP' => 'یه‌ده‌ککردنی بنکه‌ی زانیاری',
	'R_REPAIR' => 'چاککردنی هه‌مووه‌کی',
	'R_LGOUTCP' => 'سڕینه‌وه‌ی مافه‌کانی به‌ڕێوه‌به‌ر',
	'R_BAN' => 'ڕاگرتنی ئایپی',
	'BAN_EXP1' => 'لێره‌ ئایپیه‌ ڕاگیراوه‌کان ده‌ستکاری بکه‌ یاخود ڕێ له‌ئایپی نوێ بگره‌‌..',
	'BAN_EXP2' => 'ئه‌ستێره‌ به‌کاربێنه‌ (*)بۆ گۆڕینی ژماره‌کان.. ئه‌گه‌ر ڕاگرتنی ته‌واوه‌تیت ده‌وێت ..وه‌ نیشانه‌ی (|) بۆ جیاکردنه‌وه‌ی ئایپیه‌کان',
	'UPDATE_BAN' => 'پاشه‌که‌وتکردنی گۆڕانکاریه‌کانی ڕاگرتن',
	'BAN_UPDATED' => 'زانیاریه‌کانی ڕاگرتن نوێکرانه‌وه‌ ..',
	'R_RULES' => 'به‌ڕێوه‌بردنی یاساکان',
	'RULES_EXP' => 'لێره‌ ده‌توانیت ده‌ستکاری ئه‌و یاسایانه‌ بکه‌یت که‌ ئه‌ندامان و میوانان ده‌یبین و ده‌بێت له‌کاتی بارکردندا ڕه‌چاوی بکه‌ن',
	'UPDATE_RULES' => 'نوێکردنه‌وه‌ی یاساکان',
	'RULES_UPDATED' => 'یاساکان به‌سه‌رکه‌وتووی نوێکرانه‌وه‌..',
	'R_SEARCH' => 'گه‌ڕانی پێشکه‌وتوو',
	'SEARCH_FILES' => 'گه‌ڕان به‌دوای په‌ڕگه‌دا',
	'SEARCH_SUBMIT' => 'بگه‌ڕێ',
	'LAST_DOWN' => 'دوا داگرتن',
	'TODAY' => 'ئه‌مڕۆ',
	'DAYS' => 'ڕۆژ',
	'WAS_B4' => 'پێش',
	'BITE' => 'بايت',
	'SEARCH_USERS' => 'گه‌ڕان به‌دوای ئه‌نداماندا',
	'R_IMG_CTRL' => 'به‌ڕێوه‌بردنی وێنه‌کان‌',
	'ENABLE_USERFILE' => 'چالاککردنی بوخچه‌ی ئه‌ندامان',
	'R_EXTRA' => 'هيده‌ر وفوته‌ری زیاده‌',
	'EX_HEADER_N' => 'هیده‌ری زیاده‌.. هیده‌رێکی زیاده‌یه‌ و له‌ژێر هیده‌ره‌ سه‌ره‌کیه‌که‌ ده‌رده‌که‌وێت..',
	'EX_FOOTER_N' => 'فوته‌ری زیاده‌.. فوته‌رێکی زیاده‌یه‌ و له‌سه‌ر فوته‌ره‌ سه‌ره‌کیه‌که‌ ده‌رده‌که‌وێت..',
	'UPDATE_EXTRA' => 'نوێکردنه‌وه‌ی گۆڕانکاریه‌کان',
	'EXTRA_UPDATED' => 'گۆڕانکاریه‌کان نوێکرانه‌وه‌',
	'R_STYLES' => 'ڕووخساره‌کان',
	'STYLES_EXP' => 'بۆچاککردن و سڕینه‌وه‌ی قاڵبه‌کان له‌خواره‌وه‌ دیاری بکه‌',
	'SHOW_TPLS' => 'خستنه‌ڕووی قاڵبه‌کان',
	'SUBMIT' => 'به‌رده‌وامبه‌',
	'EDIT' => 'چاککردن',
	'TPL_UPDATED' => 'قاڵب نوێکرایه‌وه‌',
	'TPL_DELETED' => 'قاڵب سڕایه‌وه‌',
	'NO_TPL_SHOOSED' => 'هیچ قاڵبێکت دیارینه‌کردووه‌!!!',
	'NO_TPL_NAME_WROTE' => 'ناوی قاڵبه‌که‌ت نه‌نوسیووه‌!!!',
	'ADD_NEW_STYLE' => 'دروستکردنی ڕووخسارێکی نوێ',

	'EXPORT_AS_XML' => 'پاشه‌که‌وتکردن وه‌ک xml',
	'NEW_STYLES_EXP' => 'هێنانی ڕووخساری نوێ به‌په‌ڕگه‌ی xml',
	'NEW_STYLE_ADDED' => 'ڕووخسار به‌سه‌رکه‌وتووی زیادکرا ',
	'ERR_IN_UPLOAD_XML_FILE' => 'هه‌ڵه‌ هه‌یه‌ له‌بارکردنی په‌ڕگه‌!',
	'ERR_UPLOAD_XML_FILE_NO_TMP' => 'هه‌ڵه‌ هه‌یه‌ له‌بارکردنی په‌ڕگه‌!',
	'ERR_UPLOAD_XML_NO_CONTENT' => 'په‌ڕگه‌ ناوه‌ڕۆکی تێدا نییه‌!',
	'ERR_XML_NO_G_TAGS' => 'په‌ڕگه‌ هه‌ندێک تاگی گرنگی تێدا نییه‌',
	'STYLE_DELETED' => 'ڕووخسار به‌سه‌رکه‌وتووی سڕایه‌وه‌',
	'STYLE_1_NOT_FOR_DEL' => 'ناتوانیت ڕووخساری سه‌ره‌کی بسڕیته‌وه‌',
	'ADD_NEW_TPL' => 'زیادکردنی قاڵبێکی نوێ',
	'ADD_NEW_TPL_EXP' => 'ناوی قاڵبه‌ نوێیه‌که‌ بنوسه‌...',
	'TPL_CREATED' => 'قاڵبی نوێ دروستکرا...',
	
	'R_LANGS' => 'ووشه‌ و ئامڕازه‌کان',
	'WORDS_UPDATED' => 'ووشه‌ نوێکرایه‌وه‌...',
	//deprecated, removed from rc6+
	// [ - ] 'LANGS_EXP' => 'بۆ ده‌ستکاریکردنی زمان و سڕینه‌وه‌ی له‌خواره‌وه‌ ده‌ستکاری بکه',
	// [ - ] 'SHOW_WORDS' => 'نیشاندانی ووشه‌کان',
	// [ - ] 'ADD_NEW_LANG' => 'زیادکردنی زمانێکی نوێ',
	// [ - ] 'NEW_LANG_EXP' => 'مانه‌ نوێیه‌که‌ یان ده‌ستکاری کراوه‌که‌ باربکه',
	// [ - ] 'SHOW_WORDS_EXP' => 'ووشه‌ سه‌رکی و ماناکه‌ی دیار بکه‌ و ده‌ستکاریبکه‌ یاخود بیسڕه‌وه‌..',
	// [ - ] 'ADD_NEW_WORD' => 'ووشه‌ زیاد بکه‌',
	// [ - ] 'ADD_NEW_WORD_EXP' => 'ووشه‌یه‌کی نوێ و ماناکه‌ی زیاد بکه‌',
	// [ - ] 'LANG_DELETED' => 'زمان سڕایه‌وه‌...',
	// [ - ] 'LANG_1_NOT_FOR_DEL' => 'زمانی سه‌ره‌کی ناسڕێته‌وه‌...',
	// [ - ] 'NEW_LANG_ADDED' => 'زمانی نوێ زیادکرا...',
	// [ - ] 'NO_WORD_SHOOSED' => 'ووشه‌که‌ت دیاری نه‌کردووه‌..',	// [ - ] 'WORD_DELETED' => 'ووشه‌ به‌سه‌رکه‌وتووی سڕایه‌وه‌...',
	// [ - ] 'WORD_CREATED' => 'ووشه‌ زیادکرا...',	//<<<--
	
	'PLUGINS' => 'زیادکاریه‌کان',
	'PLUGINS_EX' => 'زیادکاریه‌کان.. لێره‌ چاکیان بکه‌ یاخود بیان سڕه‌وه‌',
	'DISABLE' => 'ناچالاکردن',
	'ENABLE' => 'چالاککردن',
	'ADD_NEW_PLUGIN' => 'زیادکردنی زیادکاریه‌کی نوێ',
	'ADD_NEW_PLUGIN_EXP' => 'هه‌سته‌ به‌بارکردنی په‌ڕگه‌ی زیادکاری که‌ له‌درێژکراوه‌ی xml ـــه‌...',
	'PLUGIN_DELETED' => 'زیادکاری سڕایه‌وه‌...',
	'PLGUIN_DISABLED_ENABLED' => 'زیادکاری چالاک \ ناچالاککرا..',
	'NO_PLUGINS' => 'هیچ زیادکاریه‌ک بوونی نییه‌ ..',
	'NEW_PLUGIN_ADDED' => 'لقد تمت إضافة الإضافة البرمجيه .. <br /> لاحظ : بعض الأضافات البرمجيه يأتي معها ملفات تحتاج لنقلها لمجلد كليجا.',
	'R_CHECK_UPDATE' => 'گه‌ڕان به‌دوای نوێکردنه‌وه‌دا',
	'ERROR_CHECK_VER' => 'هه‌ڵه‌: ناتوانرێت زانیاری ده‌رباره‌ی دواوه‌شان بهێنرێت له‌م ساته‌دا , دواتر هه‌وڵبده‌ره‌وه‌!.',
	'UPDATE_KLJ_NOW' => 'ده‌بێت وه‌شانی سکریپته‌که‌ت نوێ بکه‌یته‌وه‌ بۆ دوا وه‌شان, بڕۆ بۆ ماڵپه‌ڕی کلیجا یاخود پڵپشتی کوردی بۆ زانیاری زیاتر..',
	'U_LAST_VER_KLJ' => 'تۆ دوا وه‌شانی کلیجا به‌کارده‌به‌یت. سوپاس بۆ به‌دواداچوونت بۆ نوێکاریه‌کان.',
	
	//rc6
	'U_USE_PRE_RE' => 'تۆ وه‌شانی کاتی به‌کارده‌به‌یت , کرته‌ <a href="http://www.kurdhome.net/forum/forumdisplay.php?f=27/">لێره‌ بکه‌</a> بۆ ئاگادارکردنه‌وه‌مان له‌هه‌ر هه‌ڵه‌یه‌ک که‌به‌دی ده‌که‌یت.',
	'STYLE_IS_DEFAULT'	=> 'ڕوخساری سه‌ره‌کی',
	'MAKE_AS_DEFAULT'	=> 'بیکه‌ره‌ سه‌ره‌کی',
	'TPLS_RE_BASIC'	=>	'قاڵبه‌ بنه‌ڕه‌تیه‌کان', 
	'TPLS_RE_MSG'	=>	'قاڵبه‌کانی زانیاری‌', 
	'TPLS_RE_USER'	=>	'قاڵبه‌کانی تایبه‌ت به‌به‌کارهێنه‌ر', 
	'TPLS_RE_OTHER'	=>	'قاڵبه‌کانی تر',
	'STYLE_NOW_IS_DEFAULT' => 'ڕوخسار کرایه‌ ڕوخساری سه‌ره‌کی',
	'STYLE_DIR_NOT_WR'	=>	'بوخچه‌ی ڕوخسار ی%s ڕێگه‌ی تێدا نوسین نادات , واتا ناتوانیت گۆڕانکاری له‌ قاڵبه‌کانیدا ئه‌نجام بده‌یت تا ئه‌و کاته‌ی ڕێدانی بوخچه‌که‌ ده‌که‌یته‌ 777.',
	'TPL_PATH_NOT_FOUND' => 'قاڵبی %s بوونی نییه‌ !',
	'NO_CACHED_STYLES'	=> 'هیچ قاڵبێکی پاشه‌که‌وتکراو له‌کاتی ئێستادا بوونی نییه‌ !',
	'OPEN'	=> 'بکه‌ره‌وه‌',
	'SEARCH_FOR'	=> 'بگه‌ڕێ',
	'REPLACE_WITH'	=> 'بگۆڕه‌ به‌',
	'REPLACE_TO_REACH'	=> 'Until you reach the next code',
	'ADD_AFTER'	=> 'له‌دوایه‌وه‌ زیادبکه‌',
	'ADD_AFTER_SAME_LINE'	=> 'له‌دوایه‌وه‌ و له‌هه‌مان دێڕدا زیاد بکه‌',
	'ADD_BEFORE'	=> 'له‌پێشیه‌وه‌ زیادبکه‌',
	'ADD_BEFORE_SAME_LINE'	=> 'له‌پێشیه‌وه‌ زیادبکه‌ له‌هه‌مان دێڕدا',
	'ADD_IN'	=> 'تێیدا زیادبکه‌ له‌پاش دروستکردنی',
	'CACHED_STYLES_DELETED'	=>'قاڵبه‌ پاشه‌که‌وتکراوه‌کان سڕانه‌وه‌ .',
	'CACHED_STYLES'	=>'قاڵبه‌ پاشه‌که‌وتکراوه‌کان',
	'DELETE_CACHED_STYLES'	=>'سڕینه‌وه‌ی قاڵبه‌ پاشه‌که‌وتکراوه‌کان',
	'CACHED_STYLES_DISC'	=>	'قاڵبه‌ پاشه‌که‌وتکراوه‌کان له‌ ئه‌نجامی ئه‌و زیادکاریانه‌وه‌ هاتوون که‌زیاکراون به‌ڵام دانه‌مه‌زراون یان به‌هۆی ده‌سه‌ڵاته‌وه‌ یان به‌هۆی نه‌بوونی ووشه‌ی گه‌ڕانی گونجاوه‌وه‌ , بۆیه‌ ده‌بێت خۆت دایانمه‌زرێنیت %s .',
	'UPDATE_NOW_S'	=>	'وه‌شانێکی کۆنی کلیجا به‌کارده‌به‌یت , هه‌ر ئێستا نوێی بکه‌ره‌وه‌ , وه‌شانی ئێستات %1$s وه‌شانی نوێ %2$s .',
	'ADD_NEW_EXT'	=> 'درێژکراوه‌یه‌کی نوێ زیاد بکه‌',
	'ADD_NEW_EXT_EXP'	=> 'درێژکراوه‌که‌ بنوسه‌ , و گروپێکی بۆ هه‌ڵبژێره‌',
	'EMPTY_EXT_FIELD'	=>	'خشته‌ی درێژکراوه‌ به‌تاڵه‌!', 
	'NEW_EXT_ADD'		=>	'درێژکراوه‌ی نوێ زیادکرا',
	'NEW_EXT_EXISTS_B4'	=>	'درێژکراوه‌ی نوێ %s پێشتر بوونی هه‌یه‌ !.',
	'KILOBYTE'	=>	'كيلۆبايت',
	'NOT_SAFE_FILE'	=> 'سیسته‌مه‌که‌مان ده‌ڵێت ئه‌م په‌ڕگه‌یه‌ مه‌ترسیداره‌ !',
	'CONFIG_WRITEABLE' => 'په‌ڕگه‌ی config.php ڕێگای تێدانوسین ده‌دات ئامۆژگاریتان ده‌که‌ین به‌گۆڕینی ڕێدانه‌که‌ی به‌ ‌640 یاخود لایه‌نی که‌م به‌ 644.',
	'NOTE'	=>	'ئاگادارکردنه‌وه‌',
	'WARN'	=>	'انتبه',
	'NO_KLEEJA_COPYRIGHTS'	=> 'وادیاره‌ به‌هه‌ڵه‌ مافه‌کانی کلیجات سڕیوه‌ته‌وه‌ له‌فووته‌ردا‌ , بیگێڕه‌ره‌وه‌ تاوه‌کو بتوانین هه‌میشه‌ خزمه‌تت بکه‌ین و سکریپته‌که‌ پێشبخه‌ین , یاخود مافه‌کانی سڕینه‌وه‌ بکڕه‌ %s .',
	'USERS_NOT_NORMAL_SYS'	=> 'سیسته‌می ئه‌ندامێتی ئێستا سیسته‌مێکی ئاسای نییه‌ , یاخود بڵێین ئه‌ندامه‌کانی ئێستا ناتوانرێت ده‌ستکاری بکرێن له‌م جێگه‌یه‌وه‌ به‌ڵکو ده‌بێت له‌ڕێگه‌ی ئه‌و سکریپته‌وه‌ بکرێن که‌ به‌ستراون به‌ کلیجاوه‌.',
	'ARE_YOU_SURE_DO_THIS'	=> 'دڵنیایت له‌ ئه‌نجامدانی ئه‌م کاره‌؟',
	'DIMENSIONS_THMB'		=> 'Thumbs dimensions',
	'AUTH_INTEGRATION_N_UTF8_T'	=> '%s is not utf8',
	'AUTH_INTEGRATION_N_UTF8' => '%s database must be utf8 to be integrated with Kleeja !.',
	'SCRIPT_AUTH_PATH_WRONG'	=> 'Path of %s is not valid, change it now.',
	'SHOW_MY_FILECP'		=> 'Show my files',
	'PASS_ON_CHANGE'		=> 'Change password',
	'MOST_EVER_ONLINE'		=> 'Most users ever online was',
	'ON'					=> 'on',
	'LAST_REG'				=> 'newest member',
	'NEW_USER'				=> 'New user',
	'LIVEXTS'				=> 'Live extensions (separate by comma)',
	'MUST_LOGIN'			=> 'You must login with correct username and password .',
	
	'COPYRIGHTS_X'			=> 'جميع الحقوق محفوظة',
	'ADMIN_DELETE_FILE_ERR'	=> 'User have no files or there is error occurred while trying to delete user files . ',
	'ADMIN_DELETE_FILE_OK'	=> 'Done ! ',
	'ADMIN_DELETE_FILES'	=> 'Delete all user files',
	
	'KLJ_MORE_PLUGINS'	=> array('قم بالحصول على الكثير من الاضافات من مركز الاضافات في موقع كليجا ,<a target="_blank" href="http://www.kleeja.com/plugins/">إظغط هنا للذهاب لهناك</a> .',
								'هل أنت مطور ؟ هل قمت بتطوير اضافات لكليجا وتريد عرضها للأخرين أو تريد بيعها في مركز كليجا للاضافات ؟ اذن <a target="_blank" href="http://www.kleeja.com/plugins/">إظغط هنا للذهاب لهناك</a>. ',
								),
	'KLJ_MORE_STYLES'	=> array('قم بالحصول على الكثير من الستايلات من معرض الستايلات في موقع كليجا ,<a target="_blank" href="http://www.kleeja.com/styles/">إظغط هنا للذهاب لهناك</a> .',
							 'هل أنت مصمم ؟ هل تريد عرض ستايلاتك في في معرض كليجا للجميع مجانا او بمقابل ؟  إذن <a target="_blank" href="http://www.kleeja.com/styles/">إظغط هنا للذهاب لهناك</a> .',
							 ),
							 
	'CHECK_ALL'	 => 'Check all',
	'BCONVERTER' => 'Byte Converter',
	'NO_HTACCESS_DIR_UP'		=> 'لايوجد ملف .htaccess في مجلد التحميل  "%s", هذا يعني انه لو تم رفع اكواد خبيثه فسيتمكن المخترق  من تشغيلها وقد يحدث امور لاتحمد عقباها !',
	'NO_HTACCESS_DIR_UP_THUMB'	=> 'لايوجد ملف .htaccess في مجلد المصغرات داخل مجلد التحميل "%s", هذا يعني انه لو تم رفع اكواد خبيثه فسيتمكن المخترق المخترق  من تشغيلها وقد يحدث امور لاتحمد عقباها  !',

	
	//last line of this file ...
	'S_TRANSLATED_BY' => 'Translated By <a href="http://www.kurdhome.net/">KurdHome</a>',
));

?>
