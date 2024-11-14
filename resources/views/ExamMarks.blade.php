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
        .sub-header { text-align: center; font-size: 14px; margin-bottom: 20px; }
        .section { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">Exam Marks Report</div>
    <div class="sub-header">Branch: {{ $data[0]['branch_name'] ?? 'N/A' }}</div>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Section</th>
                <th>Exam</th>
                <th>Total Marks</th>
                <th>Percentage</th>
                <th>Result</th>
                <th>Section Rank</th>
                <th>Class Rank</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $entry)
                <tr>
                    <td>{{ $entry['student_name'] }}</td>
                    <td>{{ $entry['admission_no'] }}</td>
                    <td>{{ $entry['marks_data']['class_info']['class_name'] }}</td>
                    <td>{{ $entry['marks_data']['class_info']['section_name'] }}</td>
                    <td>{{ $entry['marks_data']['class_info']['exam_id'] }}</td>
                    <td>{{ array_sum(array_column($entry['marks_data']['marks'], 'internal')) + array_sum(array_column($entry['marks_data']['marks'], 'external')) }}</td>
                    <td>{{ $entry['marks_data']['percentage'] }}%</td>
                    <td>{{ $entry['marks_data']['result'] }}</td>
                    <td>{{ $entry['marks_data']['section_rank'] }}</td>
                    <td>{{ $entry['marks_data']['class_rank'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section">
        <h4>Student Marks Details:</h4>
        @foreach ($data as $entry)
            <h5>{{ $entry['student_name'] }} (Admission No: {{ $entry['admission_no'] }})</h5>
            <table>
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Internal Marks</th>
                        <th>External Marks</th>
                        <th>Is Absent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entry['marks_data']['marks'] as $mark)
                        <tr>
                            <td>{{ $mark['subject_name'] }}</td>
                            <td>{{ $mark['internal'] }}</td>
                            <td>{{ $mark['external'] }}</td>
                            <td>{{ $mark['isabsent'] ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</body>
</html>
