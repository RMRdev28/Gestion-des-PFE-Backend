
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal Acceptance</title>
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
            background: #28a745;
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
            <h2>Proposal Acceptance</h2>
        </div>
        <div class="content">
            <p>Dear {{ $student->lname }} {{ $student->fname }},</p>
            <p>Congratulations! I'm pleased to inform you that your proposal regarding {{ $proposition->title }} has been accepted.</p>
            <p>We were impressed by the quality and creativity of your proposal, and we believe it aligns well with our objectives.</p>
            <p>We look forward to working with you on this project and bringing your ideas to fruition.</p>
            <p>Thank you for your contribution and enthusiasm.</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p>{{ $user->lname }} {{ $user->fname }}</p>
        </div>
    </div>
</body>
</html>
