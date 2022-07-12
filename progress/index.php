<?php

session_start();

if (@$_POST["sub"] == "load") {

    $currTS = microtime(true);

    $fData = file("data.txt");
    $lastTS = floatval($fData[0]);
    $path = $fData[1];
    $offset = floatval($fData[2]);

    $offset = $offset + ($currTS - $lastTS);

    $duration = floatval(substr($path, 1 + strpos($path, "^")));

    if ($offset > $duration) {
        $qFData = file("queue.txt");
        $nextPath = $qFData[0];

        $file2 = fopen("queue.txt", "w");
        for ($i = 1; $i < count($qFData); $i++) {
            fwrite($file2, ($qFData[$i]));
        }
        fclose($file2);

        $path = $nextPath;
        $offset = 0;
    }

    $file = fopen("data.txt", "w");
    fwrite($file, ($currTS . "\n" . $path . $offset));
    fclose($file);

    print($path . "*" . $offset);
    exit();
}

?>
<html>

<head>
    <title>
        SyncStream
    </title>
    <style>
        .container {
            display: table;
            height: 100%;
            width: 100%;
            text-align: center;
            position: absolute;
        }

        .content {
            display: table-cell;
            vertical-align: middle;
            height: 100%;
            width: 100%;
        }

        body {
            background-color: black;
            color: white;
            text-decoration: none;
            text-align: center;
            font-family: Verdana;
            font-size: 1.15em;
            text-shadow: 0px 0px 30px #000000;
        }

        div {
            text-shadow: 0px 0px 30px #000000;
        }

        #bodyDiv {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
            background: #000000 url('./blackbox.jpeg') no-repeat center center fixed;
            background-size: 100% 100%;
            z-index: -10;
        }

        #art {
            -webkit-transition: opacity 1s ease-in-out;
            -moz-transition: opacity 1s ease-in-out;
            -o-transition: opacity 1s ease-in-out;
            transition: opacity 1s ease-in-out;
            opacity: 0;
            height: 300;
            padding: 0vw 0vw 0vw 0vw;
        }

        #load {
            -webkit-transition: opacity 0.4s;
            -moz-transition: opacity 0.4s;
            -o-transition: opacity 0.4s;
            transition: opacity 0.4s;
            opacity: 0;
            height: 60;
            padding: 10vw 10vw 10vw 10vw;
        }

        #lc {
            top: 0;
            left: 0;
        }
    </style>
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="MangoBW.png" />
    <script src="./js/jquery-2.1.4.min.js"></script>
    <script src="./js/id3-minimized.js"></script>
    <script src="./js/background-blur.min.js"></script>
    <script src="./js/jquery.mb.audio.js"></script>
    <script src="./js/functions.js"></script>
    <script>
        function initBlur() {
            $('#bodyDiv').backgroundBlur({
                imageURL: './blackbox.jpeg',
                blurAmount: 25,
                imageClass: 'bg-blur',
                duration: 1010,
                endOpacity: 0.7
            });
        }

        function loadBlur(imgPath) {
            $('#bodyDiv').backgroundBlur(imgPath);
        }

        function playSong() {
            console.log("playSong");
            _$("ausrc").load();
            _$("ausrc").play();
        }

        function setSongInfo(path) {
            console.log("setSongInfo");
            _$("ausrc").src = path;
            ID3.loadTags(path, function() {
                var tags = ID3.getAllTags(path);
                _$("artist").innerHTML = tags.artist || "Artist Unknown";
                _$("title").innerHTML = "<b>" + tags.title + "</b>" || "<b>Title Unknown</b>";
                _$("album").innerHTML = "<b>" + tags.album + "</b>" || "<b>Album Unknown</b>";
                if ("picture" in tags) {
                    var image = tags.picture;
                    var base64String = "";
                    for (var i = 0; i < image.data.length; i++) {
                        base64String += String.fromCharCode(image.data[i]);
                    }

                    var dataURI = "data:" + image.format + ";base64," + window.btoa(base64String);
                    _$("load").style.opacity = "0";
                    _$("art").src = dataURI;
                    _$("art").style.opacity = "1"
                    loadBlur(dataURI);
                } else {
                    _$("load").style.opacity = "0";
                    _$("art").src = "./MangoBW.png";
                    _$("art").style.opacity = "1";
                }
            }, {
                tags: ["artist", "title", "album", "picture"]
            });
        }

        function readTime(cTime) {
            var cMinutes = Math.floor(cTime / 60);
            var cSeconds = Math.floor(cTime - cMinutes * 60);
            var cTT = [cTime, cMinutes, cSeconds];
            return cTT;
        }

        function setTimeDisp() {
            var nTT = readTime(_$("ausrc").currentTime);
            var extra = "";
            if (nTT[2] < 10) {
                extra = "0";
            }
            _$("time").innerHTML = nTT[1] + ":" + extra + nTT[2];
        }

        var co = 0;

        function retrieve() {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    sub: "load"
                },
                dataType: 'text',
                success: function(data) {
                    var nPath = data.substring(0, data.indexOf("^"));
                    var nTimeStr = data.substring(data.indexOf("*") + 1, data.length);
                    var nTime = parseFloat(nTimeStr);

                    co++;
                    console.log(co);
                    console.log(_$("ausrc").currentTime);
                    console.log(nTime);

                    if (Math.floor(nTime) != Math.floor(_$("ausrc").currentTime)) {
                        _$("ausrc").currentTime = nTime;
                    }

                    if (qualifyURL(nPath) != _$("ausrc").src) {
                        _$("art").style.opacity = "0";
                        _$("load").style.opacity = "1";
                        setSongInfo(nPath);
                        playSong();
                    }

                }
            });
        }

        function initLoad() {
            _$("load").style.opacity = "1";
            _$("art").style.opacity = "0";
            setTimeout(initBlur, 50);
            setTimeout(retrieve, 100);
            setInterval(retrieve, Math.floor(Math.random() * 2000) + 1000);
            setInterval(setTimeDisp, 500);
        }

        $(document).ready(function() {
            initLoad();
        });
    </script>
</head>

<body id="body">
    <audio src="" id="ausrc" type="audio/mpeg">
        Your browser does not support the audio element. Get <a href="https://www.google.com/chrome/">Chrome</a>.
    </audio>
    <div id="bodyDiv"></div>
    <div class="container">
        <div class="content">
            <center>
                <div id="title"><b>Name Unknown</b></div>
                <div id="time">0:00</div>
                <br />
                <div class="container" id="lc">
                    <div class="content">
                        <img id="load" src="./loading.gif">
                    </div>
                </div>
                <img id="art" src="./loading.gif">
                <br /><br />
                <div id="artist">Artist Unknown</div>
                <div id="album"><b>Album Unknown</b></div>
                <br /><br />
            </center>
        </div>
    </div>
</body>

</html>