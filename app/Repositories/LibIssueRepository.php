<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Staff;
use App\Models\LibBook;
use App\Models\Student;
use App\Models\LibBookIssue;
use Illuminate\Http\Request;
use App\Services\DateService;
use Illuminate\Support\Facades\DB;
use App\Interfaces\LibIssueInterface;

class LibIssueRepository implements LibIssueInterface
{
    protected $libbookissue;

    public function __construct(LibBookIssue $libbookissue)
    {
        $this->libbookissue = $libbookissue;
    }

    public function issueBooks(array $books,int $studentId,string $issueDate)
    {
        try {
            $dateFormatService = new DateService;
            $formattedIssueDate = $dateFormatService->formatDate($issueDate);
            $student = Student::where('id', $studentId)->first();
            if (!$student || !$student->branch_id) {
                return ['status' => false, 'message' => 'Student does not have a valid branch ID'];
            }
            $studentBranchId = $student->branch_id;
            $issuer = auth()->user();
            $staff = User::where('id', $issuer->id)->first();
            foreach ($books as $bookData) {
                $bookId = is_array($bookData) ? $bookData['book_id'] : $bookData;
                $book = LibBook::where('id', $bookId)
                            ->where('branch_id', $studentBranchId)
                            ->first();

                if (!$book || $book->quantity <= 0) {
                    return ['status' => false, 'message' => 'Book ' . ($book ? $book->name : 'Unknown') . ' not available for issuing'];
                }
                $existingIssue = LibBookIssue::where('student_id', $studentId)
                                            ->where('book_id', $book->id)
                                            ->whereIn('return_status', [1, 2, 3])
                                            ->first();

                if ($existingIssue) {
                    if ($existingIssue->return_status == 1) {
                        return ['status' => false, 'message' => 'Book ' . $book->name . ' is already issued to ' . $student->first_name . ' and has not been returned'];
                    }
                    if ($existingIssue->return_status == 2) {
                        return ['status' => false, 'message' => 'Book ' . $book->name . ' for '. $student->first_name . ' is marked as lost and cannot be issued again until paid'];
                    }
                    if ($existingIssue->return_status == 3) {
                        return ['status' => false, 'message' => 'Book ' . $book->name . ' for '. $student->first_name . ' has not been returned and cannot be issued again'];
                    }
                }
                LibBookIssue::create([
                    'book_id' => $book->id,
                    'student_id' => $studentId,
                    'staff_user_id' => $staff->id,
                    'issue_date' => $formattedIssueDate,
                    'return_status' => 1,
                ]);
                $book->decrement('quantity', 1);
            }
            return ['status' => true, 'message' => 'Books issued successfully'];

        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Failed to issue books: ' . $e->getMessage()];
        }
    }

    public function returnBook(int $id, int $returnStatus, string $returnDate, string $comment)
    {
        try {
            $dateFormatService = new DateService;
            $formattedReturnDate = $dateFormatService->formatDate($returnDate);

            $receiver = auth()->user();
            $staff = User::find($receiver->id);
            $bookIssue = LibBookIssue::find($id);

            if (!$bookIssue) {
                return ['status' => false, 'message' => "Book Issue ID {$libBookIssueId} not found."];
            }
            $bookIssue->update([
                'return_status' => $returnStatus,
                'return_staff_user_id' => $staff->id,
                'return_date' => $formattedReturnDate,
                'return_comments' => $comment
            ]);

            $book = LibBook::find($bookIssue->book_id);
            $message = "";

            if ($returnStatus == 0) {
                if ($book) {
                    $book->increment('quantity', 1);
                }
                $message = "Book {$book->name} returned successfully.";
            } elseif ($returnStatus == 2) {
                $message = "Book {$book->name} marked as lost.";
            } elseif ($returnStatus == 3) {
                $message = "Book {$book->name} marked as damaged.";
            } elseif ($returnStatus == 4) {
                if ($book) {
                    $book->increment('quantity', 1);
                }
                $message = "Book {$book->name} marked as paid.";
            } else {
                $message = "Book {$book->name} return status updated to {$returnStatus}.";
            }

            return ['status' => true, 'message' => $message];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => 'Failed to update return status: ' . $e->getMessage()];
        }
    }

    public function GetLibIssued(Request $request)
    {
        $branchId = $request->input('branch_id');
        $search = $request->input('q', null);
        $sortBy = $request->input('_sort', 'lib_book_issue.created_at');
        $sortOrder = $request->input('_order', 'asc');
        $limit = $request->input('_limit', 15);

        if (!$branchId) {
            return response()->json(['error' => 'branch_id is required'], 400);
        }

        $alllibissue = LibBookIssue::query()
            ->select(
                'lib_book_issue.id as issue_id',
                'lib_book.id as book_id',
                'lib_book.name as book_name',
                'students.id as student_id',
                'users.id as staff_user_id',
                'users.username as staff_user_name',
                'students.first_name',
                'students.last_name',
                'lib_book_issue.return_status',
                'lib_book_issue.issue_date',
                'lib_book_issue.return_date',
                'lib_book_issue.created_at',
                'lib_book_issue.status',
                'lib_book_issue.updated_at'
            )
            ->join('lib_book', 'lib_book.id', '=', 'lib_book_issue.book_id')
            ->join('students', 'students.id', '=', 'lib_book_issue.student_id')
            ->join('users','users.id','=','lib_book_issue.staff_user_id')
            ->where('lib_book.branch_id', $branchId)
            ->where('lib_book_issue.return_status', 1);

        if (!is_null($search)) {
            $alllibissue->where('lib_book_issue.return_status', 'like', "%{$search}%");
        }
        if (strpos($sortBy, '.') === false) {
            $sortBy = "lib_book_issue.$sortBy";
        }
        $alllibissue->orderBy($sortBy, $sortOrder);
        $total = $alllibissue->count();
        $alllibissueData = ($limit <= 0)
            ? $alllibissue->get()
            : $alllibissue->paginate($limit);

        $alllibissueData = collect($alllibissueData->items())->map(function ($item)
        {
            $returnStatusNames = [
                0 => 'Returned',
                1 => 'Issued',
                2 => 'Lost',
                3 => 'Damaged',
                4 => 'Paid',
            ];
            return [
                'id' => $item->issue_id,
                'book_id' => $item->book->id,
                'book_name' => $item->book->name ?? 'Unknown Book',
                'student_id' => $item->student->id,
                'student_name' => $item->student->first_name . ' ' . $item->student->last_name,
                'staff_user_id' => $item->staff->id ?? null,
                'staff_name' => ($item->staff->username ?? ''),
                'issue_date' => $item->issue_date,
                'return_status' => $item->return_status,
                'return_status_name' => $returnStatusNames[$item->return_status] ?? 'Unknown',
                'status' => $item->status,
            ];
        });
        return ['data' => $alllibissueData, 'total' => $total];
    }

    public function GetAllLibIssueId($id)
    {
        $returnStatusNames = [
            0 => 'Returned',
            1 => 'Issued',
            2 => 'Lost',
            3 => 'Damaged',
            4 => 'Paid',
        ];

        $libBookIssue = LibBookIssue::query()
            ->join('lib_book', 'lib_book.id', '=', 'lib_book_issue.book_id')
            ->join('users as staff', 'staff.id', '=', 'lib_book_issue.staff_user_id')
            ->leftJoin('users as return_staff', 'return_staff.id', '=', 'lib_book_issue.return_staff_user_id')
            ->where('lib_book_issue.id', $id)
            ->select(
                'lib_book_issue.*',
                'lib_book.name as book_name',
                'staff.username as staff_name',
                'return_staff.username as return_staff_name'
            )
            ->first();

        if (!$libBookIssue) {
            return ['status' => false, 'message' => 'Book issue not found'];
        }

        $libBookIssue->return_status_name = $returnStatusNames[$libBookIssue->return_status] ?? 'Unknown';
        return [
            'status' => true,
            'data' => [
                'id' => $libBookIssue->id,
                'book_id' => $libBookIssue->book_id,
                'book_name' => $libBookIssue->book_name,
                'student_id' => $libBookIssue->student_id,
                'staff_user_id' => $libBookIssue->staff_user_id,
                'staff_name' => $libBookIssue->staff_name,
                'return-status'=>$libBookIssue->return_status,
                'return_staff_user_id' => $libBookIssue->return_staff_user_id,
                'return_staff_name' => $libBookIssue->return_staff_name,
                'return_status_name' => $libBookIssue->return_status_name,
                'issue_date' => $libBookIssue->issue_date,
                'return_date' => $libBookIssue->return_date,
                'return_comments' => $libBookIssue->return_comments,
                'created_at' => $libBookIssue->created_at,
                'updated_at' => $libBookIssue->updated_at,
                'status' => $libBookIssue->status,
            ]
        ];
    }

    public function DeleteLibissue($id)
    {
        $libissue = LibBookIssue::find($id);
        if (!$libissue) {
            return false;
        }
        if ($libissue->status == 0) {
            return null;
        }
        $libissue->status = 0;
        $libissue->save();
        return $libissue;
    }

    public function GetAllLibBooksNotIssued(Request $request)
    {
        $branchId = $request->input('branch_id');
        $search = $request->input('q', null);
        $sortBy = $request->input('_sort', 'lib_book.created_at');
        $sortOrder = $request->input('_order', 'asc');
        $limit = $request->input('_limit', 15);

        if (!$branchId) {
            return response()->json(['error' => 'branch_id is required'], 400);
        }

        $allLibBooksNotIssued = LibBookIssue::query()
            ->select(
                'lib_book_issue.id as issue_id',
                'lib_book.id as book_id',
                'lib_book.name as book_name',
                'users.id as return_staff_user_id',
                'users.username as return_staff_user_name',
                'students.id as student_id',
                'students.first_name',
                'students.last_name',
                'lib_book_issue.return_status',
                'lib_book_issue.return_comments',
                'lib_book_issue.issue_date',
                'lib_book_issue.return_date',
                'lib_book_issue.status',
                'lib_book_issue.created_at',
                'lib_book_issue.updated_at'
            )
            ->join('lib_book', 'lib_book.id', '=', 'lib_book_issue.book_id')
            ->join('students', 'students.id', '=', 'lib_book_issue.student_id')
            ->join('users','users.id','=','lib_book_issue.staff_user_id')
            ->where('lib_book.branch_id', $branchId)
            ->where('lib_book_issue.return_status', '!=', 1);

        if (!is_null($search)) {
            $allLibBooksNotIssued->where('lib_book_issue.return_status', 'like', "%{$search}%");
        }

        if (strpos($sortBy, '.') === false) {
            $sortBy = "lib_book_issue.$sortBy";
        }
        $allLibBooksNotIssued->orderBy($sortBy, $sortOrder);
        $total = $allLibBooksNotIssued->count();
        $allLibBooksNotIssuedData = ($limit <= 0)
            ? $allLibBooksNotIssued->get()
            : $allLibBooksNotIssued->paginate($limit);
            $allLibBooksNotIssuedData = collect($allLibBooksNotIssuedData->items())->map(function ($item) {
            $returnStatusNames = [
                0 => 'Returned',
                1 => 'Issued',
                2 => 'Lost',
                3 => 'Damaged',
                4 => 'Paid',
            ];

            return [
                'id' => $item->issue_id,
                'book_id' => $item->book->id,
                'book_name' => $item->book->name ?? 'Unknown Book',
                'student_id' => $item->student->id,
                'student_name' => $item->student->first_name . ' ' . $item->student->last_name,
                'return_staff_user_id' => $item->returnStaff->id ?? null,
                'return_staff_user_name' => $item->returnStaff->username ?? null,
                'return_status' => $item->return_status,
                'return_status_name' => $returnStatusNames[$item->return_status] ?? 'Unknown',
                'issue_date' => $item->issue_date,
                'return_date' => $item->return_date,
                'return_comments' => $item->return_comments,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'status' => $item->status,
            ];
        });
        return ['data' => $allLibBooksNotIssuedData, 'total' => $total];
    }

}
