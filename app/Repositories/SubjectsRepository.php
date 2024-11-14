<?php

namespace App\Repositories;

use App\Interfaces\SubjectsInterface;
use App\Models\Subjects;
use App\Models\User;
use App\Models\SubjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\DB;

class SubjectsRepository implements SubjectsInterface
{
    protected $subject;

    public function __construct(Subjects $subject)
    {
        $this->subject = $subject;
    }

    public function deleteSubject($id)
    {
        $subject = $this->subject->find($id);

        if ($subject) {
            $subject->delete();
            return $subject;
        }

        return null;
    }


    public function updateSubject($data, $id)
    {
        try {
            $subject = $this->subject->find($id);
            if (!$subject) {
                return [
                    'status' => 'error',
                    'message' => 'Subject not found.',
                    'code' => 404,
                ];
            }
            $SubjectName = strtolower(trim($data['name']));
            $SubjectName = preg_replace('/[ _]/', '', $SubjectName);
            $SubjectName = preg_replace('/[^a-zA-Z0-9]/', '', $SubjectName);
            $currentSubjectName = strtolower(trim($subject->name));
            $currentSubjectName = preg_replace('/[ _]/', '', $currentSubjectName);
            $currentSubjectName = preg_replace('/[^a-zA-Z0-9]/', '', $currentSubjectName);
            if ($SubjectName !== $currentSubjectName) {
                $existingSubject = $this->subject
                    ->whereRaw('LOWER(REPLACE(REPLACE(name, \' \', \'\'), \'_\', \'\')) = ?', [$SubjectName])
                    ->where('id', '!=', $id)
                    ->first();
                if ($existingSubject) {
                    return [
                        'status' => 'error',
                        'message' => 'Subject with this name already exists.',
                        'code' => 409,
                    ];
                }
            }

            $subject->update([
                'name' => $data['name'],
            ]);

            return [
                'status' => 'success',
                'data' => $subject,
                'message' => 'Subject updated successfully.',
                'code' => 200,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'An error occurred during the update.',
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }
    }

    public function createSubject($data)
    {
        $SubjectName = strtolower(trim($data['name']));
        $SubjectName = preg_replace('/[ _]/', '', $SubjectName);
        $SubjectName = preg_replace('/[^a-zA-Z0-9]/', '', $SubjectName);
        $existingSubject = $this->subject->whereRaw('LOWER(REPLACE(REPLACE(name, \' \', \'\'), \'_\', \'\')) = ?', [$SubjectName])->first();

        if ($existingSubject) {
            return [
                'status' => 'error',
                'message' => 'Subject already exists.',
                'code' => 409,
            ];
        }
        $subject = $this->subject->create([
            'name' => $data['name'],
        ]);

        $user = Auth::user();

        if ($user->roleid != 1) {
            $content = "Dear SuperAdmin,\n\nUser " . $user->name . " has created a new subject with the name " . $subject->name;

            $email = User::where('roleid', 1)->value('email');
            SendEmailJob::dispatch($email, 'Subject Creation', $content);
        }
        return [
            'status' => 'success',
            'data' => $subject,
            'code' => 201,
        ];
    }


    public function getSubject(Request $request, $limit)
    {
        $query = Subjects::where('subjects.status', 1)
            ->whereNull('subjects.deleted_at')
            ->select('subjects.id', 'subjects.name','subjects.created_at as subject_created_at');

        if ($request->has('q')) {
            $search = $request->input('q');
            $query->where('subjects.name', 'like', "%{$search}%");
        }

        if ($request->has('_sort') && $request->has('_order')) {
            $query->orderBy($request->input('_sort'), $request->input('_order'));
        } else {
            $query->orderBy('subjects.created_at', 'asc');
        }

        $total = $query->count();

        if ($limit <= 0) {
            $subjects = $query->get();
        } else {
            $subjects = $query->paginate($limit)->items();
        }

        return ['data' => $subjects, 'total' => $total];
    }

    public function getSubjectTypes()
    {
        $subject = SubjectType::where('status',1)->get();
        return $subject;
    }

    public function getSubjectById($id)
    {
        return SubjectType::join('subject_types', 'subjects.type', '=', 'subject_types.id')
            ->where('subjects.id', $id)
            ->select('subjects.id', 'subjects.name', 'subject_types.name as subject_type')
            ->first();
    }
}
