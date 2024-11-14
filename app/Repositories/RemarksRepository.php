<?php

namespace App\Repositories;

use App\Models\ReportRemarks; 
use Illuminate\Http\Request;
use App\Interfaces\RemarksInterface;

class RemarksRepository implements RemarksInterface
{
    protected $reportRemarks;
    
    public function __construct(ReportRemarks $reportRemarks)
    {
        $this->reportRemarks = $reportRemarks; 
    }

    public function CreateRemarks($data)
    {
        return $this->reportRemarks->create($data);
    }

    public function GetAllRemarks(Request $request)
    {
        $limit = $request->input('_limit');
        $allremarks = ReportRemarks::where('status', 1);
        $total = $allremarks->count();

        if ($request->has('q')) {
            $search = $request->input('q');
            $allremarks->where('name', 'like', "%{$search}%");
        }
        if ($request->has('_sort') && $request->has('_order')) {
            $sortBy = $request->input('_sort');
            $sortOrder = $request->input('_order');
            $allremarks->orderBy($sortBy, $sortOrder);
        } else {
            $allremarks->orderBy('created_at', 'asc');
        }
        if ($limit <= 0) {
            $allremarksData = $allremarks->get();
        } else {
            $allremarksData = $allremarks->paginate($limit);
            $allremarksData = $allremarksData->items();
        }
        return ['data'=>$allremarksData,'total'=>$total];
    }
    
    public function GetRemarkById($id)
    {
        return $this->reportRemarks->find($id); 
    }

    public function UpdateRemarks($id, $data)
    {
        $remark = $this->reportRemarks->find($id);
        if ($remark) {
            $remark->update($data);
            return "Updated Successfully."; 
        }
    }
    
    public function SoftDeleteRemarks($id)
    {
        $remark = $this->reportRemarks->find($id);    
        if ($remark) {
            $remark->status = 0; 
            $remark->save();
            return true;
        }
        return false;
    }
}