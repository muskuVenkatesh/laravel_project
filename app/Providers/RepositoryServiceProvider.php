<?php
namespace App\Providers;

use App\Interfaces\IdInterface;
use App\Interfaces\PDFInterface;
use App\Interfaces\ExamInterface;
use App\Interfaces\GroupInterface;
use App\Interfaces\LeaveInterface;
use App\Interfaces\MarksInterface;
use App\Interfaces\PilotInterface;
use App\Interfaces\StaffInterface;
use App\Repositories\IdRepository;
use App\Interfaces\BranchInterface;
use App\Interfaces\MediumInterface;
use App\Interfaces\ParentInterface;
use App\Interfaces\SchoolInterface;
use App\Repositories\PDFRepository;
use App\Interfaces\ClassesInterface;
use App\Interfaces\RemarksInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\StudentInterface;
use App\Repositories\DistRepository;
use App\Repositories\ExamRepository;
use App\Interfaces\FeesTypeInterface;
use App\Interfaces\HolidaysInterface;
use App\Interfaces\HomeworkInterface;
use App\Interfaces\LanguageInterface;
use App\Interfaces\SubjectsInterface;
use App\Repositories\GroupRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\MarksRepository;
use App\Repositories\PilotRepository;
use App\Repositories\StaffRepository;
use App\Repositories\StateRepository;
use App\Interfaces\DashboardInterface;
use App\Interfaces\ExamGradeInterface;
use App\Interfaces\TransportInterface;
use App\Repositories\BranchRepository;
use App\Repositories\MediumRepository;
use App\Repositories\ParentRepository;
use App\Repositories\SchoolRepository;
use App\Interfaces\AttendanceInterface;
use App\Interfaces\DepartmentInterface;
use App\Interfaces\ExamConfigInterface;
use App\Interfaces\OccupationInterface;
use App\Repositories\ClassesRepository;
use App\Repositories\LibBookRepository;
use App\Repositories\RemarksRepository;
use App\Repositories\SectionRepository;
use App\Repositories\StudentRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\FeesPayTypeInterface;
use App\Interfaces\PersonalityInterface;
use App\Interfaces\SmsTemplateInterface;
use App\Repositories\FeesTypeRepository;
use App\Repositories\HolidaysRepository;
use App\Repositories\HomeworkRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\SubjectsRepository;
use App\Interfaces\FeesAcademicInterface;
use App\Interfaces\FeesTimelineInterface;
use App\Interfaces\NotificationInterface;
use App\Repositories\DashboardRepository;
use App\Repositories\ExamGradeRepository;
use App\Repositories\TransportRepository;
use App\Interfaces\BranchSubjectInterface;
use App\Interfaces\LibBookReviewInterface;
use App\Interfaces\QualificationInterface;
use App\Repositories\AttendanceRepository;
use App\Repositories\DepartmentRepository;
use App\Repositories\ExamConfigRepository;
use App\Repositories\OccupationRepository;
use App\Interfaces\AdmissionFormsInterface;
use App\Interfaces\DistRepositoryInterface;
use App\Interfaces\ExamMarksEntryInterface;
use App\Interfaces\ExamReportLockInterface;
use App\Interfaces\TransportRouteInterface;
use App\Repositories\FeesPayTypeRepository;
use App\Repositories\PersonalityRepository;
use App\Repositories\SmsTemplateRepository;
use App\Interfaces\AcademicDetailsInterface;
use App\Interfaces\CertificateDataInterface;
use App\Interfaces\CertificateTypeInterface;
use App\Interfaces\InfoCertificateInterface;
use App\Interfaces\StateRepositoryInterface;
use App\Repositories\FeesAcademicRepository;
use App\Repositories\FeesTimelineRepository;
use App\Repositories\NotificationReposatory;
use App\Interfaces\AdmissionEnquiryInterface;
use App\Interfaces\FeesDiscountTypeInterface;
use App\Interfaces\homeWorkCircularInterface;
use App\Interfaces\TransportVehicleInterface;
use App\Repositories\BranchSubjectRepository;
use App\Repositories\LibBookReviewRepository;
use App\Repositories\QualificationRepository;
use App\Repositories\SchoolSettingRepository;
use App\Interfaces\FeesAcademicSetupInterface;
use App\Interfaces\LibBookRepositoryInterface;
use App\Repositories\AdmissionFormsRepository;
use App\Repositories\ExamMarksEntryRepository;
use App\Repositories\ExamReportLockRepository;
use App\Repositories\TransportRouteRepository;
use App\Interfaces\AdmissionSchedulesInterface;
use App\Interfaces\TransportRouteStopInterface;
use App\Repositories\AcademicDetailsRepository;
use App\Repositories\CertificateDataRepository;
use App\Repositories\CertificateTypeRepository;
use App\Repositories\InfoCertificateRepository;
use App\Repositories\AdmissionEnquiryRepository;
use App\Repositories\FeesDiscountTypeRepository;
use App\Repositories\HomeWorkCircularRepository;
use App\Repositories\TransportVehicleRepository;
use App\Interfaces\AdmissionAnouncementInterface;
use App\Interfaces\CertificateTypeFieldInterface;
use App\Interfaces\FeesAcademicPaymentsInterface;
use App\Interfaces\StudentParentDetailsInterface;
use App\Repositories\FeesAcademicSetupRepository;
use App\Repositories\StaffNotificationRepository;
use App\Repositories\AdmissionSchedulesRepositoty;
use App\Repositories\TransportRouteStopRepository;
use App\Interfaces\SchoolSettingRepositoryInterface;
use App\Repositories\CertificateTypeFieldRepository;
use App\Repositories\FeesAcademicPaymentsRepository;
use App\Repositories\StudentParentDetailsRepository;
use App\Interfaces\LibIssueInterface;
use App\Repositories\LibIssueRepository;
use App\Repositories\AdmissionAnnouncementRepositoty;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(SchoolInterface::class, SchoolRepository::class);
        $this->app->bind(SchoolSettingRepositoryInterface::class, SchoolSettingRepository::class);
        $this->app->bind(BranchInterface::class, BranchRepository::class);
        $this->app->bind(DepartmentInterface::class, DepartmentRepository::class);
        $this->app->bind(ClassesInterface::class, ClassesRepository::class);
        $this->app->bind(StudentInterface::class, StudentRepository::class);
        $this->app->bind(SectionInterface::class, SectionRepository::class);
        $this->app->bind(AcademicDetailsInterface::class, AcademicDetailsRepository::class);
        $this->app->bind(SubjectsInterface::class, SubjectsRepository::class);
        $this->app->bind(MediumInterface::class, MediumRepository::class);
        $this->app->bind(ParentInterface::class, ParentRepository::class);
        $this->app->bind(StaffInterface::class, StaffRepository::class);
        $this->app->bind(BranchSubjectInterface::class, BranchSubjectRepository::class);
        $this->app->bind(QualificationInterface::class, QualificationRepository::class);
        $this->app->bind(GroupInterface::class, GroupRepository::class);
        $this->app->bind(LanguageInterface::class, LanguageRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(DistRepositoryInterface::class, DistRepository::class);
        $this->app->bind(AttendanceInterface::class, AttendanceRepository::class);
        $this->app->bind(HomeworkInterface::class, HomeworkRepository::class);
        $this->app->bind(SmsTemplateInterface::class, SmsTemplateRepository::class);
        $this->app->bind(OccupationInterface::class, OccupationRepository::class);
        $this->app->bind(NotificationInterface::class, NotificationReposatory::class);
        $this->app->bind(homeWorkCircularInterface::class, HomeWorkCircularRepository::class);
        $this->app->bind(AdmissionAnouncementInterface::class, AdmissionAnnouncementRepositoty::class);
        $this->app->bind(AdmissionEnquiryInterface::class, AdmissionEnquiryRepository::class);
        $this->app->bind(DashboardInterface::class, DashboardRepository::class);
        $this->app->bind(AdmissionFormsInterface::class, AdmissionFormsRepository::class);
        $this->app->bind(StaffNotificationInterface::class, StaffNotificationRepository::class);
        $this->app->bind(AdmissionSchedulesInterface::class, AdmissionSchedulesRepositoty::class);
        $this->app->bind(FeesAcademicSetupInterface::class, FeesAcademicSetupRepository::class);
        $this->app->bind(FeesTypeInterface::class, FeesTypeRepository::class);
        $this->app->bind(FeesTimelineInterface::class, FeesTimelineRepository::class);
        $this->app->bind(FeesPayTypeInterface::class, FeesPayTypeRepository::class);
        $this->app->bind(FeesDiscountTypeInterface::class, FeesDiscountTypeRepository::class);
        $this->app->bind(FeesAcademicInterface::class, FeesAcademicRepository::class);
        $this->app->bind(CertificateTypeInterface::class, CertificateTypeRepository::class);
        $this->app->bind(CertificateTypeFieldInterface::class, CertificateTypeFieldRepository::class);
        $this->app->bind(CertificateDataInterface::class, CertificateDataRepository::class);
        $this->app->bind(LeaveInterface::class, LeaveRepository::class);
        $this->app->bind(FeesAcademicPaymentsInterface::class, FeesAcademicPaymentsRepository::class);
        $this->app->bind(RemarksInterface::class, RemarksRepository::class);
        $this->app->bind(HolidaysInterface::class, HolidaysRepository::class);
        $this->app->bind(ExamInterface::class, ExamRepository::class);
        $this->app->bind(ExamConfigInterface::class, ExamConfigRepository::class);
        $this->app->bind(ExamGradeInterface::class, ExamGradeRepository::class);
        $this->app->bind(ExamReportLockInterface::class, ExamReportLockRepository::class);
        $this->app->bind(StudentParentDetailsInterface::class, StudentParentDetailsRepository::class);
        $this->app->bind(IdInterface::class, IdRepository::class);
        $this->app->bind(ExamMarksEntryInterface::class, ExamMarksEntryRepository::class);
        $this->app->bind(PDFInterface::class, PDFRepository::class);
        $this->app->bind(MarksInterface::class, MarksRepository::class);
        $this->app->bind(PersonalityInterface::class, PersonalityRepository::class);
        $this->app->bind(TransportRouteInterface::class, TransportRouteRepository::class);
        $this->app->bind(TransportRouteStopInterface::class, TransportRouteStopRepository::class);
        $this->app->bind(TransportVehicleInterface::class, TransportVehicleRepository::class);
        $this->app->bind(PilotInterface::class, PilotRepository::class);
        $this->app->bind(TransportInterface::class, TransportRepository::class);
        $this->app->bind(InfoCertificateInterface::class, InfoCertificateRepository::class);
        $this->app->bind(LibIssueInterface::class, LibIssueRepository::class);
        $this->app->bind(LibBookRepositoryInterface::class, LibBookRepository::class);
        $this->app->bind(LibBookReviewInterface::class, LibBookReviewRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
