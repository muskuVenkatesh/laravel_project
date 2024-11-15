<!DOCTYPE html>
<html>
<head>
    <title>Exam Marks Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; font-size: 18px; font-weight: bold; }
        .sub-header { text-align: center; font-size: 14px; margin-bottom: 20px; font-weight: bold;  }
        .details { margin-bottom: 20px; font-size: 14px; }
        .inline-details { display: inline-block; margin-right: 30px; }
    </style>
</head>
<body>
    <div class="header">Exam Marks Report</div>
    <div class="sub-header">{{ $data[0]['branch_name'] ?? 'N/A' }}</div>

    <!-- Exam Details Above the Table -->
    <div class="details">
        <span class="inline-details"><strong>Class:</strong> {{ $data[0]['marks_data']['class_info']['class_name'] ?? 'N/A' }}</span>
        <span class="inline-details"><strong>Section:</strong> {{ $data[0]['marks_data']['class_info']['section_name'] ?? 'N/A' }}</span>
        <span class="inline-details"><strong>Exam:</strong> {{ $data[0]['marks_data']['class_info']['exam_name'] ?? 'N/A' }}</span>
        <span class="inline-details"><strong>Academic Year:</strong> {{ $data[0]['marks_data']['academic_year'] ?? 'N/A' }}</span>
        <span><strong>Total Marks:</strong>
            {{ array_sum(array_column($data[0]['marks_data']['marks'], 'internal')) + array_sum(array_column($data[0]['marks_data']['marks'], 'external')) }}
        </span>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2">Student Name</th>
                <th rowspan="2">Admission No</th>
                <th rowspan="2">Class</th>
                <th rowspan="2">Section</th>
                <th rowspan="2">Exam</th>
                <th rowspan="2">Total Marks</th>
                <th rowspan="2">Percentage</th>
                <th rowspan="2">Result</th>
                <th rowspan="2">Section Rank</th>
                <th rowspan="2">Class Rank</th>

                <!-- Dynamic Subject Headers -->
                @foreach ($data[0]['marks_data']['marks'] as $mark)
                    <th colspan="3">{{ $mark['subject_name'] }}</th>
                @endforeach
            </tr>
            <tr>
                <!-- Column for Internal Marks, External Marks, and Absent Status -->
                @foreach ($data[0]['marks_data']['marks'] as $mark)
                    <th>Internal</th>
                    <th>External</th>
                    <th>Absent</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $entry)
                <tr>
                    <td>{{ $entry['student_name'] }}</td>
                    <td>{{ $entry['admission_no'] }}</td>
                    <td>{{ $entry['marks_data']['class_info']['class_name'] }}</td>
                    <td>{{ $entry['marks_data']['class_info']['section_name'] }}</td>
                    <td>{{ $entry['marks_data']['class_info']['exam_name'] }}</td>
                    <td>{{ array_sum(array_column($entry['marks_data']['marks'], 'internal')) + array_sum(array_column($entry['marks_data']['marks'], 'external')) }}</td>
                    <td>{{ $entry['marks_data']['percentage'] }}%</td>
                    <td>{{ $entry['marks_data']['result'] }}</td>
                    <td>{{ $entry['marks_data']['section_rank'] }}</td>
                    <td>{{ $entry['marks_data']['class_rank'] }}</td>

                    <!-- Dynamic Subject Marks Data -->
                    @foreach ($data[0]['marks_data']['marks'] as $mark)
                        @php
                            $subjectMark = collect($entry['marks_data']['marks'])->firstWhere('subject_name', $mark['subject_name']);
                        @endphp
                        <td>{{ $subjectMark ? $subjectMark['internal'] : 'null' }}</td>
                        <td>{{ $subjectMark ? $subjectMark['external'] : 'null' }}</td>
                        <td>{{ isset($subjectMark) && $subjectMark['isabsent'] ? 'Yes' : 'No' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
