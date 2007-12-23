<?php
#####################################
##			Arabic language for Kleeja
## Default language of Kleeja script ..
## by : Kleeja Develpers ..
#####################################

/*
Information :
Arabic Language .. from right-to-left and it use utf8
Spoken by many countries in the middle east ..
*/

$lang = array (

		'DIR'				=> 'rtl', // rtl or ltr
		'HOME'				=> 'البدايه',
		'INDEX'				=> 'الرئيسيه',
		//common.php
		'SITE_CLOSED' 		=> 'الموقع مغلق !',
		'STOP_FOR_SIZE' 	=> 'متوقف حالياً !',
		'SIZES_EXCCEDED' 	=> 'الحجم الكلي للمركز إستنفذ .. سوف نعود قريباً',
		//index.php
		'INFORMATION'		=> 'تعليمات',
		'WELCOME' 			=> 'أهلاً',
		'KLEEJA_VERSION' 	=> 'إصدار كليجا',
		'NUMBER_ONLINE' 	=> 'عدد المتواجدين',
		'NUMBER_UONLINE' 	=> 'أعضاء',
		'NUMBER_VONLINE' 	=> 'زوار',
		//usercp.php
		'USERS_SYSTEM' 			=> 'نظام المستخدمين',
		'ERROR_NAVIGATATION'=> 'خطأ بالتوجه ..',
				#go=login
		'LOGIN' 			=> 'دخول',
		'USERNAME'			=> 'اسم المستخدم',
		'PASSWORD'			=> 'كلمة المرور',
		'EMPTY_USERNAME' 	=> 'حقل اسم المستخدم فارغ',
		'EMPTY_PASSWORD' 	=> 'حقل كلمة المرور فارغ',
		'LOSS_PASSWORD' 	=> 'نسيت كلمة المرور؟',
		'LOGINED_BEFORE' 	=> 'انت داخل بالفعل',
		'LOGOUT' 			=> 'خروج',
		'EMPTY_FIELDS'		=> 'خطأ ..حقول ناقصه!',
		'LOGIN_SUCCESFUL' 	=> 'لقد تم الدخول بنجاح',
		'LOGIN_ERROR' 		=> 'خطأ .. لايمكن الدخول!',
				#go=register
		'REGISTER_CLOSED' 	=> 'نأسف ..التسجيل مقفل حالياً',
		'PLACE_NO_YOU' 		=> 'منطقه محظوره',
		'REGISTERED_BEFORE' => 'لقد قمت بالتسجيل سابقاً',
		'REGISTER' 			=> 'تسجيل عضويه',
		'EMAIL' 			=> 'البريد الإلكتروني',
		'VERTY_CODE'		=> 'كود الأمان',
		'WRONG_EMAIL' 		=> 'بريد خاطئ',
		'WRONG_NAME' 		=> 'الإسم يجب أن يكون أكبر من 4 احرف وغير طويل',
		'WRONG_LINK' 		=> 'رابط خاطئ ..',
		'EXIST_NAME'		=> 'الإسم موجود مسبقاً',
		'EXIST_EMAIL' 		=> 'البريد موجود مسبقاً',
		'WRONG_VERTY_CODE' 	=> 'كود الأمان خاطئ',
		'CANT_UPDATE_SQL' 	=> 'لا يمكن التحديث لقاعدة البيانات',
		'CANT_INSERT_SQL' 	=> 'لايمكن إدخال المعلومات لقاعدة البيانات',
		'REGISTER_SUCCESFUL'=> 'شكراً لتسجيلك معناً',
				#go=logout
		'LOGOUT_SUCCESFUL' 	=> 'تم الخروج بنجاح',
		'LOGOUT_ERROR' 		=> 'هناك مشكله بالخروج',
				#go=filecp
		'FILECP' 			=> 'إدارة الملفات',
		'DEL_SELECTED' 		=> 'حذف المحدد',
		'EDIT_U_FILES' 		=> 'تحديث ملفاتك',
		'FILES_UPDATED' 	=> 'تم تحديث الملفات',
		
				#go=userfile
		'PUBLIC_USER_FILES'	=> 'مجلد ملفات العضو ',
		'FILEUSER'			=> 'مجلد ملفات عضو',
		'GO_FILECP'			=> 'إظغط هنا لإدارة ملفاتك هذه',
		'YOUR_FILEUSER'		=> 'مجلدك',
		'COPY_AND_GET_DUD'	=> 'إنسخ الرابط وأعطه لأصدقائك ليطلعو على مجلدك ',
		'CLOSED_FEATURE'	=> 'خاصية مغلقه',
		'USERFILE_CLOSED'	=> 'خاصية مجلدات المستخدمين مغلقه !',
		
				#go=profile
		'PFILE_4_FORUM' 	=> 'قم بالذهاب للمنتدى لتغيير بياناتك',
		'USER_PLACE' 		=> 'منطقة أعضاء',
		'PROFILE' 			=> 'ملفك..',
		'EDIT_U_DATA' 		=> 'تحديث بياناتك',

		'PASS_ON_CHANGE' 	=> 'كلمة المرور ..عند التغيير فقط',
		'OLD' 				=> 'القديمه',
		'NEW' 				=> 'الجديده',
		'NEW_AGAIN' 		=> 'تكرار الجدبده',
		'UPDATE' 			=> 'تحديث',
		'PASS_O_PASS2' 		=> 'كلمة المرور القديمه مهمه واكتب كلمتا المرور الجديدتان بدقه ',
		'DATA_CHANGED_O_LO' => 'تم تحديث بياناتك وسوف تستخدم بدخولك القادم',
		'DATA_CHANGED_NO' 	=> 'لم تحدث بياناتك .. لن تتغير المعلومات',
				#go=get_pass
		'LOST_PASS_FORUM' 	=> 'إذهب للمنتدى وإسترجع كلمة المرور',
		'GET_LOSTPASS' 		=> 'إستعادة كلمة المرور',
		'E_GET_LOSTPASS'	=> 'لإستعادة كلمة المرور يجب أن تكتب البريد الالكتروني المسجل لدينا',
		'WRONG_DB_EMAIL' 	=> 'لا يوجد بريد كهذا في قاعدة البيانات لدينا',
		'GET_LOSTPASS_MSG' 	=> 'لقد قمت بطلب كلمة مرور جديده  لعضويتك لدينا .. وقمنا بإرجاعها لك',
		'CANT_SEND_NEWPASS' => 'خطأ ..لم يتم ارسال كلمة المرور الجديده!',
		'OK_SEND_NEWPASS' 	=> 'تم إرسال كلمة المرور الجديده..',
		//go.php
				#go=guide
		'GUIDE' 			=> 'الملفات المسموحه',
		'GUIDE_VISITORS'	=> 'الإمتدادات المسموحه للزوار وامتداداتها:',
		'GUIDE_USERS' 		=> 'الإمتدادات المسموحه للأعضاء وامتداداتها:',
		'EXT' 				=> 'الإمتداد',
		'SIZE' 				=> 'الحجم',
				#go=report
		'REPORT' 			=> 'تبليغ',
		'YOURNAME' 			=> 'إسمك',
		'URL' 				=> 'الرابط',
		'REASON' 			=> 'السبب',
		'NO_ID' 			=> 'لم تحدد ملف..!!',
		'NO_ME300RES' 		=> 'رجاءاً .. حقل السبب لا يمكن ملأه بأكثر من 300 حرف!!',
		'THNX_REPORTED' 	=> 'تم التبليغ .. شكراً لاهتمامك',
				#go=rules
		'RULES' 			=> 'الشروط',
		'NO_RULES_NOW' 		=> 'لايوجد قوانين حالياً',
		'E_RULES' 			=> 'هذه هي شروط مركز التحميل',
				#go=call
		'CALL' 				=> 'إتصل بنا',
		'SEND' 				=> 'أرسل',
		'TEXT' 				=> 'النص',
		'NO_ME300TEXT' 		=> 'رجاءاً .. حقل النص لا يمكن ملأه بأكثر من 300 حرف!!',
		'THNX_CALLED' 		=> 'تم الإرسال. . سوف يتم الرد قريباً',
				#go=down
				#go=del
		'NO_DEL_F' 			=> 'نأسف .خاصية الحذف المباشر معطله من المدير',
		'E_DEL_F'			=> 'الحذف المباشر',
		'WRONG_URL' 		=> 'خطأ .. في الرابط ..',
		'CANT_DEL_F' 		=> 'خطأ ..لايمكن حذف الملف .. ربما معلوماتك خاطئه او قد تم حذف مسبقاً',
		'CANT_DELETE_SQL' 	=> 'لا يمكن الحذف من قاعدة البيانات',
		'DELETE_SUCCESFUL' 	=> 'تم الحذف بنجاح',
				#go=stats
		'STATS' 			=> 'إحصائيات المركز',
		'STATS_CLOSED' 		=> 'صفحة الإحصائيات معطله من المدير !',
		'FILES_ST' 			=> 'تم  تحميل ',
		'FILE' 				=> 'ملف',
		'USERS_ST' 			=> 'عدد الأعضاء',
		'USER' 				=> 'عضو',
		'SIZES_ST' 			=> 'حجم كل الملفات',	
		'LSTFLE_ST' 		=> 'آخر مارفع',	
		'LSTDELST' 			=> 'آخر فحص للملفات الخامله',	
		'S_C_T' 			=> 'زوار اليوم',	
		'S_C_Y' 			=> 'زوار أمس',	
		'S_C_A' 			=> 'عدد الزوار كلياً',	
		'LAST_1_H' 			=> 'هذه الإحصائيات لقبل ساعه من الآن',	
		//download.php
		'DOWNLAOD' 			=> 'تحميل',
		'FILE_FOUNDED' 		=> 'تم إيجاد الملف .. ',
		'WAIT' 				=> 'إنتظر رجاءاً ..',
		'CLICK_DOWN' 		=> 'اضغط هنا لتنزيل الملف',
		'JS_MUST_ON' 		=> 'لا بد من تفعيل الجافا سكربت في  متصفحك !!',
		'FILE_INFO' 		=> 'معلومات عن الملف',
		'FILENAME' 			=> 'إسم الملف',
		'FILESIZE' 			=> 'حجم الملف',
		'FILETYPE' 			=> 'نوع الملف',
		'FILEDATE' 			=> 'تاريخ الملف',
		'FILEUPS' 			=> 'عدد التحميلات',
		'FILEREPORT' 		=> 'تبليغ : ملف مخالف للقوانين',
		'FILE_NO_FOUNDED' 	=> 'لم نتمكن من إيجاد الملف ..!!',
		'IMG_NO_FOUNDED' 	=> 'لم نتمكن من إيجاد الصوره ..!!',
		'NOT_IMG' 			=> 'ليست صوره  ..هذا ملف!!',
		//includes/KljUploader.php
		'MORE_F_FILES' 		=> 'هذا آخر حد يمكنك تحميله',
		'DOWNLOAD_F' 		=> '[ تحميل الملفات ]',
		'DOWNLOAD_T' 		=> '[ تحميل من الرابط ]',
		'PAST_URL_HERE' 	=> '[ ألصق الرابط هنا ]',
		'SAME_FILE_EXIST' 	=> 'هذا الملف موجود مسبقا',
		'NO_FILE_SELECTED' 	=> 'لم تقم بإختيار ملف !!',
		'WRONG_F_NAME' 		=> 'اسم الملف غير يحوي أحرف غير مسموحه',
		'FORBID_EXT' 		=> 'هذا الإمتداد غير مدعوم',
		'SIZE_F_BIG' 		=> 'الحجم للملف المختار يجب أن يكون أقل من',
		'CANT_CON_FTP' 		=> 'لايمكن الإتصال بـ ',
		'URL_F_DEL' 		=> 'رابط الحذف',
		'URL_F_THMB' 		=> 'رابط المصغره',
		'URL_F_FILE' 		=> 'رابط الملف',
		'URL_F_IMG' 		=> 'رابط الصوره',
		'URL_F_BBC' 		=> 'رابط للمنتديات',
		'IMG_DOWNLAODED' 	=> 'تم تحميل الصوره بنجاح',
		'FILE_DOWNLAODED' 	=> 'تم تحميل الملف بنجاح',
		'CANT_UPLAOD' 		=> 'خطأ... لم يتم تحميل الملف  لاسباب غير معروفة',
		'NEW_DIR_CRT' 		=> 'لقد تم انشاء مجلد جديد',
		'PR_DIR_CRT' 		=> 'لم يتم اعطاء التصريح للمجلد',
		'CANT_DIR_CRT' 		=> 'لم يتم إنشاء مجلد تلقائياً .. قم بإنشاءه انت',
		'AGREE_RULES'		=> 'أوافق على شروط المركز',
		'CHANG_TO_URL_FILE'	=> 'تبديل طريقة التحميل..رابط أو إدخال',
		'CURL_IS_OFF'		=> 'دوال CURL معطله ..',

		//includes/cache.php
		'ADMINCP' 			=> 'مركز التحكم',
		'JUMPTO' 			=> 'إنتقل إلى',
		'GO_BACK_BROWSER' 	=> 'رجوع للخلف',
		'U_R_BANNED' 		=> 'لقد تم حظر الآي بي هذا..',
		'U_R_FLOODER' 		=> 'لقد قمت بتخطي عدد مرات عرض الصفحه بالوقت المحدد  ...',
		
		//admin.php
		'U_NOT_ADMIN' 		=> 'يجب أن تملك صلاحية الإداره',
		'UPDATE_CONFIG' 	=> 'تحديث الإعدادات',
		'YES' 				=> 'نعم',
		'NO' 				=> 'لا',
		'NO_CHANGE' 		=> 'بلا تغيير',
		'CHANGE_MD5' 		=> 'تغيير مع دالة MD5',
		'CHANGE_TIME' 		=> 'تغيير مع دالة TIME',
		'SITENAME' 			=> 'إسم المركز',
		'SITEMAIL' 			=> 'بريد المركز',
		'SITEURL' 			=> 'رابط المركز(مع /)',
		'FOLDERNAME' 		=> 'إسم مجلد التحميل',
		'FILES_PREFIX' 		=> 'بادئة اسماء الملفات',
		'FILES_NUMB' 		=> 'عدد ملفات التحميل',
		'SITECLOSE' 		=> 'إغلاق المركز',
		'CLOSE_MSG' 		=> 'رسالة الإغلاق',
		'LANGUAGE'			=> 'اللغه',
		'FILENAME_CHNG' 	=> 'تغيير إسم الملف',
		'STYLENAME' 		=> 'ستايل المركز',
		'SC_BEFOR_DOWM' 	=> 'الثواني قبل بدء التحميل',
		'SHOW_PHSTAT' 		=> 'إحصائيات الصفحه بالفوتر',
		'EN_GZIP' 			=> 'gzip مسرع',
		'WELC_MSG' 			=> 'كلمة الترحيب',
		'USER_SYSTEM' 		=> 'نظام العضويه',
		'NORMAL' 			=> 'عادي',
		'W_PHPBB' 			=> 'مربوط phpbb',
		'W_MYSBB' 			=> 'مربوط MySmartBB',
		'W_VBB' 			=> 'مربوط vb',
		'ENAB_REG' 			=> 'فتح التسجيل',
		'MAX_SIZE_SITE' 	=> 'أقصى حجم كلي بالميقا',
		'ENAB_THMB' 		=> 'تفعيل مصغرات الصور',
		'ENAB_STAMP' 		=> 'تفعيل ختم الصور',
		'ENAB_DELURL' 		=> 'تفعيل رابط الحذف المباشر',
		'WWW_URL'			=> 'تفعيل التحميل من رابط',
		'ALLOW_STAT_PG'		=> 'تفعيل صفحة الإحصائيات',
		'ALLOW_ONLINE'		=> 'تفعيل عرض المتواجدون الآن',
		'DEL_FDAY'			=> 'حذف الملفات الخامله كذا يوم',
		'MOD_WRITER'		=> 'Mod Rewrite',
		'MOD_WRITER_EX'		=> 'روابط كهتمل..',
		'NUMFIELD_S' 		=> 'رجاءاً .. الحقول الرقميه .. يجب ان تكون رقميه !!',
		'CONFIGS_UPDATED' 	=> 'تم تحديت الإعدادات بنجاح',
		'UPDATE_EXTS' 		=> 'تحديث الإمتدادات',
		'GROUP' 			=> 'المجموعه',
		'SIZE_G' 			=> 'الحجم ز',
		'SIZE_U' 			=> 'الحجم م',
		'ALLOW_G' 			=> 'سماح ز',
		'ALLOW_U' 			=> 'سماح م',
		'E_EXTS' 			=> 'ز :تعني الزوار<br />م : تعني الأعضاء <br />الأحجام تظبط بالبايت.',
		'UPDATED_EXTS' 		=> 'تم تحديث الإمتدادات بنجاح',
		'UPDATE_FILES' 		=> 'تحديث الملفات',
		'BY' 				=> 'من',
		'FILDER' 			=> 'مجلد',
		'DELETE' 			=> 'حذف',
		'GUST' 				=> 'زائر',
		'FILES_UPDATED' 	=> 'تم تحديث الملفات بنجاح',
		'UPDATE_REPORTS' 	=> 'تحديث التبليغات',
		'NAME' 				=> 'الإسم',
		'CLICKHERE' 		=> 'إظغط هنا',
		'TIME' 				=> 'الوقت',
		'E_CLICK' 			=> 'إظغط على أحد المختارات لتظهر هنا!',
		'IP' 				=> 'IP',
		'REPLY' 			=> '[ رد ]',
		'REPLY_REPORT' 		=> 'رد على تبليغ',
		'U_REPORT_ON' 		=> 'بسبب تبليغك في ',
		'BY_EMAIL' 			=> 'بواسطة البريد ',
		'ADMIN_REPLIED' 	=> 'فقد قام المدير بالرد التالي',
		'CANT_SEND_MAIL' 	=> 'لا يمكن إرسال رد بريدي',
		'IS_SEND_MAIL' 		=> 'تم إرسال الرد البريدي',
		'REPORTS_UPDATED' 	=> 'تم تحديث التبليغات',
		'UPDATE_CALSS' 		=> 'تحديث المراسلات',
		'REPLY_CALL' 		=> 'رد على مراسله',
		'REPLIED_ON_CAL' 	=> 'بخصوص مراسلتك ',
		'CALLS_UPDATED' 	=> 'تم تحديث المراسلات',
		'IS_ADMIN' 			=> 'مدير',
		'UPDATE_USERS' 		=> 'تحديث المستخدمين',
		'USERS_UPDATED' 	=> 'تم تحديث المستخدمين',
		'E_BACKUP' 			=> 'اختر الجداول التي تريد تضمينها في النسخة الاحتياطية ومن ثم اضغط على تحميل',
		'SIZE' 				=> 'الحجم',
		'TAKE_BK' 			=> 'أخذ نسخه',
		'REPAIRE_TABLE' 	=> '[جداول] تم إصلاح ',
		'REPAIRE_F_STAT' 	=> '[إحصائيات] تم إعادة إحتساب عدد الملفات',
		'REPAIRE_S_STAT' 	=> '[إحصائيات] تم إعادة إحتساب حجم الملفات ',
		'REPAIRE_CACHE' 	=> '[كاش] تم حذف  ..',
		'KLEEJA_CP' 		=> 'لوحة تحكم [ كليجا ]',
		'GENERAL_STAT' 		=> 'إحصائيات عامه',
		'SIZE_STAT' 		=> 'إحصائيات الحجم',
		'OTHER_INFO' 		=> 'معلومات أخرى',
		'AFILES_NUM' 		=> 'عدد الملفات كلها',
		'AFILES_SIZE' 		=> 'أحجام الملفات كلها',
		'AUSERS_NUM' 		=> 'عدد الإعضاء',
		'LAST_GOOGLE'		=> 'آخر زيارة لجوجل',
		'GOOGLE_NUM'		=> 'زيارات جوجل',
		'LAST_YAHOO'		=> 'آخر زيارة للياهو',
		'YAHOO_NUM'			=> 'زيارات الياهو',
		'KLEEJA_CP_W' 		=> 'أهلا وسهلاً بك في لوحة التحكم لمركز التحميل <b>كليجا</b>',
		'USING_SIZE' 		=> 'الحجم المستخدم',
		'PHP_VER' 			=> 'إصدار php',
		'MYSQL_VER' 		=> 'إصدار mysql',
		'N_IMGS' 			=> 'الصور',
		'N_ZIPS' 			=> 'ملفات الظغط',
		'N_TXTS' 			=> 'ملفات النصوص',
		'N_DOCS' 			=> 'مستندات',
		'N_RM' 				=> 'RealMedia',
		'N_WM' 				=> 'WindowsMedia',
		'N_SWF' 			=> 'ملفات الفلاش',
		'N_QT' 				=> 'QuickTime',
		'N_OTHERFILE' 		=> 'ملفات أخرى',
		'LOGOUT_CP_OK' 		=> 'تم مسح جلستك الإداريه وبقي صلاحياتك الأخرى..',
		'RETURN_HOME' 		=> '<<  رجوع للمركز',
		'R_CONFIGS' 		=> 'إعدادات المركز',
		'R_CPINDEX' 		=> 'بداية لوحة التحكم',
		'R_EXTS' 			=> 'إعدادات الإمتدادات',
		'R_FILES' 			=> 'التحكم بالملفات',
		'R_REPORTS' 		=> 'التحكم بالتبليغات',
		'R_CALLS' 			=> 'التحكم بالمراسلات',
		'R_USERS' 			=> 'التحكم بالأعضاء',
		'R_BCKUP' 			=> 'نسخه إحتياطيه',
		'R_REPAIR' 			=> 'صيانه شامله',
		'R_LGOUTCP' 		=> 'مسح جلسة الإداره',
		'R_BAN' 			=> 'التحكم بالحظر',
		'BAN_EXP1' 			=> 'قم بتحرير الآيبيات المحظوره وإضافة الجديد من هنا ..',
		'BAN_EXP2' 			=> 'إستخدم رمز النجمه (*)لاستبدال الارقام ..إذا كنت تريد الحظر الشامل ..وأستخدم الفاصل (|) للفصل بين الآيبيات',
		'UPDATE_BAN' 		=> 'حفظ تعديلات الحظر',	
		'BAN_UPDATED' 		=> 'تم تحديث قائمة الحظر بنجاح ..',	
		'R_RULES'			=> 'التحكم بالشروط',
		'RULES_EXP'			=> 'من هنا تستطيع تعديل الشروط التي سوف تظهر للزوار والأعضاء',
		'UPDATE_RULES'		=> 'تحديث الشروط',
		'RULES_UPDATED' 	=> 'تم تحديث الشروط بنجاح ..',	
		'R_SEARCH'			=> 'بحث متقدم',
		'SEARCH_FILES'		=> 'بحث عن الملفات',
		'SEARCH_SUBMIT'		=> 'بحث الآن',
		'LAST_DOWN'			=> 'آخر تحميل',
		'TODAY'				=> 'اليوم',
		'DAYS'				=>	'أيام',
		'WAS_B4'			=> 'كان قبل',
		'BITE'				=> 'بايت',
		'SEARCH_USERS'		=> 'بحث عن مستخدمين',	
		'R_IMG_CTRL'		=> 'تحكم بالصور فقط',
		'ENABLE_USER_FILE'	=> 'تفعيل مجلدات المستخدمين',
		'R_EXTRA'			=> 'هيدر وفوتر إضافي',
		'EX_HEADER_N'		=> 'الهيدر الإضافي .. وهو مايظهر أسفل الهيدر الأصلي ..',
		'EX_FOOTER_N'		=> 'الفوتر الإضافي .. وهو مايظهر أعلى الفوتر الأصلي ..',
		'UPDATE_EXTRA'		=> 'تحديث الإضافات القوالبيه',
		'EXTRA_UPDATED'		=> 'تم تحديث الإضافات القوالبيه',
		
		//install.php
		'INST_AGR_GPL2'		=> 'أوافق على هذه الشروط كلها',
		'INST_SUBMIT' 		=> '[  متابعه  ]',
		'INST_SITE_INFO' 	=> 'معلومات الموقع',
		'INST_SITE_INFO' 	=> 'معلومات المسؤول',
		'INST_CHANG_CONFIG' => ' config.php بعض البيانات المهمه ناقصه إملأ ملف',
		'INST_CONNCET_ERR' 	=> 'لا يمكن الإتصال ..',
		'INST_SELECT_ERR' 	=> 'لا يمكن الإرتباط بقاعدة البيانات',
		'INST_NO_WRTABLE' 	=> 'مجلد غير قابل للكتابه .. ',
		'INST_GOOD_GO' 		=> 'تم التأكد من المتغييرات والإتصال والتراخيص .. تابع',
		'INST_MSGINS' 		=> 'أهلا بك في مركز التحميل . يمكنك تحميل ماتشاء وفق المسموح به ..شكراُ لزيارتك',
		'INST_CRT_CALL' 	=> 'تم إنشاء جدول المراسلات',
		'INST_CRT_ONL' 		=> 'تم إنشاء جدول المتواجدون الآن',
		'INST_CRT_REPRS' 	=> 'تم إنشاء جدول التبليغات',
		'INST_CRT_STS'		=> 'تم إنشاء جدول الإحصائيات',
		'INST_CRT_USRS' 	=> 'تم إنشاء جدول الأعضاء',
		'INST_CRT_ADM'	 	=> 'تم إدخال معلومات المسؤول',
		'INST_CRT_FLS' 		=> 'تم إنشاء جدول الملفات',
		'INST_CRT_CNF' 		=> 'تم إنشاء جدول الإعدادات',
		'INST_CRT_EXT' 		=> 'تم إنشاء جدول الإمتدادات',
		'INST_SQL_OK' 		=> 'تم تنفيذ الإستعلام بنجاح ..',
		'INST_SQL_ERR'		=> 'خطأ في تنفيذ الإستعلام .. ',
		'INST_FINISH_SQL' 	=> 'تم الإنتهاء من التثبيت .. قم بحذف ملف التثبيت وتوجه للرئيسيه',
		'INST_FINISH_ERRSQL'=> 'يبدوا ان هناك مشكله تعيق التثبيت .. حاول مجدداً او استفسر من المطورين',
		'INST_KLEEJADEVELOPERS'=> 'مع أحلى و أطيب التمنيات .. من فريق عمل كليجا'
);




?>