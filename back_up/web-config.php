<?php
define('USER_PANEL_TITLE', 'Swig TV');
define('ADMIN_PANEL_TITLE', 'Swig TV | ');
define('DASHBOARD_WELCOME_TXT', 'Welcome Back, Swig Admin Panel');
define('NO_REPLY_EMAIL', 'no-reply@swig.tv');
define('FROM_NAME', 'Swig TV');
define('SIGNATURE', 'Swig TV Team');
define('TOKEN_SALT', 'E0xyJXmAPmeScqWrVLXX2L1ukycGqrtlMpx8gDKhe5wP3QUXNZmuyC9kNCaV');

// HERE DEFINE CONTS DATE
define('DATE_FORMAT_SPLITTER', '-');
define('SHORT_DATE_FORMAT', 'd-m-Y');
define('LONG_DATE_FORMAT', 'd-m-Y H:i:s');
define('SHORT_MYSQL_DATE_FORMAT', 'Y-m-d');
define('MYSQL_DATE_CONVERSION_STYLE', 'EU');
define('LONG_MYSQL_DATE_FORMAT', 'Y-m-d H:i:s');
define('SET_PROJECT_TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(SET_PROJECT_TIMEZONE);

// HERE DEFINE COMMON MSG
define('USER_KEY_MSG', 'Sorry, user key does not match.');
define('UNAUTHORIZED_MSG', 'Unauthorized access denied.');
define('USER_KEY_NOT_RECEIVE_MSG', 'Sorry, user key does not receive.');
define('APP_ID_NOT_EXIST_MSG', 'Sorry, App Id does not exists.');
define('USER_EMAIL_MSG', 'Please enter valid email address.');
define('USER_EMAIL_EXIST_MSG', 'Sorry, email address already exists, please try another.');
define('USER_NAME_EXIST_MSG', 'Sorry, username already exists, please try another.');
define('USER_EMAIL_NOT_FOUND_DB_MSG', 'Sorry, given email address is not present in our record.');
define('USER_PIN_CONFIRM_NOT_MATCH', 'Sorry, confirm password does not match.');
define('USER_PIN_OLD_NOT_FOUND_DB_MSG', 'Sorry, given current password does not match with our record.');
define('USER_EMAIL_PIN_NOT_FOUND_DB_MSG', 'Sorry, invalid email address or password.');
define('USER_EMAIL_USERNAME_MSG', 'Sorry, given email address or username is not present in our record.');


$cpYearInfo = '2019';
if (date('Y') != $cpYearInfo) $cpYearInfo = $cpYearInfo.'-'.date('Y');

$ARR_MENU_TYPE = array('L' => 'Live', 'V' => 'VOD');
$STATUS = array("A" => "Active", "I" => "Inactive");
$DEFAULT_SELECTION_MENU = array("N" => "No", "Y" => "Yes");
$ARR_IS_PREMIUM = array("N" => "No", "Y" => "Yes");
$ARR_STREAM_ENTRY_BY = array("M" => "By Manual", "F" => "By Feed URL");
$ARR_SUBSCRIPTION_EXPIRED = array("mintue" => "Mintue", "hour" => "Hour", 'day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year');
$ARR_RATING = array(1 => 'G', 2 => 'PG', 3 => 'PG13', 4 => 'NC-17', 5 => 'R');
$ARR_REVIEW = array(1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5');


$ARR_ACTIVITES = array (
				'login' => 'Login',
				'updateYourProfile' => 'Your Profile Updated',
				'changePassword' => 'Your Password Changed',
				'updateSocialMediaInfo' => 'SocialMedia Info Updated',
				'updateWebPrimaryInfo' => 'Web Primary Info Updated',
				'updatePage' => 'Page Detail Updated',
				'addPage' => 'New Page Added',								
				'addBlog' => 'New Blog Added',				
				'updateBlog' => 'Blog Detail Updated',				
				'deleteBlog' => 'Blog Detail Deleted',
				'changeBlogStatus' => 'Blog Status Changed',
				'addTestimonial' => 'New Testimonial Added',				
				'updateTestimonial' => 'Testimonial Detail Updated',				
				'deleteTestimonial' => 'Testimonial Detail Deleted',
				'changeTestimonialStatus' => 'Testimonial Status Changed',
				'addPortfolio' => 'New Portfolio Added',				
				'updatePortfolio' => 'Portfolio Detail Updated',				
				'deletePortfolio' => 'Portfolio Detail Deleted',
				'changePortfolioStatus' => 'Portfolio Status Changed',
				'addBlogCategory' => 'New Blog Category Added',				
				'updateBlogCategory' => 'Blog Category Detail Updated',				
				'deleteBlogCategory' => 'Blog Category Detail Deleted',
				'changeBlogCategoryStatus' => 'Blog Category Status Changed',
			);


define('COPY_RIGHT_INFO', "&copy;Swig $cpYearInfo. ALL RIGHTS RESERVED.");

if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '10.1.1.5')
{
	define('HTTP_PATH', 'http://10.1.1.5/vijay/swig');
	define('HARD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/vijay/swig');
	//ini_set('display_errors', 1);
} 
else
{
	define('HTTP_PATH', 'http://ec2-3-80-207-244.compute-1.amazonaws.com');
	define('HARD_PATH', $_SERVER['DOCUMENT_ROOT'] . '');
	ini_set('display_errors', 0);
}

##############################DO NOT TOUCH BELOW THIS LINE##############################
ob_start();
@session_start();
?>
