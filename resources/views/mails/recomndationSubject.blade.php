<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder: Final Day for PFE Submission Tomorrow!</title>
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
            <h2>Reminder: Final Day for PFE Submission Tomorrow!</h2>
        </div>
        <div class="content">
            <p>Dear {{$user->lname}} {{$user->fname}},</p>
            <p>I hope this email finds you well. As a friendly reminder, tomorrow marks the final day for PFE (Projet de Fin d'Études) submissions. We understand the importance of this milestone in your academic journey and want to ensure that you have all the necessary support to complete your project successfully.</p>
            <p>As part of our system, we have recommended the following subject for your PFE:</p>
            <blockquote>
                <p>{!! $subjects !!}</p>
            </blockquote>
            <p>Please review this subject and consider it for your PFE submission. If you have any questions or require further assistance, feel free to contact us.</p>
            <p>If, for any reason, you wish to reject the recommended subject or encounter any issues, please do not hesitate to contact us. We are committed to ensuring a smooth and successful PFE submission process for all students.</p>
            <p>Thank you for your attention to2222222222222222222222222222222222 this matter, and best of luck with your PFE submission!</p>
        </div>
    </div>
</body>
</html>
