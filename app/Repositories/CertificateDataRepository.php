<?php
namespace App\Repositories;
use App\Models\Classes;
use App\Models\Schools;
use App\Models\Section;
use App\Models\Student;
use App\Models\Branches;
use Illuminate\Http\Request;
use App\Models\CertificateData;
use App\Models\CertificateTypesField;
use App\Interfaces\CertificateDataInterface;

class CertificateDataRepository implements CertificateDataInterface
{
    public function __construct(CertificateTypesField $certificatetypefield,CertificateData $CertificateData)
    {
        $this->certificatetypefield = $certificatetypefield;
        $this->CertificateData = $CertificateData;
    }

    public function getCertificateFields($data)
    {
        $certificateTypeId = $data['certificate_type_id'];
        $classId = $data['class_id'];
        $sectionId = $data['section_id'];
        $studentId = $data['student_id'];
        $fields = CertificateTypesField::where('certificate_type_id', $certificateTypeId)->where('status',1)->get();
        $classData = [];
        $sectionData = [];
        $studentData = [];
        $branchData = [];
        $schoolData = [];
        if ($classId) {
            $classData = Classes::find($classId);
            if ($classData) {
                $branchData = Branches::find($classData->branch_id);
            }
        }
        if ($sectionId) {
            $sectionData = Section::find($sectionId);
        }

        if ($studentId) {
            $studentData = Student::find($studentId);
            if ($studentData) {
                if ($studentData->class_id) {
                    $classData = Classes::find($studentData->class_id);
                    if ($classData) {
                        $branchData = Branches::find($classData->branch_id);
                    }
                }
                if ($branchData) {
                    $schoolData = Schools::find($branchData->school_id);
                }
            }
        }
        $mappedData = [];
        foreach ($fields as $field) {
            $mappedData[$field->field_name] = $this->mapFieldData($field, $classData, $sectionData, $studentData, $branchData, $schoolData);
        }
        return [
            'mappedData' => $mappedData,
        ];
    }

    /**
     * Map field data based on the provided field and data.
     *
     * @param CertificateTypesField $field
     * @param Classes $classData
     * @param Section $sectionData
     * @param Student $studentData
     * @param Branches $branchData
     * @param Schools $schoolData
     * @return mixed
     */
    private function mapFieldData($field, $classData, $sectionData, $studentData, $branchData, $schoolData)
    {
        switch ($field->field_name) {
            case 'class_name':
                return $classData ? $classData->name : null;
            case 'section_name':
                return $sectionData ? $sectionData->name : null;
            case 'student_name':
                return $studentData ? "{$studentData->first_name} {$studentData->last_name}" : null;
            case 'branch_name':
                return $branchData ? $branchData->name : null;
            case 'school_name':
                return $schoolData ? $schoolData->name : null;
            default:
                return null;
        }
    }

    public function generateCertificate(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'certificate_type' => 'required|integer',
            'school_id' => 'required|integer',
            'branch_id' => 'required|integer',
            'student_id' => 'required|integer',
            'certificate_data' => 'required|array'
        ]);
        $certificates = $this->CertificateData->create([
            'certificate_type' => $data['certificate_type'],
            'school_id' => $data['school_id'],
            'branch_id' => $data['branch_id'],
            'student_id' => $data['student_id'],
            'cert_data' => $data['certificate_data'],
        ]);
        return $certificates;
    }

    public function generateCertificateList(Request $request)
    {
        $branch_id = $request->input('branch_id');
        $class_id = $request->input('class_id');
        $section_id = $request->input('section_id');

        $CertificateData = $this->CertificateData->where('certificate_data.branch_id', $branch_id)
        ->join('students', 'students.id', '=', 'certificate_data.student_id')
        ->where('students.class_id', $class_id)
        ->where('students.section_id', $section_id)
        ->where('certificate_data.status', 1)
        ->join('certificate_types', 'certificate_types.id', '=' ,'certificate_data.certificate_type')
        ->select('certificate_data.id','students.id as student_id','students.first_name', 'students.last_name', 'certificate_types.certificate_type', 'certificate_data.certificate_type as certificate_type_id')
        ->get();

        return $CertificateData;
    }

    public function deleteCertificatePdf($id)
    {
       $Certificatedata = $this->CertificateData->find($id);
       $Certificatedata->status = 0;
       $Certificatedata->save();
       return $Certificatedata;
    }
}
