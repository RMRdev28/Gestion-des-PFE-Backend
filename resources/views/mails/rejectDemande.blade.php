<!-- resources/views/emails/rejectedProposalEmail.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande Rejection</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: #007bff;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Demande Rejection</h2>
        </div>
        <div class="content">
            <p>Dear {{ $student->lname }} {{ $student->fname }},</p>
            <p>I hope this email finds you well. I wanted to personally thank you for submitting your proposal regarding {{ $proposition->title }}. We appreciate the time and effort you invested in putting forth your ideas.</p>
            <p>After careful consideration and review, I regret to inform you that your proposal has not been accepted at this time. Please understand that this decision was not made lightly
            <p>While your proposal may not have been selected this time, please don't be discouraged. Your creativity and enthusiasm are commendable, and I encourage you to continue exploring new ideas and initiatives.</p>
            <p>Thank you once again for your submission and your understanding. If you have any questions or would like further feedback, please don't hesitate to reach out to me.</p>
            <p>Wishing you all the best in your future endeavors.</p>
        </div>
        <div class="footer">
            <p>Sincerely,</p>
            <p>{{ $user->lname }} {{ $user->fname }}</p>
        </div>
    </div>
</body>
</html>
