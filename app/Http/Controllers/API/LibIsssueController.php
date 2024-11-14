<?php

namespace App\Http\Controllers\API;

use App\Models\Staff;
use App\Models\LibBook;
use App\Models\Student;
use App\Models\LibBookIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Interfaces\LibIssueInterface;
use App\Http\Requests\BookReturnRequest;
use App\Repositories\LibIssueRepository;
use App\Exceptions\DataNotFoundException;
use App\Http\Requests\LibBookIssueRequest;
use App\Http\Requests\UpdateBookIssueRequest;

class LibIsssueController extends Controller
{
    protected $libissueinterface;

    public function __construct(LibIssueInterface $libissueinterface)
    {
        $this->libissueinterface = $libissueinterface;
    }

    public function createLibIssue(LibBookIssueRequest $request)
    {
        $validated = $request->validated();
        $books = $validated['book_id'];
        $issueDate = $validated['issue_date'];
        $studentId = $validated['student_id'];
        $result = $this->libissueinterface->issueBooks($books,$studentId,$issueDate);
        if ($result['status']) {
            return response()->json(['message' => $result['message']], 201);
        }
        return response()->json(['error' => $result['message']], 400);
    }

    public function returnBook(BookReturnRequest $request,$id)
    {
        $validated = $request->validated();
        $returnStatus = $validated['return_status'];
        $returnDate = $validated['return_date'];
        $comment = $validated['return_comments'];
        $result = $this->libissueinterface->returnBook($id,$returnStatus,$returnDate,$comment);
        if ($result['status']) {
            return response()->json(['message' => $result['message']], 200);
        }
        return response()->json(['error' => $result['message']], 400);
    }

    public function GetAllLibIssue(Request $request)
    {
        $libissue = $this->libissueinterface->GetLibIssued($request);
        if ($libissue instanceof \Illuminate\Http\JsonResponse) {
            $libissue = $libissue->getData(true);
        }
        if (empty($libissue['data']) || empty($libissue['total'])) {
            throw new DataNotFoundException('Data not found.');
        }
        return response()->json([
            'data'  => $libissue['data'],
            'total' => $libissue['total']
        ], 200);
    }

    public function GetAllLibIssueId($id)
    {
        $libissue = $this->libissueinterface->GetAllLibIssueId($id);

        if (!$libissue['status']) {
            throw new DataNotFoundException($libissue['message'] ?? 'Data not found.');
        }

        if (isset($libissue['data']['return_status_name']) && $libissue['data']['return_status_name'] === 'Returned') {
            return response()->json(['message' => 'This book has already been returned.'], 400);
        }

        return response()->json(['data' => $libissue['data']], 200);
    }

    public function DeleteLibissue($id)
    {
        $Deleteissue = $this->libissueinterface->DeleteLibissue($id);
        if ($Deleteissue === false) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        if ($Deleteissue === null) {
            return response()->json(['message' => 'Already in inactive state'], 404);
        }
        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    public function GetAllLibBooksNotIssued(Request $request)
    {
        $libissue = $this->libissueinterface->GetAllLibBooksNotIssued($request);
        if ($libissue instanceof \Illuminate\Http\JsonResponse) {
            $libissue = $libissue->getData(true);
        }
        if (empty($libissue['data']) || empty($libissue['total'])) {
            throw new DataNotFoundException('Data not found.');
        }
        return response()->json([
            'data'  => $libissue['data'],
            'total' => $libissue['total']
        ], 200);
    }
}
