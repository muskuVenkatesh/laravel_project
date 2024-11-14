<?php
namespace App\Repositories;
use App\Models\CertificateType;
use App\Models\CertificateTypesField;
use App\Interfaces\CertificateTypeFieldInterface;
use Illuminate\Support\Str;

class CertificateTypeFieldRepository Implements CertificateTypeFieldInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(certificateType $certificateType,CertificateTypesField $CertificateTypesField)
    {
        $this->CertificateTypesField = $CertificateTypesField;
        $this->certificateType =$certificateType;
    }

    public function createCertificateField($data)
    {
        $certificateTypeId = $data['certificate_type_id'];
        $fieldLabels = $data['field_label'];
        $fieldNames = $data['field_name'] ?? [];
        $fieldTypes = $data['field_type'];
        $createdFields = [];

        foreach ($fieldLabels as $key => $label) {
            $fieldName = isset($fieldNames[$key]) && !empty($fieldNames[$key])
                ? Str::slug($fieldNames[$key], '_')
                : Str::slug($label, '_');

            $createdFields[] = $this->CertificateTypesField->create([
                'certificate_type_id' => $certificateTypeId,
                'field_label' => $label,
                'field_name' => $fieldName,
                'field_type' => $fieldTypes[$key]
            ]);
        }

        return $createdFields;
    }

    public function getCertificateField($certificateTypeId)
    {
        $certificateType = $this->certificateType->where('id', $certificateTypeId)->where('status', 1)->first();
        $data = $this->CertificateTypesField->where('certificate_type_id', $certificateTypeId)->where('status', 1)->get();
        $data->transform(function ($item) {
            unset($item->id);
            return $item;
        });
        return ['data'=>$data,
        'certificatetype'=>$certificateType
    ];
    }

    public function getCertificateFieldById($id)
    {
        $data = $this->CertificateTypesField->find($id);
        return $data;
    }

    public function getCertificateFields()
    {
        $certificateTypes = CertificateType::with(['fields' => function($query) {
            $query->where('status', 1);
        }])->where('status', 1)->get();
        return $certificateTypes;
    }

    public function updateFieldsByCertificateId($data, $id)
    {
        $fields = $this->CertificateTypesField->find($id);

        $fieldLabel = !empty($data['field_label']) ? $data['field_label'][0] : '';

        $fields->field_label = $fieldLabel;
        $fields->field_name = strtolower(str_replace(' ', '_', $fieldLabel));
        $fields->field_type = !empty($data['field_type']) ? $data['field_type'][0] : '';
        $fields->save();

        return $fields;
    }

    public function deleteCertificateFields($certificate_type_id)
    {
        $fields = $this->CertificateTypesField->where('certificate_type_id', $certificate_type_id)->get();
        if ($fields) {
            foreach ($fields as $field) {
                $field->status = 0;
                $field->save();
            }
        }
    }
}
