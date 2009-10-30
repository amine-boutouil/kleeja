<?php
//Arabic langugae
// KLEEJA 


	if(!isset($lang) || !is_array($lang)) $lang = array();
	
	$lang['INST_INSTALL_WIZARD']				= 'معالج تثبيت كليجا';
	$lang['INST_INSTALL_CLEAN_VER']				= "تثبيت نسخه جديده ";
	$lang['INST_UPDATE_P_VER']					= "تحديث نسخه سابقه ";
	$lang['INST_AGR_LICENSE']					= 'أوافق على هذه الشروط كلها';
	$lang['INST_NEXT']							= 'التالي';
	$lang['INST_PREVIOUS']						= 'السابق';
	$lang['INST_SITE_INFO']						= 'فضلاً قم بإدخال بيانات الموقع';
	$lang['INST_ADMIN_INFO']					= 'معلومات مدير المركز';
	$lang['INST_CHANG_CONFIG']					= 'بعض البيانات المهمه ناقصه إملأ ملف config.php';
	$lang['INST_CONNCET_ERR']					= 'لا يمكن الإتصال ..';
	$lang['INST_SELECT_ERR']					= 'لا يمكن الإرتباط بقاعدة البيانات';
	$lang['INST_NO_WRTABLE']					= 'مجلد غير قابل للكتابه .. يحتاج تصريح 777';
	$lang['INST_GOOD_GO']						= 'تم التأكد من المتغييرات والإتصال والتراخيص .. تابع';
	$lang['INST_MSGINS']						= 'يمكنك تحميل ماتشاء وفق المسموح به .. شكراُ لزيارتك';
	$lang['INST_CRT_CALL']						= 'تم إنشاء جدول المراسلات';
	$lang['INST_CRT_ONL']						= 'تم إنشاء جدول المتواجدون الآن';
	$lang['INST_CRT_REPRS']						= 'تم إنشاء جدول التبليغات';
	$lang['INST_CRT_STS']						= 'تم إنشاء جدول الإحصائيات';
	$lang['INST_CRT_USRS']						= 'تم إنشاء جدول الأعضاء';
	$lang['INST_CRT_ADM']						= 'تم إدخال معلومات المسؤول';
	$lang['INST_CRT_FLS']						= 'تم إنشاء جدول الملفات';
	$lang['INST_CRT_CNF']						= 'تم إنشاء جدول الإعدادات';
	$lang['INST_CRT_EXT']						= 'تم إنشاء جدول الإمتدادات';
	$lang['INST_CRT_HKS']						= 'تم إنشاء جدول الهاكات';
	$lang['INST_CRT_LNG']						= 'تم إنشاء جدول اللغه';
	$lang['INST_CRT_LSTS']						= 'تم إنشاء جدول القوائم';
	$lang['INST_CRT_PLG']						= 'تم إنشاء جدول الإضافات';
	$lang['INST_CRT_TPL']						= 'تم إنشاء جدول القوالب';
	$lang['INST_SQL_OK']						= 'تم تنفيذ الإستعلام بنجاح ..';
	$lang['INST_SQL_ERR']						= 'خطأ في تنفيذ الإستعلام .. ';
	$lang['INST_FINISH_SQL']					= 'تم الإنتهاء من التثبيت .. قم بحذف  مجلد التثبيت  install , وتوجه للرئيسيه';
	$lang['INST_FINISH_ERRSQL']					= 'يبدوا ان هناك مشكله تعيق التثبيت .. حاول مجدداً او استفسر من المطورين';
	$lang['INST_KLEEJADEVELOPERS']				= 'مع أحلى و أطيب التمنيات .. من فريق عمل كليجا';
	$lang['SITENAME']							= 'اسم الموقع';
	$lang['SITEURL']							= 'رابط الموقع';
	$lang['SITEMAIL']							= 'بريد الموقع';
	$lang['USERNAME']							= 'اسم المستخدم';
	$lang['PASSWORD']							= 'كلمة المرور';
	$lang['PASSWORD2']							= 'أعد كلمة المرور';
	$lang['EMAIL']								= 'البريد الالكتروني';
	$lang['INDEX']								= 'الرئيسيه';
	$lang['ADMINCP']							= 'لوحة التحكم';
	$lang['DIR']								= 'rtl';
	$lang['EMPTY_FIELDS']						= 'هنـــاك حقول مهمه, تركتها فارغه...';
	$lang['WRONG_EMAIL']						= 'البريد الإلكتروني خاطئ !';
	//
	
	$lang['DB_INFO_NW']							= 'قم بإدخال معلومات قاعدة البيانات بشكل صحيح .. ثم سوف نصدرها لك كملف , تضعه في مجلد السكربت الرئيسي';
	$lang['DB_INFO']							= 'قم بإدخال معلومات قاعدة البيانات بشكل صحيح .. ';
	$lang['DB_SERVER']							= 'الخادم';
	$lang['DB_TYPE']							= 'نوع قاعده البيانات';
	$lang['DB_TYPE_MYSQL']						= 'MySQL القياسي';
	$lang['DB_TYPE_MYSQLI']						= 'MySQL المطور';
	$lang['DB_USER']							= 'اسم المستخدم لقاعدة البيانات';
	$lang['DB_PASSWORD']						= 'كلمة المرور لقاعدة البيانات';
	$lang['DB_NAME']							= 'إسم قاعدة البيانات';
	$lang['DB_PREFIX']							= 'بادئة الجداول <span style="font-size:10px;color:#FF0000">[كلمة توضع لتعريف جداول السكربت عن غيرها]</span>';
	$lang['VALIDATING_FORM_WRONG']				= 'يبدو أنك تركت أحد الحقول المطلوبه فارغاً ...';
	$lang['CONFIG_EXISTS']						= 'تم إيجاد ملف config.php قم بالمتابعه ...';
	$lang['INST_SUBMIT_CONFIGOK']				= 'قمت برفع الملف , بالمجلد الرئيسي , تابع التثبيت';
	$lang['INST_EXPORT']						= 'تصدير الملف';
	$lang['INST_OTHER_INFO']					= 'معلومات اخرى';
	$lang['URLS_TYPES']							= 'شكل روابط الملفات';
	$lang['DEFAULT']							= 'الافتراضي';
	$lang['FILENAME_URL']						= 'اسماء الملفات';
	$lang['DIRECT_URL']							= 'روابط مباشره';
	$lang['LIKE_THIS']							= 'مثال';

	//
	$lang['FUNCTIONS_CHECK']					= 'فحص الدوال';
	$lang['RE_CHECK']							= 'إعادة الفحص';
	$lang['FUNCTION_IS_NOT_EXISTS']				= 'الدالة %s معطله لديك.';
	$lang['FUNCTION_IS_EXISTS']					= 'الدالة %s مفعله لديك.';
	$lang['FUNCTION_DISC_UNLINK']				= 'دالة unlink يتم استخدامها لحذف الملفات وأيضا لحذف ملفات الكاش وتحديثها.';
	$lang['FUNCTION_DISC_GD']					= 'دالة imagecreatetruecolor هي من دوال مكتبة GD التي تستخدم لإنشاء المصغرات وأيضا التحكم بالصور.';
	$lang['FUNCTION_DISC_FOPEN']				= 'دالة fopen تستخدم في التحكم بالستايل والملفات في كليجا.';
	$lang['FUNCTION_DISC_MUF']					= 'دالة move_uploaded_file تستخدم لتحميل الملفات  وهي اهم دالة في السكربت.';
	//
	$lang['ADVICES_CHECK']						= 'فحص متقدم (يمكن تثبيت كليجا بدون تحقق هذا الفحص , لكنه مجرد معلومات لك)';
	$lang['ADVICES_REGISTER_GLOBALS']			= '<span style="color:red;padding:0 6px;">خاصية register_globals مفعله ..!</span><br /> هذه الخاصيه غير محبب تفعيلها ويفضل تعطيلها , ومع هذا فكليجا تحاول تعطيل اثارها برمجياً .';
	$lang['ADVICES_ICONV']						= '<span style="color:red;padding:0 6px;">دوال iconv غير مفعله لديك  ..!</span><br /> لكن هذا لايمنع تثبيت كليجا , فقط ستواجه مشاكل عند الربط مع سكربتات اخرى غير متوافقه مع نظام الترميز العالمي UTF8.';
	$lang['ADVICES_MAGIC_QUOTES']				= '<span style="color:red;padding:0 6px;">خاصية magic_quotes مفعله ..!</span><br /> هذه الخاصيه غير محبب تفعيلها ويفضل تعطيلها , ومع هذا فكليجا تحاول تعطيل اثارها برمجياً ..';
	
	//UPDATOR
	$lang['INST_CHOOSE_UPDATE_FILE']			= 'قم بإختيار التحديث المناسب لك ومن ثم تابع التحديث ..';
	$lang['INST_ERR_NO_SELECTED_UPFILE_GOOD']	= 'ملف التحديث غير مناسب او انه غير موجود من الأساس ! ..';
	$lang['INST_UPDATE_CUR_VER_IS_UP']			= 'النسخه الحاليه محدثه لهذا التحديث المحدد.';
	$lang['INST_UPDATE_SELECT_ONTHER_UPDATES']	= 'رجوع وإختيار تحديث آخر.';
	
	$lang['INST_NOTES_UPDATE']					= 'ملاحظات التحديث';
	$lang['INST_NOTE_RC2_TO_RC3']				= 'لا بد ان تقوم أيضا بإستبدال الملفات الجديده المعدله لإستكمال الترقيه بشكل جيد !.';
	$lang['INST_NOTE_RC4_TO_RC5']				= 'لا بد ان تقوم أيضا بإستبدال الملفات الجديده المعدله لإستكمال الترقيه بشكل جيد !.';
	$lang['INST_NOTE_RC5_TO_RC6']				= 'لا بد ان تقوم أيضا بإستبدال الملفات الجديده المعدله لإستكمال الترقيه بشكل جيد !.';
	$lang['INST_NOTE_RC6_TO_1.0.0']				= 'لا بد ان تقوم أيضا بإستبدال الملفات الجديده المعدله لإستكمال الترقيه بشكل جيد !.';
	$lang['RC6_1_CNV_CLEAN_NAMES']				= 'جاري تحديث كل عضو لوضع الاسم النظيف ...';
	
	$lang['INST_UPDATE_IS_FINISH']				= "معالج التحديث انتهى .. يجب الآن حذف مجلد <br /><b>INSTALL</b><br /> و متابعة عملك في كليجا ..";
	$lang['IN_INFO']							= 'ادخل معلومات السكربت الذي تريد ربط كليجا معه  كنظام عضويات أو تجاهل هذه الخطوه اذا كنت ترغب بنظام العضويات العادي ..<br /><span style="color:red;">ملاحظة : بعد الانتهاء من ثبيت كليجا قم باختيار نظام الاعضاء من لوحة التحكم في كليجا</span>';
	$lang['IN_PATH']							= 'مجلد السكربت';
	$lang['INST_PHP_LESSMIN']					= 'لتثبيت كليجا يجب على الأقل أن يكون لديك اصدار PHP %1$s , وانت لديك اصدار PHP %2$s.';
	$lang['INST_MYSQL_LESSMIN']					= 'لتثبيت كليجا يجب على الأقل أن يكون لديك اصدار MYSQL %1$s , وانت لديك اصدار MYSQL %2$s.';
	$lang['IS_IT_OFFICIAL']						= 'هل قمت بتحميل النسخه من الموقع الرسمي Kleeja.com ؟';
	$lang['IS_IT_OFFICIAL_DESC']				= 'يصلنا الكثير من الشكاوي والتساؤلات عن سبب بعض المشاكل التي تحدث في بعض النسخ ولا نستطيع معرفة المشكلة غالباً , لكن بعد البحث وجدنا ان هناك نسخاً يتم تحميلها من مواقع اخرى غير رسميه وتكون اما معدلة بطريقة بدائية او مزروع داخلها اكواد خبيثه.<br /><br /> <span style="color:#154188;border-bottom:1px dashed #154188;padding:4px 0;">لذا , هل انت متأكد من ان نسختك هذه تم تحميلها من الموقع الرسمي : kleeja.com ؟</span>';
	$lang['IS_IT_OFFICIAL_YES']					= 'نعم , نسختي قمت بتحميلها من الموقع الرسمي kleeja.com';
	$lang['IS_IT_OFFICIAL_NO']					= 'لا , لم اقم بتحميلها من هناك , حولني للموقع  الرسمي للتحميل الان';
	
	//wizard
	$lang['WZ_TITLE'] 							= 'معالج مابعد تثبيت كليجا';
	$lang['WZ_TXT_W1'] 							= 'قم بتغيير كل ماتريد في كليجا لما تحب , من خلال الاعدادت في لوحة التحكم';
	$lang['WZ_TXT_W2'] 							= 'قم بالتحكم بالامتدادات \ الملفات التي تريد ان يقوم مستخدمينك بتحميلها والتحكم بإحجامها .';
	$lang['WZ_TXT_W3'] 							= 'قم بالتحكم بالستايلات و اختيار مايناسبك , ايضا التعديل على الشكل ككل  ..';
	$lang['WZ_TXT_W4'] 							= 'لوضع قوانين للمركز او شروط خاصه تحددها انت ...';
	
//<-- EOF