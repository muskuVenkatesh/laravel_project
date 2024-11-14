<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Absentee Notification</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .header-banner {
            text-align: center;
            padding: 20px;
            border: 2px solid #0a19a0;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .title {
            color: #0609bd;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #0a19a0;
            padding-bottom: 10px;
        }

        .notification-text {
            font-size: 16px;
            color: #333;
            text-align: left;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 30px;
            padding: 20px;
            background-color: #010924;
            border-top: 1px solid #02011a;
            text-align: center;
        }

        .logo-text h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2a09be;
            margin: 0;
        }

        .logo-text h2 {
            font-size: 18px;
            font-weight: bold;
            color: #a92e3c;
            margin: 5px 0;
        }

        .logo-text p {
            font-size: 14px;
            color: #333;
            margin: 0;
        }
  </style>
</head>

<body>
  <div class="container">
    <header class="header-banner">
      <div class="row align-items-center">
        <div class="col-md-9">
          <div class="logo-text">
            <h1>{{ $branchName }}</h1>
            <h2>INNOVATE | EDUCATE | ELEVATE</h2>
          </div>
        </div>
      </div>
    </header>

    <p class="title">Leaves Notification</p>

    <p class="notification-text">
      Dear Parent,<br /><br />
      We hope this message finds you well. We would like to bring to your attention that your child, <strong>{{ $student_name }}</strong>, Leaves From, <strong>{{ $leave_date }}</strong>.
    </p>
  </div>
</body>

</html>
