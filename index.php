<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIB Paguokon Rebana Dance Library</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, Helvetica, sans-serif;
        }

        body{
            height:100vh;

            background:
                linear-gradient(rgba(255,240,248,0.82), rgba(243,232,255,0.82)),
                url("images/login-card-background.png");

            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;

            display:flex;
            justify-content:center;
            align-items:center;
        }

        .container{
            max-width:750px;
            text-align:center;
            padding:50px;
            background:rgba(255,255,255,0.45);
            backdrop-filter:blur(10px);
            border-radius:25px;
            box-shadow:0 12px 35px rgba(170,130,255,.25);
        }

        h1{
            font-size:42px;
            color: #F48FB1;
            margin-bottom:20px;
        }

        p{
            font-size:18px;
            color:#555;
            line-height:1.8;
            margin-bottom:40px;
        }

        .btn{
            display:inline-block;
            text-decoration:none;
            color:white;
            padding:13px 32px;
            margin:8px;
            border-radius:30px;
            font-weight:bold;
            background:linear-gradient(135deg,#F9A8D4,#C084FC);
            box-shadow:0 8px 20px rgba(192,132,252,.35);
            transition:.3s ease;
        }

        .btn:hover{
            transform:translateY(-3px);
            background:linear-gradient(135deg,#EC4899,#9333EA);
            box-shadow:0 12px 25px rgba(147,51,234,.4);
        }

        @media(max-width:768px){

            .container{
                width:90%;
                padding:35px;
            }

            h1{
                font-size:32px;
            }

            p{
                font-size:16px;
            }

            .btn{
                display:block;
                width:220px;
                margin:12px auto;
            }

        }

    </style>
</head>

<body>

<div class="container">

    <h1>SIB Paguokon Rebana Dance Library System</h1>

    <p>
        Welcome to the official library system for the SIB Paguokon Rebana Dance Library.
        This platform provides easy access to dance learning materials, instructional videos,
        music, documents, and other resources while helping administrators manage the digital
        library efficiently.
    </p>

    <a class="btn" href="Login.php">Login</a>
    <a class="btn" href="Signup.php">Create Account</a>
    <a class="btn" href="About.php">About</a>

</div>

</body>
</html>