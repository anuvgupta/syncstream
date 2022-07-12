<?php
session_start();
if (@$_POST["pass"] == "password") {
    $_SESSION["potatoes"] = true;
    header("Location: ./manager.php");
} else {
    $_SESSION["potatoes"] = false;
?>
<html>

<head>
    <title>
        The Archive
    </title>
    <style>
    form {
        display: table-cell;
        vertical-align: middle;
        text-align: center;
    }

    div {
        width: 100%;
        height: 100%;
        position: absolute;
        display: table;
    }
    </style>
</head>

<body>
    <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <br />
            <input type="image" src="./yeah.png">
            <br />
            <input type="password" name="pass">
        </form>
    </div>
</body>

</html>
<?php
}
?>