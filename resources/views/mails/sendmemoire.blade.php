<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <p>Dear Jury {{$jury->lname}}-{{$jury->fname}},</p>
    <p>Please review the memoire of {{$pfe->title}} by clicking the button below:</p>
    <a href="{{$linkToMemoire}}" class="button">View Memoire</a>
</body>
</html>
