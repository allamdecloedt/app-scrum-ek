<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['api/login'] = 'api/Admin/login';
$route['api/menu'] = 'api/Admin/menu';


$route['api/edit'] = 'api/Admin/editProfile';

$route['api/editPassword'] = 'api/Admin/updatePassword';


$route['api/getDashboard'] = 'api/Admin/get_dashboard_data';


$route['api/getexpenses/(:num)'] = 'api/Admin/expense/$1';


$route['api/getinvoices/(:num)'] = 'api/Admin/invoices/$1';


$route['api/onlineadmission'] = 'api/Admin/onlineadmission';
$route['api/onlineadmissionList/(:num)'] = 'api/Admin/onlineadmissionList/$1';
$route['api/onlineadmissionDelete/(:num)'] = 'api/Admin/onlineadmissionDelete/$1';
$route['api/onlineadmissionListApproved/(:num)'] = 'api/Admin/onlineadmissionListApproved/$1';
$route['api/onlineadmissionListApprovedAndDesapproved/(:num)'] = 'api/Admin/onlineadmissionListApprovedAndDesapproved/$1';
$route['api/approveOnlineAdmissions/(:num)']['PUT'] = 'api/Admin/approveOnlineAdmissions/$1';
$route['api/deactivate/(:num)']['PUT'] = 'api/Admin/deactivate/$1';
$route['api/activate/(:num)']['PUT'] = 'api/Admin/activate/$1';
$route['api/onlineadmissionEdit/(:num)']['PUT'] = 'api/Admin/onlineadmissionEdit/$1';
$route['api/onlineadmission'] = 'api/Admin/onlineadmission';
$route['api/fetchStudentsByName/(:num)/(:any)'] = 'api/Admin/fetchStudentsByName/$1/$2';

$route['api/school/create']= 'api/Admin/online_admission_school'; 
$route['api/school/update'] = 'api/Admin/update_school';
$route['api/school/delete/(:num)'] = 'api/Admin/delete_school/$1'; 
$route['api/schools'] = 'api/Admin/schools';
$route['api/categoriesByName']= 'api/Admin/categoriesByName'; 
$route['api/schools_by_category']= 'api/Admin/schools_by_category'; 
$route['api/search_schools']= 'api/Admin/search_schools'; 



$route['api/create_admin']= 'api/Admin/create_admin'; 
$route['api/get_admin']= 'api/Admin/all_admins';
$route['api/fetch_admins']= 'api/Admin/fetch_admins_by_name';
$route['api/schoolsName']= 'api/Admin/all_school_names';
$route['api/editAdmin/(:num)']= 'api/Admin/edit_admin/$1';
$route['api/deleteAdmin/(:num)']= 'api/Admin/Deladmin/$1';

$route['api/teachers']= 'api/Admin/all_teacher';
$route['api/departments']= 'api/Admin/department';
$route['api/teachers/search'] = 'api/Admin/search';
$route['api/teachers/Create'] = 'api/Admin/create_teacher';
$route['api/teachers/edit/(:num)'] = 'api/Admin/edit_teacher/$1';
$route['api/teachers/delete/(:num)'] = 'api/Admin/delete_teacher/$1';
$route['api/teachersById/(:num)'] = 'api/Admin/teacher_by_id/$1';
$route['api/teacherPermission']= 'api/Admin/add_teacher_permission';
$route['api/classes']= 'api/Admin/classes';
$route['api/teachers_by_class/(:num)'] = 'api/Admin/teachers_by_class/$1';
$route['api/assign_teacher_permission_to_class']= 'api/Admin/assign_teacher_permission_to_class';
$route['api/get_class_id_by_name']= 'api/Admin/get_class_id_by_name';






$route['api/GetPaymentSettings/(:num)'] = 'api/Admin/payment_settings/$1';
$route['api/CreateClass'] = 'api/Admin/class_create';
$route['api/GetClass'] = 'api/Admin/class';
$route['api/UpdateClass'] = 'api/Admin/class_update';
$route['api/DeleteClass'] = 'api/Admin/class_del';
$route['api/SubmitQuizResponse'] = 'api/Admin/submit_quiz_responses';

$route['api/Instructor'] = 'api/Admin/all_teacher_names';
$route['api/subjects'] = 'api/Admin/subjects_names';
$route['api/CreateCourse']  = 'api/Admin/create_course';
$route['api/StatusCourse'] = 'api/Admin/course_status_counts';
$route['api/courses_by_class/(:any)'] = 'api/Admin/courses_by_class/$1';
$route['api/coursesByTeacher/(:any)'] = 'api/Admin/courses_by_user/$1';
$route['api/CoursesByStatus/(:any)'] = 'api/Admin/courses_by_status/$1';
$route['api/AllCourses'] = 'api/Admin/all_courses';
$route['api/LessonsAndSections/(:num)'] = 'api/Admin/lessons_and_sections/$1';
$route['api/Course/ChangeStatus/(:num)'] = 'api/Admin/change_course_status/$1';
$route['api/EditCourse/(:num)']  = 'api/Admin/edit_course/$1';
$route['api/GetCourse/(:num)']  = 'api/Admin/course_details/$1';
$route['api/DeleteCourse/(:num)']  = 'api/Admin/delete_course/$1';
$route['api/course/(:num)/add_section'] = 'api/Admin/add_course_section/$1';
$route['api/course/(:num)/sections'] = 'api/Admin/sections/$1';
$route['api/section/(:num)/quizzes'] = 'api/Admin/quizzes_by_section/$1';
$route['api/course/(:num)/quizzes'] = 'api/Admin/quizzes_by_course/$1';
$route['api/quizzes/course/(:num)'] = 'api/Admin/quizzes/$1';
$route['api/quizzes/section/(:num)'] = 'api/Admin/quizzes/null/$1';
$route['api/course/(:num)/section/(:num)/edit'] = 'api/Admin/sections_title/$1/$2';
$route['api/course/(:num)/section/(:num)/delete'] = 'api/Admin/sections_del/$1/$2';
$route['api/update_quiz_order/(:num)'] = 'api/Admin/update_quiz_order/$1';
$route['api/quizzes/AddQuiz'] = 'api/Admin/add_quiz';
$route['api/quizzes/UpdateQuiz'] = 'api/Admin/update_quiz';
$route['api/quizzes/delete'] = 'api/Admin/delete_quiz';
$route['api/quizzes/AddQuestion'] = 'api/Admin/add_question';
$route['api/quizzes/GetQuestions/(:num)'] = 'api/Admin/get_quiz_questions/$1';
$route['api/quizzes/EditQuestions'] = 'api/Admin/edit_question';
$route['api/quizzes/DeleteQuestions'] = 'api/Admin/delete_question';
$route['api/lesson/create'] = 'api/Admin/add_lesson';
$route['api/AllLessons/(:num)'] = 'api/Admin/all_lesson_types/$1';
$route['api/AffectUserToSchool']='api/Admin/associate_user_with_school';
$route['api/GetStudentsList']='api/Admin/get_students_list';
$route['api/ApproveStudent']='api/Admin/approve_student';
$route['api/GetSectionsList'] = 'api/Admin/section';
$route['api/DeleteStudent'] = 'api/Admin/delete_student';
$route['api/NumberStudentOnlineAdmission'] = 'api/Admin/count_student_online_admission';
$route['api/GetStudentIdByUserId'] = 'api/Admin/get_student_id';
$route['api/GetSectionsForClass'] = 'api/Admin/sections_for_class';
$route['api/GetUserName/(:num)']='api/Admin/user_name_by_student_id/$1';





$route['api/CreateSubject'] = 'api/Admin/create_subject';
$route['api/GetClassBySchoolId/(:num)'] = 'api/Admin/get_classes_by_school_id/$1';
$route['api/GetSubjectsBySchoolId/(:num)'] = 'api/Admin/get_subjects_by_school_id/$1';
$route['api/GetCorrectAnswers/(:num)'] = 'api/Admin/all_quiz_responses/$1 ';



$route['api/CreateEvent'] = 'api/Admin/create_event';
$route['api/GetEvent/(:num)']= 'api/Admin/events_by_school_id/$1';
$route['api/DeleteEvent/(:num)']= 'api/Admin/delete_event/$1';
$route['api/EditEvent/(:num)']= 'api/Admin/edit_event/$1';




$route['api/GetExams/(:num)/(:num)'] = 'api/Admin/exams_by_school_id/$1/$2';
$route['api/CreateExams'] = 'api/Admin/create_exam';
$route['api/EditExams'] = 'api/Admin/edit_exam';
$route['api/DeleteExams/(:num)'] = 'api/Admin/delete_exam/$1';



$route['api/GetBooks/(:num)/(:num)'] = 'api/Admin/books_by_school_id/$1/$2';
$route['api/CreateBook'] = 'api/Admin/create_book';
$route['api/EditBook'] = 'api/Admin/edit_book';
$route['api/DeleteBook/(:num)'] = 'api/Admin/delete_book/$1';



$route['api/GetGrades/(:num)/(:num)'] = 'api/Admin/grades_by_school_id/$1/$2';
$route['api/CreateGrade'] = 'api/Admin/create_grade';
$route['api/EditGrade'] = 'api/Admin/edit_grade';
$route['api/DeleteGrade/(:num)'] = 'api/Admin/delete_grade/$1';



$route['api/CreateDepartment'] = 'api/Admin/create_department';
$route['api/GetDepartments/(:num)']= 'api/Admin/departments_by_school_id/$1';
$route['api/UpdateDepartment'] = 'api/Admin/update_department';
$route['api/DeleteDepartment'] = 'api/Admin/delete_department';


$route['api/getexpenses/(:num)'] = 'api/Admin/expense/$1';
$route['api/CreateExpenseCategory'] = 'api/Admin/create_expense_category';
$route['api/EditExpenseCategory'] = 'api/Admin/edit_expense_category';
$route['api/DeleteExpenseCategory'] = 'api/Admin/delete_expense_category';
$route['api/GetExpenseCategories/(:num)/(:num)/(:num)'] = 'api/Admin/expense_categories/$1/$2/$3';
$route['api/GetExpenses/(:num)'] = 'api/Admin/get_expenses/$1';
$route['api/CreateExpense'] = 'api/Admin/create_expense';
$route['api/EditExpense'] = 'api/Admin/edit_expense';
$route['api/DeleteExpense'] = 'api/Admin/delete_expense';


$route['api/GetSessions'] = 'api/Admin/sessions';
$route['api/CreateSession'] = 'api/Admin/create_session';
$route['api/EditSession'] = 'api/Admin/edit_session';
$route['api/DeleteSession/(:num)'] = 'api/Admin/delete_session/$1';



$route['api/createInvoice'] = 'api/Admin/create_invoice';
$route['api/PaidInvoice'] = 'api/Admin/paid_invoice';
$route['api/getInvoiceStatus'] = 'api/Admin/invoice_status';




$route['api/AddMarks'] = 'api/Admin/add_marks';
$route['api/FilterExams/(:num)'] = 'api/Admin/filter_exams_by_school_id/$1';
$route['api/FilterStudents'] = 'api/Admin/filter_student';
$route['api/GetSectionsByClassId/(:num)'] = 'api/Admin/get_sections_by_class_id/$1';
$route['api/GetSubjectIdByName/(:any)'] = 'api/Admin/get_subject_id_by_name/$1';
$route['api/UpdateMarks'] = 'api/Admin/update_marks';

$route['api/CreateClassRoom'] = 'api/Admin/add_class_room';
$route['api/UpdateClassRoom/(:num)'] = 'api/Admin/update_class_room/$1';
$route['api/DeleteClassRoom/(:num)'] = 'api/Admin/delete_class_room/$1';
$route['api/GetClassRoom/(:num)'] = 'api/Admin/get_class_room/$1';


$route['api/CreateRoutine'] = 'api/Admin/create_routine';
$route['api/UpdateRoutine/(:num)'] = 'api/Admin/update_routine/$1';
$route['api/DeleteRoutine/(:num)'] = 'api/Admin/delete_routine/$1';
$route['api/GetRoutine/(:num)'] = 'api/Admin/routines_by_school_id/$1';
$route['api/GetRoutineByClassAndSection/(:num)/(:num)'] = 'api/Admin/routines_by_class_and_section/$1/$2';



$route['api/GetInvoice'] = 'api/Admin/invoice_by_date_range';
$route['api/invoices/parent'] = 'api/Admin/get_invoice_by_parent_id';
$route['api/invoices/CreateSingleInvoice'] = 'api/Admin/create_single_invoice';
$route['api/invoices/CreateMassInvoice'] = 'api/Admin/create_mass_invoice';
$route['api/invoices/update/(:num)'] = 'api/Admin/update_invoice/$1';
$route['api/invoices/delete/(:num)'] = 'api/Admin/delete_invoice/$1';
$route['api/GetStudentFreeFilter'] = 'api/Admin/invoices_by_filter';
$route['api/Getclasses']= 'api/Admin/classe';
















$route['api/GetAppropriateCourses'] = 'api/Admin/get_appropriate_courses';


//Partie Quiz (stocker les reponses dans la table question_quiz)
$route['api/SubmitQuiz'] = 'api/Admin/submit_quiz';
$route['api/checkProgress/(:num)/(:num)'] = 'api/Admin/check_progress/$1/$2';

// Forget Password
$route['api/VerifyEmail'] = 'api/Admin/send_reset_link_api';
$route['api/ResendCode'] =  'api/Admin/resend_code_api';
$route['api/VerifyCode'] =  'api/Admin/verify_code_api';
$route['api/GetUserIdByEmail'] = 'api/Admin/getUserIdByEmail';
$route['api/UpdatePassword'] = 'api/Admin/update_Password';

//register 
$route['api/Register']  = 'api/Admin/register';












$route['api/SetSmtpSettings'] = 'api/Admin/set_smtp_settings';
$route['api/GetSmtpSettings'] = 'api/Admin/get_smtp_settings';
$route['api/GetAllSmtpSettings'] = 'api/Admin/get_alls_smtp_settings';



$route['api/SetPaymentSettings'] = 'api/Admin/set_payment_settings';

$route['api/UpdatePaypalSettings'] = 'api/Admin/update_paypal_settings';
$route['api/UpdateSystemCurrency'] = 'api/Admin/update_system_currency';
$route['api/UpdateStripeSettings'] = 'api/Admin/update_stripe_settings';
$route['api/GetSchoolSettings/(:num)'] = 'api/Admin/school_settings/$1';
$route['api/UpdateSchoolSettings/(:num)'] = 'api/Admin/school_settings_update/$1';
$route['api/GetSystemSettings/(:num)'] = 'api/Admin/system_settings/$1';
$route['api/UpdateSystemSettings/(:num)'] = 'api/Admin/update_system_settings/$1';


$route['api/GetSystemLogo/(:num)'] = "api/Admin/system_logo/$1";
$route['api/UpdateSystemLogo/(:num)'] = "api/Admin/update_system_logo/$1";


$route['api/GetLanguage/(:num)'] = 'api/Admin/language/$1';
$route['api/GetSelectedLanguage/(:num)'] = 'api/Admin/selected_language/$1';
$route['api/AddLanguage/(:num)'] = 'api/Admin/add_language/$1';
$route['api/UpdateLanguage/(:num)'] = 'api/Admin/update_language/$1';
$route['api/GetPhrases/(:any)'] = 'api/Admin/phrases/$1';
$route['api/GetWebsiteSettings/(:num)'] = 'api/Admin/website_settings/$1';
$route['api/GetGeneralSettings'] = 'api/Admin/general_settings';
$route['api/GetOthersSettings'] = 'api/Admin/other_settings';
$route['api/UpdateOthersSettings'] = 'api/Admin/other_settings_update';

$route['api/GetTermsAndConditionsSettings'] = 'api/Admin/terms_and_conditions_settings';
$route['api/UpdateTermsAndConditionsSettings'] = 'api/Admin/upterms_and_conditions_settings';

$route['api/GetPrivacyPolicySettings'] = 'api/Admin/privacy_policy_settings';
$route['api/UpdatePrivacyPolicySettings'] = 'api/Admin/privacy_policy_settings_update';

$route['api/CreateGallery'] = 'api/Admin/create_gallery';
$route['api/GetGalleries'] = 'api/Admin/galleries';
$route['api/GetGalleryImage'] = 'api/Admin/gallery_images_by_id';
$route['api/DeleteGallery'] = 'api/Admin/gallery_by_id';
$route['api/DeleteGalleryImage'] = 'api/Admin/gallery_image_by_id';

$route['api/GetHomePageSlider'] = 'api/Admin/homepage_slider';
$route['api/UpdateSliders'] = 'api/Admin/update_sliders';

















// Noticeboard Routes under Admin Controller
$route['api/notices/create'] = 'api/Admin/create_noticeboard'; // Create a new notice
$route['api/noticeboard/get/(:num)'] = 'api/Admin/noticeboard/$1'; // Fetch a single notice by ID
$route['api/allnoticeboard'] = 'api/Admin/fetch_all_notices'; // Fetch all notices
$route['api/notices/update/(:num)'] = 'api/Admin/update_noticeboard/$1'; // Update a notice by ID
$route['api/notices/delete/(:num)'] = 'api/Admin/noticeboard_delete/$1'; // Delete a notice by ID
$route['api/notices/filter/(:num)'] = 'api/Admin/filter_notices/$1';

// Route for filtering by year and month
$route['api/notices/filter/(:num)/(:num)'] = 'api/Admin/filter_notices/$1/$2';

// Route for filtering by year, month, and day
$route['api/notices/filter/(:num)/(:num)/(:num)'] = 'api/Admin/filter_notices/$1/$2/$3';
$route['api/GetDays'] = 'api/Admin/unique_notice_days';





$route['api/sessions'] = 'api/Admin/session';
$route['api/StudentsForPromotion'] = 'api/Admin/students_for_promotion';
$route['api/GetPromotedClasses'] = 'api/Admin/classes_promote';
$route['api/PromoteStudent'] = 'api/Admin/promote_student';



$route['api/GetQuizResult'] = 'api/Admin/quiz_result';
$route['api/GetQuizByClass'] = 'api/Admin/quizzes_by_class';
$route['api/GetQuizzesNames'] = 'api/Admin/all_quizzes';





// Route for filtering attendance
$route['api/attendance/filter'] = 'api/Admin/filter_attendance';
$route['api/attendance/student_list'] = 'api/Admin/student_list';
$route['api/attendance/CreateAttendance'] = 'api/Admin/create_attendance';
$route['api/attendance/bulk_update'] = 'api/Admin/bulk_attendance_update';
$route['api/attendance/monthly_summary'] = 'api/Admin/monthly_attendance_summary';
$route['api/attendance/UpdateAttendanceStatus'] = 'api/Admin/update_attendance_status';
$route['api/attendance/ToggleAttendanceStatus'] = 'api/Admin/toggle_attendance_status';






















//Route of Session Manager 
$route['api/GetSessionManager/list'] = 'api/Admin/list';
$route['api/CreateSessionManager/create'] = 'api/Admin/create';
$route['api/UpdateSessionManager/(:num)'] = 'api/Admin/update/$1';
$route['api/DeleteSessionManager/delete/(:num)'] = 'api/Admin/delete/$1';
$route['api/ActivateSessionManager/activate/(:num)'] = 'api/Admin/activate/$1';
$route['api/DesactivateSessionManager/deactivate/(:num)'] = 'api/Admin/deactivate/$1';





//Route of Accountant 
$route['api/GetAccountant'] = 'api/Admin/accountant_users';
$route['api/CreateAccountant/create'] = 'api/Admin/create_accountant';
$route['api/UpdateAccountant/update/(:num)'] = 'api/Admin/update_accountant/$1';
$route['api/DeleteAccountant/delete/(:num)'] = 'api/Admin/delete_accountant/$1';










$route['api/GetLibrarians'] = 'api/Admin/all_librarians';
$route['api/CreateLibrarian/create'] = 'api/Admin/create_librarian';
$route['api/UpdateLibrarian/update/(:num)'] = 'api/Admin/update_librarian/$1';
$route['api/DeleteLibrarian/delete/(:num)'] = 'api/Admin/delete_librarian/$1';















$route['api/student/DownloadCsv'] = 'api/Admin/download_csv';




















// Route for syllabus operations
$route['syllabus/(:any)'] = 'api/Admin/syllabus_operations/$1'; // Handles create, delete operations
$route['syllabus/(:any)/(:num)/(:num)'] = 'api/Admin/syllabus_operations/$1/$2/$3'; // Handles get operation
$route['api/GetSyllabus/(:any)/(:num)'] = 'api/Admin/syllabus_by_class_section/$1/$2'; // Handles delete operation by ID
$route['api/CreateSyllabus'] = 'api/Admin/create_syllabus';
$route['api/DeleteSyllabus'] = 'api/Admin/delete_syllabus';


















$route['api/GetBookIssue'] = 'api/Admin/book_issues';
$route['api/CreateBookIssue'] = 'api/Admin/create_book_issue';
$route['api/EditBookIssue/(:num)'] = 'api/Admin/update_book_issue/$1';
$route['api/ReturnBookIssue/(:num)'] = 'api/Admin/return_book_issue/$1';
$route['api/DeleteBookIssue/(:num)'] = 'api/Admin/delete_book_issue/$1';
$route['api/GetBooksBySchool/(:num)'] = 'api/Admin/books_by_school/$1';
$route['api/ClassesBySchool/(:num)'] = 'api/Admin/classes_by_school/$1';
$route['api/StudentsBySchool/(:num)'] = 'api/Admin/students_by_school/$1';



















$route['api/GetAddons'] = 'api/Admin/addon';
$route['api/CreateAddon'] = 'api/Admin/create_addon';
$route['api/RemoveAddon/(:num)'] = 'api/Admin/remove_addon/$1';










$route['api/SchoolsNotApproved'] = 'api/Admin/not_approved_schools';
$route['api/ApprovedSchool/(:num)'] = 'api/Admin/approve_school/$1';
$route['api/DelSchool/(:num)'] = 'api/Admin/del_school/$1';
$route['api/NumberSchoolsNotApproved'] = 'api/Admin/count_not_approved_schools';
$route['api/FilterStudents/(:num)/(:num)'] = 'api/Admin/students_by_class_and_section/$1/$2';





















$route['api/AddLessonYoutubeVideo'] = 'api/Admin/add_lesson_youtube';
$route['api/GetYoutubeVideoDuration'] = 'api/Admin/get_youtube_video_duration';
$route['api/DisplayYoutubeVideo'] = 'api/Admin/display_youtube';
$route['api/UpdateLessonYoutubeVideo'] = 'api/Admin/update_lesson_youtube';
$route['api/GetLessonDetails'] = 'api/Admin/get_lesson_details';
$route['api/DeleteLesson'] = 'api/Admin/delete_lesson';
$route['api/GetLessonVideoType'] = 'api/Admin/lesson_video_type';
$route['api/UpdateLessonDevice'] = 'api/Admin/update_lesson_device';
$route['api/AddOthersLessons'] = 'api/Admin/add_lesson_with_attachment';
$route['api/UpdateOthersLessons'] = 'api/Admin/update_lesson_with_attachment';


$route['meeting/create'] = 'meeting/create';
$route['meeting/join'] = 'meeting/join';


$route['test-meeting/start'] = 'TestMeeting/start';
$route['test-meeting/join'] = 'TestMeeting/joinAsAttendee';
