<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\BranchSeeder;
use Database\Seeders\MediumSeeder;
use Database\Seeders\ParentSeeder;
use Database\Seeders\SchoolSeeder;
use Database\Seeders\ClassesSeeder;
use Database\Seeders\SectionSeeder;
use Database\Seeders\StudentSeeder;
use Database\Seeders\SubjectTypesSeeder;
use Database\Seeders\SubjectSeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\AttendanceSeeder;
use Database\Seeders\OccupationSeeder;
use Database\Seeders\AdmissionFormSeeder;
use Database\Seeders\BranchSubjectSeeder;
use Database\Seeders\QualificationSeeder;
use Database\Seeders\SchoolSettingSeeder;
use Database\Seeders\AcademicDetailSeeder;
use Database\Seeders\CertificateTypeSeeder;
use Database\Seeders\NotificationTypeSeeder;
use Database\Seeders\AdmissionEnquiriesSeeder;
use Database\Seeders\AdmissionSchedulesSeeder;
use Database\Seeders\AdmissionFormsDetailsSeeder;
use Database\Seeders\AdmissionAnnouncementsSeeder;
use Database\Seeders\CertificateTypesFieldsSeeder;
use Database\Seeders\FeeDiscountSeeder;
use Database\Seeders\FeePayTypeSeeder;
use Database\Seeders\FeeTimelineSeeder;
use Database\Seeders\FeeTypesSeeder;
use Database\Seeders\ExamReportLockSeeder;
use Database\Seeders\ExamGradesSeeder;
use Database\Seeders\TransportVehicleDetailsSeeder;
use Database\Seeders\TransportRouteSeeder;
use Database\Seeders\ReportCardSeeder;
use Database\Seeders\ReciptSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            BranchSeeder::class,
            SchoolSettingSeeder::class,
            AcademicDetailSeeder::class,
            QualificationSeeder::class,
            OccupationSeeder::class,
            ParentSeeder::class,
            UserSeeder::class,
            StaffSeeder::class,
            ClassesSeeder::class,
            SectionSeeder::class,
            MediumSeeder::class,
            GroupSeeder::class,
            NotificationTypeSeeder::class,
            SubjectTypesSeeder::class,
            SubjectSeeder::class,
            BranchSubjectSeeder::class,
            StateSeeder::class,
            LanguageSeeder::class,
            CertificateTypeSeeder::class,
            CertificateTypesFieldsSeeder::class,
            AdmissionAnnouncementsSeeder::class,
            AdmissionEnquiriesSeeder::class,
            AdmissionFormsDetailsSeeder::class,
            AdmissionFormSeeder::class,
            AdmissionSchedulesSeeder::class,
            AttendanceSeeder::class,
            FeeDiscountSeeder::class,
            FeePayTypeSeeder::class,
            FeeTimelineSeeder::class,
            FeeTypesSeeder::class,
            ExamReportLockSeeder::class,
            ExamGradesSeeder::class,
            TransportRouteSeeder::class,
            TransportRouteStopSeeder::class,
            TransportVehicleDetailsSeeder::class,
            ReportCardSeeder::class,
            ReciptSeeder::class
        ]);
    }
}
