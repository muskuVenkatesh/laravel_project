<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface LibIssueInterface
{
    public function issueBooks(array $books, int $studentId,string $issueDate);
    public function GetLibIssued(Request $request);
    public function returnBook(int $id,int $returnStatus,string $reutrnDate,string $comment);
    public function GetAllLibBooksNotIssued(Request $request);
    public function GetAllLibIssueId($id);
    public function DeleteLibissue($id);
}
