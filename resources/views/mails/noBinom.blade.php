<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorry, No Binome Found for PFE Submission!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f7f6;
        }
        .header {
            background-color: #3498db;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Sorry, No Binome Found for PFE Submission!</h2>
        </div>
        <div class="content">
            <p>Dear {{$user->lname}} {{$user->fname}},</p>
            <p>We regret to inform you that our system couldn't find a suitable binome for your PFE (Projet de Fin d'Études) submission. We apologize for any inconvenience this may cause.</p>
            <p>However, you still have options:</p>
            <ul>
                <li>You can try to find a binome on your own.</li>
                <li>You can choose to work on your PFE as a monome.</li>
            </ul>
            <p>If you decide to proceed as a monome or have any questions, please don't hesitate to contact us. We're here to support you in any way we can.</p>
            <p>Thank you for your understanding, and we apologize again for the inconvenience.</p>
        </div>
    </div>
</body>
</html>
