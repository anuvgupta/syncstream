<html>

<head>
    <title>
        SyncStream Manager
    </title>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="SyncStream" />
    <link rel="icon" type="image/png" href="./img/warp2.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="./img/applogoF.png" />
    <link rel="apple-touch-startup-image" href="./img/MangoBW.png" />
    <script src="./js/jquery-2.1.4.min.js"></script>
    <script src="./js/id3-minimized.js"></script>
    <script src="./js/background-blur.min.js"></script>
    <script src="./js/functions.js"></script>
    <style>
        body {
            background-color: black;
            color: white;
            text-decoration: none;
            text-align: center;
            font-family: Verdana;
            font-size: 1.2em;
            text-shadow: 0px 0px 30px #000000;
            overflow-y: auto;
            overflow-x: hidden;
        }

        div {
            text-shadow: 0px 0px 30px #000000;
        }

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

        #imgWrapper {
            width: 65px;
            height: 65px;
            background-color: black;
        }

        #art {
            -webkit-transition: opacity 0.3s ease-in-out;
            -moz-transition: opacity 0.3s ease-in-out;
            -o-transition: opacity 0.3s ease-in-out;
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            height: 75;
            z-index: 5;
        }

        #bodyBG {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 100%;
            background: #000000 url('./img/blackbox.jpeg') no-repeat center center fixed;
            background-size: 100% 100%;
            z-index: -1;
        }

        #dock {
            position: fixed;
            width: 100%;
            height: 10%;
            padding: 0px;
            bottom: 0px;
            text-align: center;
            z-index: 3;
            background: rgba(0, 0, 0, 0.1);
        }

        #dockBG {
            position: fixed;
            width: 100%;
            height: 10%;
            padding: 0px;
            bottom: 0px;
            text-align: center;
            z-index: -1;
        }

        #titleTain {
            width: 35%;
            position: absolute;
            left: 0;
            z-index: -1;
        }

        #artistTain {
            width: 35%;
            position: absolute;
            right: 0;
            z-index: -1;
        }

        #menuTain {
            width: 100%;
            position: absolute;
            z-index: 4;
        }

        #title {
            text-align: right;
            z-index: -1;
        }

        #artist {
            text-align: left;
            z-index: -1;
        }

        #time {
            z-index: -1;
            -webkit-transition: opacity 0.3s ease-in-out;
            -moz-transition: opacity 0.3s ease-in-out;
            -o-transition: opacity 0.3s ease-in-out;
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .link {
            height: 60px;
            width: 60px;
            background-color: rgba(0, 0, 0, 0.33);
            color: white;
            text-decoration: none;
            display: table;
            -moz-transition: width 0.5s, height 0.5s;
            -o-transition: width 0.5s, height 0.5s;
            -webkit-transition: width 0.5s, height 0.5s;
            transition: width 0.5s, height 0.5s;
            font-size: 0em;
            margin: auto;
            text-align: center;
            opacity: 0.9;
            z-index: 5;
        }

        .link:hover {
            color: white;
            height: 80px;
            width: 80px;
        }

        i {
            display: table-cell;
            vertical-align: middle;
            text-shadow: 0px 0px 0px #000000;
        }

        td {
            width: 60px;
            height: 60px;
            border: 0.3em solid transparent;
            text-align: center;
            font-family: Verdana;
            margin: auto;
        }

        .linkImg {
            height: 45px;
            width: 45px;
            -moz-transition: width 0.26s, height 0.26s;
            -o-transition: width 0.26s, height 0.26s;
            -webkit-transition: width 0.26s, height 0.26s;
            transition: width 0.26s, height 0.26s;
        }
    </style>
    <script>
        var cURI = './img/blackbox.jpeg';
        var audioRoot = "./audio/";
        var randTime = false;
        var sync = true;
        var retTime = 2000;
        var synjQ = $("#linkImg2");
        var timerjQ = $("#linkImg3");
        var play = true;
        var playing = true;
        var enable = true;

        function initBlur() {
            //body
            $("#bodyBG").remove();
            var iDiv = document.createElement('div');
            iDiv.id = 'bodyBG';
            body.appendChild(iDiv);
            $('#bodyBG').backgroundBlur({
                imageURL: cURI,
                blurAmount: 50,
                imageClass: 'bg-blur',
                duration: 1010,
                endOpacity: 0.7
            });
            //dock
            $("#dockBG").remove();
            var iDiv = document.createElement('div');
            iDiv.id = 'dockBG';
            body.appendChild(iDiv);
            $('#dockBG').backgroundBlur({
                imageURL: cURI,
                blurAmount: 30,
                imageClass: 'bg-blur',
                duration: 1010,
                endOpacity: 1
            });
        }

        function loadBlur(imgPath) {
            $('#bodyBG').backgroundBlur(imgPath);
            $('#dockBG').backgroundBlur(imgPath);
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
            if (nTT[2] < 10) extra = "0";
            _$("time").innerHTML = nTT[1] + ":" + extra + nTT[2];
        }

        function playSong() {
            _$("ausrc").load();
            _$("ausrc").play();
        }

        function syncNow() {
            rotateElement(360, 1200, syncjQ);
            ret(true);
        }

        function retrieve() {
            if (sync) ret(false);
            setTimeout(retrieve, retTime);
            if (randTime) retTime = Math.floor(Math.random() * 2000) + 1000;
        }

        function ret(exact) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    sub: "load",
                    command: "pull",
                    mode: playing.toString()
                },
                dataType: 'text',
                success: function(data) {
                    console.log(data);
                    if (data.charAt(data.length - 1) == "s") {
                        console.log("pause");
                        playing = false;
                        _$("ausrc").pause();
                    } else {
                        if (!playing) {
                            playing = true;
                            _$("ausrc").play();
                        }
                        var nPath = audioRoot + data.substring(0, data.indexOf("^"));
                        var nTimeStr = data.substring(data.indexOf("*") + 1, data.length);
                        var nTime = parseFloat(nTimeStr);

                        console.log(_$("ausrc").currentTime);
                        console.log(nTime);

                        if ((exact) || ((!exact) && (Math.floor(nTime) != Math.floor(_$("ausrc").currentTime))))
                            _$("ausrc").currentTime = nTime;

                        if (qualifyURL(nPath) != _$("ausrc").src) {
                            _$("ausrc").src = nPath;
                            playSong();
                            _$("art").style.opacity = "0";
                            //_$("load").style.opacity = "1";
                            setSongInfo(nPath);
                        }
                    }
                }
            });
        }

        function changeMode(m) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {
                    sub: "load",
                    command: "change",
                    mode: m
                },
                dataType: 'text',
                success: function(data) {
                    console.log("MD DATA: " + data);
                }
            });
        }

        function setSongInfo(path) {
            console.log(path);
            //_$("ausrc").src = path;
            ID3.loadTags(path, function() {
                var tags = ID3.getAllTags(path);
                _$("artist").innerHTML = tags.artist || "Artist Unknown";
                _$("title").innerHTML = "<b>" + tags.title + "</b>" || "<b>Title Unknown</b>";
                //_$("album").innerHTML = "<b>" + tags.album + "</b>" || "<b>Album Unknown</b>";
                if ("picture" in tags) {
                    var image = tags.picture;
                    var base64String = "";
                    for (var i = 0; i < image.data.length; i++) {
                        base64String += String.fromCharCode(image.data[i]);
                    }
                    var dataURI = "data:" + image.format + ";base64," + window.btoa(base64String);
                    //_$("load").style.opacity = "0";
                    _$("art").src = dataURI;
                    _$("art").style.opacity = "1"
                    loadBlur(dataURI);
                } else {
                    //_$("load").style.opacity = "0";
                    _$("art").src = "./img/MangoBW.png";
                    _$("art").style.opacity = "1";
                }
            }, {
                tags: ["artist", "title", "album", "picture"]
            });
        }

        function toggleVol() {
            if (_$("ausrc").muted) {
                _$("ausrc").muted = false;
                _$("linkImg1").src = "./img/volOn.png";
            } else {
                _$("ausrc").muted = true;
                _$("linkImg1").src = "./img/volOff.png";
            }
        }

        function toggleLink() {
            sync = !sync;
            if (sync) _$("linkImg4").src = "./img/link.png";
            else _$("linkImg4").src = "./img/unlink.png";
        }

        function togglePlay() {
            if (enable) {
                play = !play;
                if (play) {

                    var z = _$("link3").zIndex;
                    _$("link3").style.opacity = 0.5;
                    _$("link3").style.zIndex = -100;
                    enable = false;

                    setTimeout(function() {
                        _$("link3").style.opacity = 1;
                        _$("link3").style.zIndex = z;
                        enable = true;
                    }, 3500);

                    changeMode("p");
                    _$("linkImg3").src = "./img/pause.png";

                } else {

                    var z = _$("link3").zIndex;
                    _$("link3").style.opacity = 0.5;
                    _$("link3").style.zIndex = -100;
                    enable = false;

                    setTimeout(function() {
                        _$("link3").style.opacity = 1;
                        _$("link3").style.zIndex = z;
                        enable = true;
                    }, 3500);

                    changeMode("s");
                    _$("linkImg3").src = "./img/play.png";
                }
            }
        }

        function setEvents() {
            $("#art").hover(function() {
                _$("art").style.opacity = 0.1;
                _$("time").style.opacity = 1;
            }, function() {
                _$("art").style.opacity = 1;
                _$("time").style.opacity = 0.1;
            });

            $("#link1").hover(function() {
                _$("linkImg1").style.width = 60;
                _$("linkImg1").style.height = 60;
            }, function() {
                _$("linkImg1").style.width = 45;
                _$("linkImg1").style.height = 45;
            });

            $("#link2").hover(function() {
                _$("linkImg2").style.width = 60;
                _$("linkImg2").style.height = 60;
            }, function() {
                _$("linkImg2").style.width = 45;
                _$("linkImg2").style.height = 45;
            });

            $("#link3").hover(function() {
                _$("linkImg3").style.width = 60;
                _$("linkImg3").style.height = 60;
            }, function() {
                _$("linkImg3").style.width = 45;
                _$("linkImg3").style.height = 45;
            });

            $("#link4").hover(function() {
                _$("linkImg4").style.width = 60;
                _$("linkImg4").style.height = 60;
            }, function() {
                _$("linkImg4").style.width = 45;
                _$("linkImg4").style.height = 45;
            });
        }

        function initLoad() {
            setEvents();
            syncjQ = $("#linkImg2");
            timerjQ = $("#linkImg3");
            _$("art").style.opacity = "0";
            audioRoot = "./audio/";
            setTimeout(syncNow, 30);
            setTimeout(initBlur, 50);
            setTimeout(retrieve, 100);
            setInterval(setTimeDisp, 500);
        }

        $(window).on("orientationchange", initBlur);
        $(window).resize(initBlur);
        $(document).ready(initLoad);
    </script>
</head>

<body id="body">
    <audio src="" id="ausrc" type="audio/mpeg">
        Your browser does not support the audio element. Get <a href="https://www.google.com/chrome/">Chrome</a>.
    </audio>
    <div id="bodyBG"></div>
    <div id="dockBG"></div>
    <div id="dock">
        <div id="menuTain" class="container">
            <div id="menuTent" class="content">
                <center>
                    <table>
                        <center>
                            <tr>
                                <td><a href="#">
                                        <div onclick="toggleVol()" class="link" id="link1"><i><img height="45" width="45" src="./img/volOn.png" class="linkImg" id="linkImg1" /></i></div>
                                    </a></td>
                                <td><a href="#">
                                        <div onclick="togglePlay()" class="link" id="link3"><i><img height="45" width="45" src="./img/pause.png" class="linkImg" id="linkImg3" /></i></div>
                                    </a></td>
                                <td><img onclick="test1()" src="./img/coldplay5.jpg" id="art" /></a>
                                <td><a href="#">
                                        <div onclick="syncNow()" class="link" id="link2"><i><img height="45" width="45" src="./img/sync2.png" class="linkImg" id="linkImg2" /></i></div>
                                    </a></td>
                                <td><a href="#">
                                        <div onclick="toggleLink()" class="link" id="link4"><i><img height="45" width="45" src="./img/link.png" class="linkImg" id="linkImg4" /></i>
                                        </div>
                                    </a></td>
                            </tr>
                        </center>
                    </table>
                </center>
            </div>
        </div>
        <div class="container" id="titleTain">
            <div class="content" id="title">
                <b>Name Unknown</b>
            </div>
        </div>
        <div class="container">
            <div class="content" id="time">
                0:00
            </div>
        </div>
        <div class="container" id="artistTain">
            <div class="content">
                <div class="content" id="artist">
                    <b>Artist Unknown</b>
                </div>
            </div>
        </div>
    </div>
</body>

</html>