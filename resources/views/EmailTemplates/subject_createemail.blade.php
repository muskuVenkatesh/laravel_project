<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #e6ebf0 50%, #ffffff 50%);
        }
        .header {
            background-color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            background-color: #fbeeee;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content h2 {
            color: #333333;
        }
        .otp-box {
            display: inline-block;
            padding: 15px;
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            background-color: #007bff;
            border-radius: 4px;
            margin: 20px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table th,
        .details-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: center;
        }
        .details-table th {
            background-color: #f2f2f2;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR8gl1JV0KOFtrnmrPMiqAU1Hju9hjxviWfuA&s" alt="SR Group Edu Logo">
        </div>

        <!-- Content -->
        <div class="content">
            <h1>Branch Name: {{ $branchName }}</h1>
            <h2>OTP Verification</h2>
            <p>Dear {{ $userName }},</p><br>
            <p>Please use the following OTP to complete your verification:</p>
            <div class="otp-box">
                {{ $otp }}
            </div>
            <p>This OTP is valid for the next 10 minutes.</p>
             <!-- Updated Details Table -->
             <table class="details-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Roll No</th>
                        <th>Class</th>
                        <th>Section</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $student['student_name'] }}</td>
                            <td>{{ $student['roll_no'] }}</td>
                            <td>{{ $student['class_name'] }}</td>
                            <td>{{ $student['section_name'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 SR Group Edu Company. All rights reserved.</p>
            <p><a href="#">Unsubscribe</a> | <a href="#">Contact Support</a></p>
        </div>
    </div>
</body>
</html>
