<?php
// Settings file for HESK 2.6.7

// ==> GENERAL

// --> General settings
$hesk_settings['site_title']='Funny&amp;Money Help';
$hesk_settings['site_url']='http://www.funnyandmoney.com/help';
$hesk_settings['webmaster_mail']='support@funnyandmoney.com';
$hesk_settings['noreply_mail']='noreply@funnyandmoney.com';
$hesk_settings['noreply_name']='Funny&amp;Money Support';

// --> Language settings
$hesk_settings['can_sel_lang']=1;
$hesk_settings['language']='Turkish';
$hesk_settings['languages']=array(
'Turkish' => array('folder'=>'tr','hr'=>'------ Bu satırın üstünü yanıtla ------'),
'English' => array('folder'=>'en','hr'=>'------ Reply above this line ------'),
);

// --> Database settings
$hesk_settings['db_host']='localhost';
$hesk_settings['db_name']='funhelpsdkhsdwnv';
$hesk_settings['db_user']='hkgsahgfsdfc';
$hesk_settings['db_pass']='wb=2S%03m?E1';
$hesk_settings['db_pfix']='ticket_';
$hesk_settings['db_vrsn']=1;


// ==> HELP DESK

// --> Help desk settings
$hesk_settings['hesk_title']='Funny&amp;Money Fast Help';
$hesk_settings['hesk_url']='http://www.funnyandmoney.com/ticket';
$hesk_settings['admin_dir']='staff';
$hesk_settings['attach_dir']='attachments';
$hesk_settings['max_listings']=20;
$hesk_settings['print_font_size']=12;
$hesk_settings['autoclose']=7;
$hesk_settings['max_open']=0;
$hesk_settings['new_top']=0;
$hesk_settings['reply_top']=0;

// --> Features
$hesk_settings['autologin']=1;
$hesk_settings['autoassign']=1;
$hesk_settings['custclose']=1;
$hesk_settings['custopen']=1;
$hesk_settings['rating']=1;
$hesk_settings['cust_urgency']=1;
$hesk_settings['sequential']=1;
$hesk_settings['time_worked']=1;
$hesk_settings['spam_notice']=1;
$hesk_settings['list_users']=0;
$hesk_settings['debug_mode']=0;
$hesk_settings['short_link']=0;
$hesk_settings['select_cat']=0;
$hesk_settings['select_pri']=0;

// --> SPAM Prevention
$hesk_settings['secimg_use']=1;
$hesk_settings['secimg_sum']='GVNGHH4U3U';
$hesk_settings['recaptcha_use']=0;
$hesk_settings['recaptcha_public_key']='';
$hesk_settings['recaptcha_private_key']='';
$hesk_settings['question_use']=0;
$hesk_settings['question_ask']='What color is snow? (give a 1 word answer to show you are a human)';
$hesk_settings['question_ans']='white';

// --> Security
$hesk_settings['attempt_limit']=6;
$hesk_settings['attempt_banmin']=60;
$hesk_settings['reset_pass']=1;
$hesk_settings['email_view_ticket']=1;

// --> Attachments
$hesk_settings['attachments']=array (
'use' => 1,
'max_number' => 2,
'max_size' => 1048576,
'allowed_types' => array('.gif','.jpg','.png','.zip','.rar','.csv','.doc','.docx','.xls','.xlsx','.txt','.pdf')
);


// ==> KNOWLEDGEBASE

// --> Knowledgebase settings
$hesk_settings['kb_enable']=1;
$hesk_settings['kb_wysiwyg']=1;
$hesk_settings['kb_search']=2;
$hesk_settings['kb_search_limit']=10;
$hesk_settings['kb_views']=0;
$hesk_settings['kb_date']=0;
$hesk_settings['kb_recommendanswers']=1;
$hesk_settings['kb_rating']=1;
$hesk_settings['kb_substrart']=200;
$hesk_settings['kb_cols']=2;
$hesk_settings['kb_numshow']=3;
$hesk_settings['kb_popart']=6;
$hesk_settings['kb_latest']=6;
$hesk_settings['kb_index_popart']=3;
$hesk_settings['kb_index_latest']=3;
$hesk_settings['kb_related']=5;


// ==> EMAIL

// --> Email sending
$hesk_settings['smtp']=0;
$hesk_settings['smtp_host_name']='mail.domain.com';
$hesk_settings['smtp_host_port']=25;
$hesk_settings['smtp_timeout']=20;
$hesk_settings['smtp_ssl']=0;
$hesk_settings['smtp_tls']=0;
$hesk_settings['smtp_user']='';
$hesk_settings['smtp_password']='';

// --> Email piping
$hesk_settings['email_piping']=0;

// --> POP3 Fetching
$hesk_settings['pop3']=0;
$hesk_settings['pop3_job_wait']=15;
$hesk_settings['pop3_host_name']='mail.domain.com';
$hesk_settings['pop3_host_port']=110;
$hesk_settings['pop3_tls']=0;
$hesk_settings['pop3_keep']=0;
$hesk_settings['pop3_user']='';
$hesk_settings['pop3_password']='';

// --> Email loops
$hesk_settings['loop_hits']=5;
$hesk_settings['loop_time']=300;

// --> Detect email typos
$hesk_settings['detect_typos']=1;
$hesk_settings['email_providers']=array('gmail.com','hotmail.com','hotmail.co.uk','yahoo.com','yahoo.co.uk','aol.com','aol.co.uk','msn.com','live.com','live.co.uk','mail.com','googlemail.com','btinternet.com','btopenworld.com');

// --> Notify customer when
$hesk_settings['notify_new']=1;
$hesk_settings['notify_skip_spam']=1;
$hesk_settings['notify_spam_tags']=array('Spam?}','***SPAM***','[SPAM]','SPAM-LOW:','SPAM-MED:');
$hesk_settings['notify_closed']=1;

// --> Other
$hesk_settings['strip_quoted']=1;
$hesk_settings['eml_req_msg']=0;
$hesk_settings['save_embedded']=1;
$hesk_settings['multi_eml']=0;
$hesk_settings['confirm_email']=0;
$hesk_settings['open_only']=1;


// ==> TICKET LIST

$hesk_settings['ticket_list']=array('trackid','lastchange','name','subject','status','lastreplier');

// --> Other
$hesk_settings['submittedformat']='2';
$hesk_settings['updatedformat']='2';


// ==> MISC

// --> Date & Time
$hesk_settings['diff_hours']=0;
$hesk_settings['diff_minutes']=0;
$hesk_settings['daylight']=1;
$hesk_settings['timeformat']='Y-m-d H:i:s';

// --> Other
$hesk_settings['ip_whois']='http://whois.domaintools.com/{IP}';
$hesk_settings['maintenance_mode']=0;
$hesk_settings['alink']=1;
$hesk_settings['submit_notice']=0;
$hesk_settings['online']=0;
$hesk_settings['online_min']=10;
$hesk_settings['check_updates']=1;


// ==> CUSTOM FIELDS

$hesk_settings['custom_fields']=array (
'custom1'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 1','maxlen'=>255,'value'=>''),
'custom2'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 2','maxlen'=>255,'value'=>''),
'custom3'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 3','maxlen'=>255,'value'=>''),
'custom4'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 4','maxlen'=>255,'value'=>''),
'custom5'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 5','maxlen'=>255,'value'=>''),
'custom6'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 6','maxlen'=>255,'value'=>''),
'custom7'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 7','maxlen'=>255,'value'=>''),
'custom8'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 8','maxlen'=>255,'value'=>''),
'custom9'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 9','maxlen'=>255,'value'=>''),
'custom10'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 10','maxlen'=>255,'value'=>''),
'custom11'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 11','maxlen'=>255,'value'=>''),
'custom12'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 12','maxlen'=>255,'value'=>''),
'custom13'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 13','maxlen'=>255,'value'=>''),
'custom14'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 14','maxlen'=>255,'value'=>''),
'custom15'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 15','maxlen'=>255,'value'=>''),
'custom16'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 16','maxlen'=>255,'value'=>''),
'custom17'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 17','maxlen'=>255,'value'=>''),
'custom18'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 18','maxlen'=>255,'value'=>''),
'custom19'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 19','maxlen'=>255,'value'=>''),
'custom20'=>array('use'=>0,'place'=>0,'type'=>'text','req'=>0,'name'=>'Custom field 20','maxlen'=>255,'value'=>'')
);

#############################
#     DO NOT EDIT BELOW     #
#############################
$hesk_settings['hesk_version']='2.6.7';
if ($hesk_settings['debug_mode'])
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(0);
}
if (!defined('IN_SCRIPT')) {die('Invalid attempt!');}