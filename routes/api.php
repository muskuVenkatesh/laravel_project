<?php
use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\API\IdController;
use App\Http\Controllers\API\PDFController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ExamController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\LeaveController;
use App\Http\Controllers\API\MarksController;
use App\Http\Controllers\API\PilotController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\StaffController;
use App\Http\Controllers\API\StateController;
use App\Http\Controllers\API\StepsController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\MediumController;
use App\Http\Controllers\API\ParentController;
use App\Http\Controllers\API\SchoolController;
use App\Http\Controllers\API\ClassesController;
use App\Http\Controllers\API\LibBookController;
use App\Http\Controllers\API\RemarksController;
use App\Http\Controllers\API\SectionController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\DistrictController;
use App\Http\Controllers\API\FeesTypeController;
use App\Http\Controllers\API\HolidaysController;
use App\Http\Controllers\API\HomeworkController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\SubjectsController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ExamGradeController;
use App\Http\Controllers\API\TransportController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\ExamConfigController;
use App\Http\Controllers\API\OccupationController;
use App\Http\Controllers\API\CertificateController;
use App\Http\Controllers\API\DepartmentsController;
use App\Http\Controllers\API\FeesPayTypeController;
use App\Http\Controllers\API\PersonalityController;
use App\Http\Controllers\API\SmsTemplateController;
use App\Http\Controllers\API\FeesAcademicController;
use App\Http\Controllers\API\FeesTimelineController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\BranchSubjectController;
use App\Http\Controllers\API\LibBookReviewController;
use App\Http\Controllers\API\QualificationController;
use App\Http\Controllers\API\SchoolSettingController;
use App\Http\Controllers\API\AcademicDetailController;
use App\Http\Controllers\API\AdmissionFormsController;
use App\Http\Controllers\API\ExamMarksEntryController;
use App\Http\Controllers\API\ExamReportLockController;
use App\Http\Controllers\API\TransportRouteController;
use App\Http\Controllers\API\CertificateDataController;
use App\Http\Controllers\API\InfoCertificateController;
use App\Http\Controllers\API\AdmissionEnquiryController;
use App\Http\Controllers\API\FeesDiscountTypeController;
use App\Http\Controllers\API\HomeworkCircularController;
use App\Http\Controllers\API\TransportVehicleController;
use App\Http\Controllers\API\FeesAcademicSetupController;
use App\Http\Controllers\API\StaffNotificationController;
use App\Http\Controllers\API\AdmissionSchedulesController;
use App\Http\Controllers\API\TransportRouteStopController;
use App\Http\Controllers\API\AdmissionAnouncementController;
use App\Http\Controllers\API\FeesAcademicPaymentsController;
use App\Http\Controllers\API\StudentParentDetailsController;
use App\Http\Controllers\API\LibIsssueController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group([

    // 'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('getUserToken', [AuthController::class, 'getUserToken']);
    // Route::get('refresh', [AuthController::class,'refresh']);

});

Route::middleware(['auth:api', CheckRole::class . ':admin,superadmin,parent'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);

    //School & branch Create API
    Route::post('/create-school', [SchoolController::class, 'CreateSchool']);
    Route::post('/update-school', [SchoolController::class, 'UpdateSchool']);
    Route::get('/delete-school/{id}', [SchoolController::class, 'DeleteSchool']);
    Route::post('/upload-schools', [SchoolController::class, 'UploadFile']);

    Route::get('/get-currency', [SchoolController::class, 'GetCurrencyType']);
    Route::post('/upload-branches', [SchoolController::class, 'UploadBranchFile']);
    Route::get('/get-schools', [SchoolController::class, 'GetAllSchool']);
    Route::get('/get-school/{id}', [SchoolController::class, 'GetSingleSchool']);
    Route::post('/upload-schools', [SchoolController::class, 'UploadFile']);
    // Branch Controllers
    Route::get('/get-school-branches/{id}', [BranchController::class, 'GetBranchesByschoolId']);
    Route::post('/create-branch', [BranchController::class, 'BranchStore']);
    Route::get('/get-branch/{branch_id}', [BranchController::class, 'GetSingleBranch']);
    Route::post('/update-branch/{id}', [BranchController::class, 'updateBranchById']);
    Route::delete('/delete-branch/{id}', [BranchController::class, 'deleteBranch']);
    Route::post('/restore-branch/{branch_id}', [BranchController::class, 'BranchRestore']);
    Route::get('/get-active-branches/{school_id}', [BranchController::class, 'ActiveBranches']);

    //School Branch Setting and Branch Meta
    Route::get('/get-school-settings/{id}', [SchoolSettingController::class, 'GetSchoolSetting']);

    //Departments
    Route::get('/getall-departments', [DepartmentsController::class, 'getDepartment']);
    Route::post('/create-department', [DepartmentsController::class, 'createDepartment']);
    Route::get('/get-department/{id}', [DepartmentsController::class, 'getDepartmentByID']);
    Route::post('/update-department/{id}', [DepartmentsController::class, 'updateDepartment']);
    Route::get('/delete-department/{id}', [DepartmentsController::class, 'deleteDepartment']);

    //Classes
    Route::get('/getall-classes', [ClassesController::class, 'getClass']);
    Route::post('/create-class', [ClassesController::class, 'createClass']);
    Route::get('/get-class/{id}', [ClassesController::class, 'getClassByID']);
    Route::post('/update-class/{id}', [ClassesController::class, 'updateClass']);
    Route::get('/delete-class/{id}', [ClassesController::class, 'deleteClass']);
    Route::get('/get-classes-branch/{id}', [ClassesController::class, 'getClassesByBranch']);

    //Subject
    Route::get('/getall-subjects', [SubjectsController::class, 'getSubject']);
    Route::post('/create-subject', [SubjectsController::class, 'createSubject']);
    Route::get('/get-subject/{id}', [SubjectsController::class, 'getSubjectByID']);
    Route::post('/update-subject/{id}', [SubjectsController::class, 'updateSubject']);
    Route::get('/delete-subject/{id}', [SubjectsController::class, 'deleteSubject']);
    Route::get('/get-subject-types', [SubjectsController::class, 'getSubjectTypes']);

    //Mideum
    Route::get('/get-medums', [MediumController::class, 'getMedium']);
    Route::post('/create-medium', [MediumController::class, 'createMedium']);
    Route::get('/get-medium/{id}', [MediumController::class, 'getMediumByID']);
    Route::post('/update-medium/{id}', [MediumController::class, 'updateMedium']);
    Route::get('/delete-medium/{id}', [MediumController::class, 'deleteMedium']);

    // SectionController
    Route::post('/create-section', [SectionController::class, 'StoreSection']);
    Route::get('/getall-sections', [SectionController::class, 'GetAllSections']);
    Route::get('/get-section/{id}', [SectionController::class, 'GetSectionById']);
    Route::post('/update-section/{id}', [SectionController::class, 'update']);
    Route::delete('/delete-section/{id}', [SectionController::class, 'DestroySection']);
    Route::get('/get-sectionbybranch/{id}', [SectionController::class, 'getSectionsByBranch']);
    Route::get('/get-sectionbyclass/{id}', [SectionController::class, 'getSectionByClass']);
    Route::post('/get-sectionbyclassids', [SectionController::class, 'getSectionByClassIds']);

    // AcademicDetailsController
    Route::post('/create-academic', [AcademicDetailController::class, 'StoreAcademicDetails']);
    Route::get('/get-academics', [AcademicDetailController::class, 'GetAllAcademicDetails']);
    Route::get('/get-academic/{id}', [AcademicDetailController::class, 'GetAcademicDetailsById']);
    Route::post('/update-academic/{id}', [AcademicDetailController::class, 'updateAcademicDetails']);
    Route::delete('/delete-academic/{id}', [AcademicDetailController::class, 'DestroyAcademic']);

    // ParentController Api's
    Route::post('/create-parent', [ParentController::class, 'CreateParent']);
    Route::get('/get-parents', [ParentController::class, 'GetParents']);
    Route::get('/get-parent/{id}', [ParentController::class, 'GetSingleParent']);
    Route::post('/update-parent/{id}', [ParentController::class, 'updateParent']);
    Route::delete('/delete-parent/{id}', [ParentController::class, 'DeleteParent']);
    Route::get('/get-parent-bynumber/{num}', [ParentController::class, 'getParentBynumber']);
    Route::get('/get-student-byparent', [ParentController::class, 'getStudentByParent']);

    // Stafff Api's
    Route::get('/get-roles', [RolesController::class, 'getRoles']);
    Route::post('/create-staff', [StaffController::class, 'StoreStaff']);
    Route::get('/get-staff-branch', [StaffController::class, 'GetAllStaff']);
    Route::get('/get-staff/{id}', [StaffController::class, 'GetStaffById']);
    Route::post('/update-staff/{id}', [StaffController::class, 'UpdateStaff']);
    Route::delete('/delete-staff/{id}', [StaffController::class, 'DestroyStaff']);

    // BrnahcSubject
    Route::post('/create-branchsubject', [BranchSubjectController::class, 'storeBranchSubject']);
    Route::get('/get-branchsubject/{id}', [BranchSubjectController::class, 'GetBranchSubject']);
    Route::get('/get-branchsubjects', [BranchSubjectController::class, 'GetAllBranchSubjects']);
    Route::post('/update-branchsubject/{id}', [BranchSubjectController::class, 'updateBranchSubject']);
    Route::delete('/delete-branchsubject/{id}', [BranchSubjectController::class, 'DeleteBranchSubject']);
    Route::get('/get-subject-bybranch/{id}', [BranchSubjectController::class, 'getSubjectByBranch']);
    Route::get('/get-subject-bybranch-class-section', [BranchSubjectController::class, 'getSubjectByBranchClassSection']);
    Route::get('/get-verify-subject', [BranchSubjectController::class, 'getVerifySubject']);
    Route::get('/get-subject-bybranch-class', [BranchSubjectController::class, 'getSubjectByBranchClass']);

    //Qualification
    Route::post('/create-qualification', [QualificationController::class, 'createQualification']);
    Route::get('/get-qualifications', [QualificationController::class, 'getQualifications']);
    Route::get('/get-qualification/{id}', [QualificationController::class, 'GetSingleQualification']);
    Route::post('/update-qualification/{id}', [QualificationController::class, 'updateQualification']);
    Route::delete('/delete-qualification/{id}', [QualificationController::class, 'DeleteQualification']);

    //Occupation
    Route::post('/create-occupation', [OccupationController::class, 'createOccupation']);
    Route::get('/get-occupations', [OccupationController::class, 'getOccupations']);
    Route::get('/get-occupation/{id}', [OccupationController::class, 'GetSingleOccupation']);
    Route::post('/update-Occupation/{id}', [OccupationController::class, 'updateOccupations']);
    Route::delete('/delete-Occupation/{id}', [OccupationController::class, 'deleteOccupation']);

    //Group
    Route::post('/create-group', [GroupController::class, 'createGroup']);
    Route::get('/get-groups-bybranch_id', [GroupController::class, 'getGroups']);
    Route::get('/get-group/{id}', [GroupController::class, 'GetSingleGroup']);
    Route::post('/update-group/{id}', [GroupController::class, 'updateGroup']);
    Route::delete('/delete-group/{id}', [GroupController::class, 'DeleteGroup']);

    // StudentsController
    Route::post('/create-student', [StudentController::class, 'CreateStudent']);
    Route::get('/get-student/{id}', [StudentController::class, 'GetStudent']);
    Route::get('/get-students', [StudentController::class, 'GetStudents']);
    Route::post('/update-student/{id}', [StudentController::class, 'UpdateStudent']);
    Route::delete('/delete-student/{id}', [StudentController::class, 'DeleStudent']);
    Route::get('/get-student-bybranch', [StudentController::class, 'getStudentByBranch']);
    Route::get('/get-student-byclass', [StudentController::class, 'getStudentByClass']);
    Route::post('/upload-students', [StudentController::class, 'UploadFile']);
    Route::get('/get-student-gap-details', [StudentController::class, 'getGapDetails']);

    // inactive Students
    Route::get('/get-inactive-students', [StudentController::class, 'GetInactiveStudents']);
    Route::get('/get-steps/{branch_id}', [StepsController::class, 'GetSteps']);
    Route::get('/get-languages', [LanguageController::class, 'GetLanguages']);
    Route::post('/create-languages', [LanguageController::class, 'createLanguage']);
    Route::get('/get-language/{id}', [LanguageController::class, 'getLanguageByID']);
    Route::post('/update-languages/{id}', [LanguageController::class, 'updateLanguage']);
    Route::delete('/delete-languages/{id}', [LanguageController::class, 'deleteLanguage']);

    // StateApi's
    Route::post('/create-state', [StateController::class, 'crateState']);
    Route::get('/get-state/{id}', [StateController::class, 'getState']);
    Route::get('/get-states', [StateController::class, 'getStates']);
    Route::post('/update-state/{id}', [StateController::class, 'updateState']);
    Route::delete('/delete-state/{id}', [StateController::class, 'deleteState']);

    // DistrictAPI's
    Route::post('/create-dist', [DistrictController::class, 'crateDistrict']);
    Route::get('/get-dists', [DistrictController::class, 'getDistricts']);
    Route::delete('/delete-dist/{id}', [DistrictController::class, 'deleteDistrict']);
    Route::post('/update-dist/{id}', [DistrictController::class, 'updateDistrict']);
    Route::get('/get-dist/{id}', [DistrictController::class, 'getDistrict']);

    //Attendance
    Route::get('/get-otp-verification', [AttendanceController::class, 'getOTPVerification']);
    Route::get('/get-attendance-date', [AttendanceController::class, 'getAttendanceByDate']);
    Route::get('/get-attendance-report', [AttendanceController::class, 'getAttendanceReport']);
    Route::get('/get-attendance-consolidated', [AttendanceController::class, 'getAttendanceConsolidated']);
    Route::post('/create-attendance', [AttendanceController::class, 'storeAttendance']);
    Route::get('/get-attendance-report-bystudentid', [AttendanceController::class, 'getAttendanceReportBystudentId']);

    //Attendance dashboard
    Route::get('/get-attendance-notentered', [AttendanceController::class, 'getAttendanceNotentered']);
    Route::get('/get-attendance-today', [AttendanceController::class, 'getAttendanceToday']);
    Route::get('/get-user-details', [AuthController::class, 'getUsers']);

    //HomeWork
    Route::post('/create-homework', [HomeworkController::class, 'storeHomework']);
    Route::get('/get-homeworks', [HomeworkController::class, 'getHomeworks']);
    Route::get('/get-homework/{id}', [HomeworkController::class, 'getHomework']);

    //Admission Anouncement
    Route::post('/create-admission-announcement', [AdmissionAnouncementController::class, "createAdmissionAnnouncement"]);
    Route::get('/get-admission-announcements', [AdmissionAnouncementController::class, "getAnnouncements"]);
    Route::get('/get-admission-announcement-byid/{id}', [AdmissionAnouncementController::class, 'getAnouncementById']);
    Route::post('/update-admission-announcement/{id}', [AdmissionAnouncementController::class, 'updateAnouncement']);
    Route::delete('/delete-admission-announcement/{id}', [AdmissionAnouncementController::class, 'deleteAnnouncement']);

    //Admission Enqueiry
    Route::post('/create-admission-enquery', [AdmissionEnquiryController::class, 'createAdmissionEnquery']);
    Route::get('/get-admission-enquerys', [AdmissionEnquiryController::class, 'getAdmissionEnquery']);
    Route::get('/get-admission-enquery/{id}', [AdmissionEnquiryController::class, 'getAdmissionEnqueryByid']);
    Route::post('/update-admission-enquery/{id}', [AdmissionEnquiryController::class, 'updateAdmissionEnquery']);
    Route::delete('/delete-admission-enquery/{id}', [AdmissionEnquiryController::class, 'deleteAdmissionEnquery']);
    Route::post('/update-admission-status/{id}', [AdmissionEnquiryController::class, 'updateAmissionStatus']);

    //Notification Logs
    Route::get('/get-notification-logs', [NotificationController::class, "getNotificationlogs"]);

    //Sms Template
    Route::get('get-sms-templates', [SmsTemplateController::class, 'getSmsTemplates']);
    Route::get('get-sms-template/{id}', [SmsTemplateController::class, 'getSmsTemplate']);
    Route::post('create-sms-templates', [SmsTemplateController::class, 'Store']);
    Route::post('update-sms-templates/{id}', [SmsTemplateController::class, 'update']);
    Route::delete('delete-sms-templates/{id}', [SmsTemplateController::class, 'delete']);
    // CircularApi
    Route::post('create-circular', [HomeworkCircularController::class, 'CreateCircular']);

    //Dashboard
    Route::get('/get-dashboard-details', [DashboardController::class, 'getDashboardDetails']);
    Route::get('/get-dashboard-birthday-details', [DashboardController::class, 'getBirthdayDetails']);
    Route::get('/dashboard/homeworkclass-count', [DashboardController::class, 'gethomeworkclasscount']);
    Route::get('/dashboard/birthday-count', [DashboardController::class, 'getBirthdayCount']);
    Route::get('/dashboard/attendanceclass-count', [DashboardController::class, 'getAttendanceclassCount']);
    Route::get('/dashboard/studentpresent-count', [DashboardController::class, 'getStudentPresentCount']);
    Route::get('/dashboard/studentabsent-count', [DashboardController::class, 'getStudentAbsentCount']);
    Route::get('/marks/aggregated-report', [DashboardController::class, 'getAggregatedMarksReport']);


    //staffnotification
    Route::post('/staff-notifications', [StaffNotificationController::class, 'store']);

    //Admission Form
    Route::post('/create-admissionforms', [AdmissionFormsController::class, 'createAadmissionforms']);
    Route::get('/get-admissionforms', [AdmissionFormsController::class, 'getAadmissionforms']);
    Route::get('/get-admissionform/{id}', [AdmissionFormsController::class, 'getAadmissionformbyId']);
    Route::post('/update-admissionform/{id}', [AdmissionFormsController::class, 'updateAadmissionformbyId']);

    //Admission_Schedules
    Route::post('/create-admission-schedule', [AdmissionSchedulesController::class, 'createAdmissionSchedules']);
    Route::get('/get-admission-schedules', [AdmissionSchedulesController::class, 'getschedules']);
    Route::get('/get-admission-schedule/{id}', [AdmissionSchedulesController::class, 'getSchedulesById']);
    Route::post('/update-admission-schedule', [AdmissionSchedulesController::class, 'updateschedules']);
    Route::delete('/delete-admission-schedule/{id}', [AdmissionSchedulesController::class, 'deleteschedules']);

    // Routes for fees_types
    Route::post('/create-fees-type', [FeesTypeController::class, 'store']);
    Route::get('/get-fees-types', [FeesTypeController::class, 'getAll']);
    Route::get('/get-fees-type/{id}', [FeesTypeController::class, 'show']);
    Route::post('/update-fees-type/{id}', [FeesTypeController::class, 'update']);
    Route::delete('/delete-fees-type/{id}', [FeesTypeController::class, 'destroy']);

    // Routes for fees_timeline
    Route::post('/create-fees-timeline', [FeesTimelineController::class, 'store']);
    Route::get('/get-fees-timelines', [FeesTimelineController::class, 'getAll']);
    Route::get('/get-fees-timeline/{id}', [FeesTimelineController::class, 'show']);
    Route::post('/update-fees-timeline/{id}', [FeesTimelineController::class, 'update']);
    Route::delete('/delete-fees-timeline/{id}', [FeesTimelineController::class, 'destroy']);

    //FeesPayType
    Route::post('/create-fees-pay-types', [FeesPayTypeController::class, 'store']);
    Route::get('/get-fees-pay-types', [FeesPayTypeController::class, 'getAll']);
    Route::get('/get-fees-pay-types/{id}', [FeesPayTypeController::class,'show']);
    Route::post('/update-fees-pay-types/{id}', [FeesPayTypeController::class,'update']);
    Route::delete('/destroy-fees-pay-types/{id}', [FeesPayTypeController::class,'destroy']);

    //FeesDiscountType
    Route::post('/create-fees-discount-type', [FeesDiscountTypeController::class, 'store']);
    Route::get('/get-fees-discount-types', [FeesDiscountTypeController::class, 'getAll']);
    Route::get('/get-fees-discount-type/{id}', [FeesDiscountTypeController::class, 'show']);
    Route::post('/update-fees-discount-type/{id}', [FeesDiscountTypeController::class, 'update']);
    Route::delete('/destroy-fees-discount-type/{id}', [FeesDiscountTypeController::class, 'destroy']);

    //Fees academics
    Route::post('/create-fees-academics', [FeesAcademicController::class, 'store']);
    Route::get('/get-fees-academics/{id}', [FeesAcademicController::class, 'show']);
    Route::get('/get-fees-academics', [FeesAcademicController::class, 'getAll']);
    Route::post('/update-fees-academics/{id}', [FeesAcademicController::class, 'update']);
    Route::delete('/destroy-fees-academics/{id}', [FeesAcademicController::class, 'destroy']);

    // Certificates Types
    Route::get('/get-admissionforms', [AdmissionFormsController::class, 'getAadmissionforms']);
    Route::get('/get-admissionform/{id}', [AdmissionFormsController::class, 'getAadmissionformbyId']);
    Route::post('/update-admissionform/{id}', [AdmissionFormsController::class, 'updateAadmissionformbyId']);
    Route::post('/create-admissionforms', [AdmissionFormsController::class, 'createAadmissionforms']);

    // Certificates
    Route::post('/create-certificate', [CertificateController::class, 'storeCertificateType']);
    Route::get('/get-certificate/{id}', [CertificateController::class, 'getCertificateType']);
    Route::get('/get-all-certificates', [CertificateController::class, 'getAllCertificateTypes']);
    Route::post('/update-certificate/{id}', [CertificateController::class, 'updateCertificateType']);
    Route::delete('/delete-certificate/{id}', [CertificateController::class, 'deleteCertificateType']);

    // CertificateTypeFields
    Route::post('/create-certificate-fields', [CertificateController::class, 'createCertificateField']);
    Route::get('/get-certificate-fields/{id}', [CertificateController::class, 'getCertificateField']);
    Route::get('/get-all-certificate-fields', [CertificateController::class, 'getAllCertificateFields']);
    Route::post('/update-certificate-fields/{id}', [CertificateController::class, 'updateFieldsByCertificateId']);
    Route::delete('/delete-certificate-fields/{id}', [CertificateController::class, 'deleteCertificateTypeField']);
    Route::get('/get-certificate-fieldbyid/{id}', [CertificateController::class, 'getCertificateFieldById']);

    // Certificatedata Controller
    Route::get('/get-certificate-fields', [CertificateDataController::class, 'getCertificateFields']);
    Route::post('/generate-certificate', [CertificateDataController::class, 'generateCertificate']);
    Route::get('/get-certificate-list', [CertificateDataController::class, 'generateCertificateList']);
    Route::get('generate-certificate-pdf', [InfoCertificateController::class, 'generateCertificatePdf']);
    Route::get('/delete-certificate-pdf/{id}', [CertificateDataController::class, 'deleteCertificatePdf']);

    //Fees Academic Setup
    Route::post('/create-academic-setup', [FeesAcademicSetupController::class, 'createAcademicSetup']);
    Route::get('/get-academic-setups', [FeesAcademicSetupController::class, 'getAcademicSetup']);
    Route::get('/get-academic-setup/{id}', [FeesAcademicSetupController::class, 'getAcademicSetupById']);
    Route::post('/update-academic-setup/{id}', [FeesAcademicSetupController::class, 'updateAcademicSetup']);
    Route::get('/get-student-academic-fees', [FeesAcademicSetupController::class, 'getStudentAcademicFees']);

    //Leave
    Route::post('/create-leave', [LeaveController::class, 'CreateLeave']);
    Route::get('/get-leave-bystudent', [LeaveController::class, 'getLeaveByStudentId']);
    Route::get('/get-leaves', [LeaveController::class, 'getLeaves']);
    Route::get('/get-leave/{id}', [LeaveController::class, 'getLeave']);
    Route::post('/update-leave/{id}', [LeaveController::class, 'UpdateLeave']);
    Route::delete('/delete-leave/{id}', [LeaveController::class, 'SoftDeleteLeave']);

    //Fees payment
    Route::post('/create-academic-payments',[FeesAcademicPaymentsController ::class, 'createAcademicPayments']);
    Route::get('/get-academic-payments',[FeesAcademicPaymentsController ::class, 'getAcademicPayments']);
    Route::get('/get-academic-payment-method',[FeesAcademicPaymentsController ::class, 'getAcademicPaymentsMethod']);

   //Report Remarks
    Route::post('/create-report-remarks',[RemarksController ::class, 'CreateRemarks']);
    Route::get('/get-remarks', [RemarksController::class, 'GetAllRemarks']);
    Route::get('/get-remarks/{id}', [RemarksController::class, 'GetRemarkById']);
    Route::post('/update-remarks/{id}', [RemarksController::class, 'UpdateRemarks']);
    Route::delete('/delete-remarks/{id}', [RemarksController::class, 'SoftDeleteRemarks']);

    //Holidays
    Route::post('/create-holidays',[HolidaysController ::class, 'CreateHolidays']);
    Route::get('/get-holidays', [HolidaysController::class, 'GetAllHolidays']);
    Route::get('/get-holidays/{id}', [HolidaysController::class, 'GetHolidaysById']);
    Route::post('/update-holidays/{id}', [HolidaysController::class, 'UpdateHolidays']);
    Route::delete('/delete-holidays/{id}', [HolidaysController::class, 'softDeleteHolidays']);

    //Exam Config
    Route::post('/create-exam-config',[ExamConfigController ::class, 'createExamConfig']);
    Route::get('/get-exam-configs', [ExamConfigController::class, 'getAllExamConfig']);
    Route::get('/get-exam-config/{id}', [ExamConfigController::class, 'getExamConnfigById']);
    Route::post('/update-exam-config/{id}', [ExamConfigController::class, 'updateExamConfig']);
    Route::delete('/delete-exam-config/{id}', [ExamConfigController::class, 'softDeleteExamConfig']);

    //Exam
    Route::post('/create-exam',[ExamController ::class, 'Createexam']);
    Route::get('/get-exams', [ExamController::class, 'getAllExams']);
    Route::get('/get-exam/{id}', [ExamController::class, 'getExamById']);
    Route::post('/update-exam/{id}', [ExamController::class, 'updateExam']);
    Route::delete('/delete-exam/{id}', [ExamController::class, 'softDeleteExam']);

    //ExamGrade
    Route::post('/create-exam-grade',[ExamGradeController ::class, 'createExamGrade']);
    Route::get('/get-exam-grades', [ExamGradeController::class, 'getAllExamGrade']);
    Route::get('/get-exam-grade/{id}', [ExamGradeController::class, 'getExamGradeById']);
    Route::post('/update-exam-grade/{id}', [ExamGradeController::class, 'updateExamGrade']);
    Route::delete('/delete-exam-grade/{id}', [ExamGradeController::class, 'deleteExamGrade']);

    //ExamReportLock
    Route::post('/create-exam-report-lock',[ExamReportLockController ::class, 'CreateExamReportLock']);
    Route::get('/get-all-exam-report-lock', [ExamReportLockController::class, 'GetExamReportLock']);
    Route::get('/get-exam-report-lock/{id}', [ExamReportLockController::class, 'GetExamReportLockById']);
    Route::post('/update-exam-report-lock/{id}', [ExamReportLockController::class, 'UpdateExamReportLock']);
    Route::delete('/delete-exam-report-lock/{id}', [ExamReportLockController::class, 'SoftDeleteExamReportLock']);

    //Exam Marks Entrues
    Route::post('/create-exam-marks', [ExamMarksEntryController::class, 'createExamMarks']);
    Route::get('/get-exam-marks', [ExamMarksEntryController::class, 'getExamMarks']);
    Route::get('/get-exam-mark/{id}', [ExamMarksEntryController::class, 'getExamMarkById']);
    Route::post('/update-exam-mark/{id}', [ExamMarksEntryController::class, 'updateExamMarkById']);
    Route::get('/get-student-exam-marks', [ExamMarksEntryController::class, 'getStudentExamMarks']);
    Route::get('/export-exam-marks', [ExamMarksEntryController::class, 'downlaodExamMarksExcel']);
    Route::get('/exam-marks-download-pdf', [ExamMarksEntryController::class, 'downloadExamMarksPDF']);


    //StudentParentDetails
    Route::get('/get-student-parent-details', [StudentParentDetailsController::class, 'getStudentParentDetails']);

    //IdCard
    Route::post('/create-id-card',[IdController ::class, 'CreateId']);
    Route::get('/get-all-id/{id_type}', [IdController::class, 'GetAllId']);
    Route::get('/get-all-id-card/{id}', [IdController::class, 'getIdCardById']);
    Route::post('/update-id-card/{id}', [IdController::class, 'updateIdCard']);
    Route::delete('/delete-id-Card/{id}', [IdController::class, 'softDeleteIdCard']);

    //PDF
    Route::get('/generate-pdf', [PDFController::class, 'generatePDF']);
    Route::get('/generate-recipet-pdf', [PDFController::class, 'generateRecipetPDF']);

    //Fees Dashboard
    Route::get('/get-fees-dashboard', [FeesAcademicSetupController::class, 'getFeesDashboard']);

    //personality Traits
    Route::post('/create-personality',[PersonalityController ::class, 'CreatePersonality']);
    Route::get('/get-all-personality-traits', [PersonalityController::class, 'GetAllPersonality']);
    Route::get('/get-personality-traits/{id}', [PersonalityController::class, 'GetPersonalityById']);
    Route::post('/update-personality-traits/{id}', [PersonalityController::class, 'UpdatePersonality']);
    Route::delete('/delete-personality_Traits/{id}', [PersonalityController::class, 'DeletePersonalityTraits']);

    //Transport Routes
    Route::get('/get-transport-routes', [TransportRouteController::class, 'GetAllTransportRoutes']);
    Route::post('/create-transport-route', [TransportRouteController::class, 'CreateTransportRoute']);
    Route::get('/get-transport-route/{id}', [TransportRouteController::class, 'GetTransportRouteById']);
    Route::post('/update-transport-route/{id}', [TransportRouteController::class, 'UpdateTransportRoute']);
    Route::delete('/delete-transport-route/{id}', [TransportRouteController::class, 'DeleteTransportRoute']);
    Route::get('/get-routes', [TransportRouteController::class, 'GetRoutes']);

    //Transport Routes Stops
    Route::get('/get-transport-route-stops', [TransportRouteStopController::class, 'getAllStopsByRouteId']);
    Route::post('/create-transport-route-stop', [TransportRouteStopController::class, 'createStop']);
    Route::get('/get-transport-route-stop/{id}', [TransportRouteStopController::class, 'getStopById']);
    Route::post('/update-transport-route-stop/{id}', [TransportRouteStopController::class, 'updateStop']);
    Route::delete('/delete-transport-route-stop/{id}', [TransportRouteStopController::class, 'deleteStop']);
    //Transport Vehicle
    Route::post('/create-transport-vehicles',[TransportVehicleController ::class, 'createTransportVehicle']);
    Route::get('/get-all-transport-vehicles', [TransportVehicleController::class, 'getAllTransportVehicle']);
    Route::get('/get-transport-vehicles/{id}', [TransportVehicleController::class, 'GetTransportVehicle']);
    Route::post('/update-transport-vehicles/{id}', [TransportVehicleController::class, 'updateTransportVehicle']);
    Route::delete('/delete-transport-vehicles/{id}', [TransportVehicleController::class, 'DeleteTransportVehicle']);
    //pilot
    Route::post('/create-pilot',[PilotController ::class, 'createpilot']);
    Route::get('/get-all-Pilot', [PilotController::class, 'getAllPilot']);
    Route::get('/get-pilot/{id}', [PilotController::class, 'getAllPilotbyid']);
    Route::post('/update-pilot/{id}', [PilotController::class, 'updatePilot']);
    Route::delete('/delete-pilot/{id}', [PilotController::class, 'deletepilot']);
    //Transport Details
    Route::post('/create-transport-details',[TransportController ::class, 'CreateTransport']);
    Route::get('/get-transport-details', [TransportController::class, 'GetAllTransportDetails']);
    Route::get('/get-transport-details/{id}', [TransportController::class, 'GetTransportDetailsById']);
    Route::post('/update-transport-details/{id}', [TransportController::class, 'UpdateTransportDetails']);
    Route::delete('/delete-transport-details/{id}', [TransportController::class, 'DeleteTransportDetails']);
    Route::get('/get-student-transport-details', [TransportController::class, 'getTransportDetailsByStudentId']);

    //Lib Book Issue
    Route::post('/create-library-book-issue',[LibIsssueController ::class, 'CreateLibIssue']);
    Route::get('/get-all-library-book-issue', [LibIsssueController::class, 'GetAllLibIssue']);
    Route::get('/get-library-book-issue/{id}', [LibIsssueController::class, 'GetAllLibIssueId']);
    Route::delete('/delete-library-book-issue/{id}', [LibIsssueController::class, 'DeleteLibissue']);
    Route::post('/library-return/{id}', [LibIsssueController::class, 'returnBook']);
    Route::get('/get-all-library-book-issue-except-issued', [LibIsssueController::class, 'GetAllLibBooksNotIssued']);

    // Library Management
    Route::post('/create-library-book',[LibBookController ::class, 'storeLibBook']);
    Route::get('/getall-lib-books', [LibBookController::class, 'GetAll']);
    Route::get('/get-lib-book/{id}', [LibBookController::class, 'showBook']);
    Route::post('/update-lib-book/{id}', [LibBookController::class, 'updateBook']);
    Route::delete('/delete-lib-book/{id}', [LibBookController::class, 'deleteBook']);
    // LibraryReviewManagement
    Route::post('/create-lib-book-review',[LibBookReviewController ::class, 'storeLibBookReview']);
    Route::get('/getall-lib-book-review',[LibBookReviewController ::class, 'GetAllBookReviews']);
    Route::get('/get-lib-book-review/{id}',[LibBookReviewController ::class, 'showBookReview']);
    Route::post('/update-lib-book-review/{id}',[LibBookReviewController ::class, 'updateBookReview']);
    Route::delete('/delete-lib-book-review/{id}',[LibBookReviewController ::class, 'deleteLibBookReview']);

});
Route::post('checktoken', [AuthController::class, 'checkToken']);














