<?php

if (@$_POST["sub"] == "load") {

    if ($_POST["command"] == "pull") {

        $currTS = microtime(true);

        $fData = file("data.txt");
        $lastTS = floatval($fData[0]);
        $path = $fData[1];
        $offset = floatval($fData[2]);
        $mode = $fData[3];

        if ($mode == "p") $offset = $offset + ($currTS - $lastTS);

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
        fwrite($file, ($currTS . "\n" . $path . $offset . "\n" . $mode));
        fclose($file);

        print($path . "*" . $offset . "&" . $mode);
        exit();
    } else if ($_POST["command"] == "change") {

        $currTS = microtime(true);

        $fData = file("data.txt");
        $lastTS = floatval($fData[0]);
        $path = $fData[1];
        $offset = floatval($fData[2]);
        $mode = $_POST["mode"];

        $file = fopen("data.txt", "w");
        fwrite($file, ($currTS . "\n" . $path . $offset . "\n" . $mode));
        fclose($file);
        print($currTS . "\n" . $path . $offset . "\n" . $mode);
        exit();
    }
}

?>
<html>

<head>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="SyncStream" />
    <link rel="icon" type="image/png" href="./img/warp2L.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="./img/applogoF.png" />
    <link rel="apple-touch-startup-image" href="./img/MangoBW.png" />
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
        overflow: hidden;
        -webkit-overflow-scrolling: hidden;
    }

    a {
        text-decoration: none;
    }

    div {
        text-shadow: 0px 0px 30px #000000;
    }

    #bgDiv {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
        background: #000000 url('./img/blackbox.jpeg') no-repeat center center fixed;
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
        z-index: 20;
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

    #menuB {
        opacity: 0.4;
        height: 50px;
        width: 75px;
        z-index: 50;
        -webkit-transition: opacity 0.3s;
        -moz-transition: opacity 0.3s;
        -o-transition: opacity 0.3s;
        transition: opacity 0.3s;
    }

    #menuBC {
        position: absolute;
        height: 50px;
        width: 100%;
        bottom: 15;
        z-index: 40;
    }

    #menuTain {
        opacity: 0;
        -webkit-transition: opacity 0.5s;
        -moz-transition: opacity 0.5s;
        -o-transition: opacity 0.5s;
        transition: opacity 0.5s;
        z-index: -20;
        background-color: rgba(0, 0, 0, 0.4);
        ;
    }

    #main {
        opacity: 1;
        -webkit-transition: opacity 0.5s;
        -moz-transition: opacity 0.5s;
        -o-transition: opacity 0.5s;
        transition: opacity 0.5s;
    }

    #menuTent {
        font-size: 23px;
        font-weight: bold;
    }

    #midBlockb {
        opacity: 1;
        -webkit-transition: opacity 0.1s;
        -moz-transition: opacity 0.1s;
        -o-transition: opacity 0.1s;
        transition: opacity 0.1s;
    }

    .link {
        height: 120px;
        width: 120px;
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
        z-index: 75;
    }

    .link:hover {
        color: white;
        height: 10.5em;
        width: 10.5em;
        font-size: 1.6em;
    }

    b {
        display: table-cell;
        vertical-align: middle;
        text-shadow: 0px 0px 0px #000000;
    }

    td {
        width: 120px;
        height: 120px;
        border: 0.3em solid transparent;
        text-align: center;
        font-family: Verdana;
        margin: auto;
    }

    .linkImg {
        height: 60px;
        width: 60px;
        -moz-transition: width 0.26s, height 0.26s;
        -o-transition: width 0.26s, height 0.26s;
        -webkit-transition: width 0.26s, height 0.26s;
        transition: width 0.26s, height 0.26s;
    }

    #prompt {
        opacity: 0;
        -webkit-transition: opacity 0.25s;
        -moz-transition: opacity 0.25s;
        -o-transition: opacity 0.25s;
        transition: opacity 0.25s;
        z-index: -50;
    }

    #numIn {
        padding: 20px;
        /* border: 5px solid rgba(0, 0, 0, 0.3); */
        border: none;
        background: rgba(0, 0, 0, 0.3);
        z-index: 200;
        color: white;
        font-size: 25px;
    }

    #numIn:focus {
        outline: 0;
    }

    .goImg {
        opacity: 0.55;
        -webkit-transition: opacity 0.05s;
        -moz-transition: opacity 0.05s;
        -o-transition: opacity 0.05s;
        transition: opacity 0.05s;
        height: 40px;
    }

    .goImg:hover {
        opacity: 1;
    }

    ::-webkit-input-placeholder {
        text-align: center;
    }

    :-moz-placeholder {
        text-align: center;
    }

    ::-moz-placeholder {
        text-align: center;
    }

    :-ms-input-placeholder {
        text-align: center;
    }

    input {
        text-align: center;
    }
    </style>
    <script src="./js/jquery-2.1.4.min.js"></script>
    <script src="./js/id3-minimized.js"></script>
    <script src="./js/velocity.min.js"></script>
    <script src="./js/background-blur.min.js"></script>
    <script src="./js/functions.js"></script>
    <script>
    var cURI = "./img/blackbox.jpeg";
    var menuOn = false;
    var retTime = Math.floor(Math.random() * 2000) + 1000;
    var randTime = false;
    var sync = true;
    var synjQ = $("#linkImg2");
    var timerjQ = $("#linkImg3");
    var audioRoot = "./audio/";
    var playing = true;

    function initBlur() {
        $("#bgDiv").remove();
        var iDiv = document.createElement('div');
        iDiv.id = 'bgDiv';
        body.appendChild(iDiv);
        $('#bgDiv').backgroundBlur({
            imageURL: cURI,
            blurAmount: 50,
            imageClass: 'bg-blur',
            duration: 1010,
            endOpacity: 0.9
        });
    }

    function loadBlur(path) {
        $('#bgDiv').backgroundBlur(path);
    }

    function playSong() {
        _$("ausrc").load();
        _$("ausrc").play();
    }

    function setSongInfo(path) {
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
                cURI = "data:" + image.format + ";base64," + window.btoa(base64String);
                _$("load").style.opacity = "0";
                _$("art").src = cURI;
                _$("art").style.opacity = "1"
                loadBlur(cURI);
            } else {
                _$("load").style.opacity = "0";
                _$("art").src = "./img/MangoBW.png";
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
        if (nTT[2] < 10) extra = "0";
        _$("time").innerHTML = nTT[1] + ":" + extra + nTT[2];
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
                    var nTimeStr = data.substring(data.indexOf("*") + 1, data.length - 2);
                    var nTime = parseFloat(nTimeStr);

                    console.log(_$("ausrc").currentTime);
                    console.log(nTime);

                    if ((exact) || ((!exact) && (Math.floor(nTime) != Math.floor(_$("ausrc").currentTime))))
                        _$("ausrc").currentTime = nTime;

                    if (qualifyURL(nPath) != _$("ausrc").src) {
                        _$("ausrc").src = nPath;
                        playSong();
                        _$("art").style.opacity = "0";
                        _$("load").style.opacity = "1";
                        setSongInfo(nPath);
                    }
                }
            }
        });
    }

    function getLength(path) {
        _$("ausrc2").src = path;
        _$("ausrc2").load();
        _$("ausrc2").play();
        setTimeout(function() {
            console.log("DURATION: " + _$("ausrc2").duration);
            _$("ausrc2").pause();
            _$("ausrc2").src = "";
        }, 200);
    }

    function setEvents() {
        $("#menuB").hover(function() {
            _$("menuB").style.opacity = 1;
        }, function() {
            if (!menuOn) _$("menuB").style.opacity = 0.4;
        });

        $("#midBlock").hover(function() {
            _$("midBlockb").style.opacity = 0;
            setTimeout(function() {
                _$("midBlockb").innerHTML = "SYNCSTREAM<br/>V1.0 BETA";
                _$("midBlockb").style.opacity = 1;
            }, 210);
        }, function() {
            _$("midBlockb").style.opacity = 0;
            setTimeout(function() {
                _$("midBlockb").innerHTML = '<img src = "./img/warp2cll.png"/>';
                _$("midBlockb").style.opacity = 1;
            }, 210);
        });

        /*
        for (var i = 1; i <= 4; i++) {
        	$("#link" + i).hover(function() {
        		_$("linkImg" + i.toString()).style.width = 140;
        		_$("linkImg" + i.toString()).style.height = 140;
        	}, function() {
        		_$("linkImg" + i.toString()).style.width = 60;
        		_$("linkImg" + i.toString()).style.height = 60;
        	});
        }
        */

        $("#link1").hover(function() {
            _$("linkImg1").style.width = 140;
            _$("linkImg1").style.height = 140;
        }, function() {
            _$("linkImg1").style.width = 60;
            _$("linkImg1").style.height = 60;
        });

        $("#link2").hover(function() {
            _$("linkImg2").style.width = 140;
            _$("linkImg2").style.height = 140;
        }, function() {
            _$("linkImg2").style.width = 60;
            _$("linkImg2").style.height = 60;
        });

        $("#link3").hover(function() {
            _$("linkImg3").style.width = 140;
            _$("linkImg3").style.height = 140;
        }, function() {
            _$("linkImg3").style.width = 60;
            _$("linkImg3").style.height = 60;
        });

        $("#link4").hover(function() {
            _$("linkImg4").style.width = 140;
            _$("linkImg4").style.height = 140;
        }, function() {
            _$("linkImg4").style.width = 60;
            _$("linkImg4").style.height = 60;
        });
    }

    function initLoad() {
        setEvents();
        syncjQ = $("#linkImg2");
        timerjQ = $("#linkImg3");
        synjQ.addClass("spin");
        _$("load").style.opacity = "1";
        _$("art").style.opacity = "0";
        audioRoot = "./audio/";
        setTimeout(syncNow, 30);
        setTimeout(initBlur, 50);
        setTimeout(retrieve, 100);
        setInterval(setTimeDisp, 500);
    }

    function toggleMenu() {
        if (menuOn) {
            menuOn = false;
            _$("menuB").style.opacity = 0.4;
            _$("menuTain").style.opacity = 0;
            _$("menuTain").style.zIndex = -20;
            _$("main").style.opacity = 1;
        } else {
            menuOn = true;
            _$("menuB").style.opacity = 1;
            _$("menuTain").style.opacity = 1;
            _$("menuTain").style.zIndex = 34;
            _$("main").style.opacity = 0;
        }
    }

    function syncNow() {
        rotateElement(360, 1200, syncjQ);
        ret(true);
    }

    function retIntPrompt() {
        _$("menuTain").style.opacity = 0;
        _$("menuTain").style.zIndex = -20;
        _$("prompt").style.zIndex = 35;
        _$("prompt").style.opacity = 1;
        _$("numIn").focus();
    }

    function check() {
        var n = _$("numIn").value;
        if ((!isNaN(n)) && (n < 30) && (n > 1)) {
            retTime = n * 1000;
            randTime = false;
            back();
        } else {
            _$("numIn").placeholder = "no";
        }
    }

    function rand() {
        randTime = true;
        back();
    }

    function back() {
        _$("prompt").style.opacity = 0;
        _$("menuTain").style.opacity = 1;
        _$("menuTain").style.zIndex = 34;
        setTimeout(function() {
            _$("prompt").style.zIndex = -20;
        }, 1500);
        setTimeout(function() {
            rotateElement(360, 1200, timerjQ);
        }, 300);
        setTimeout(function() {
            rotateElement(-360, 1200, syncjQ);
        }, 300);
        ret(true);
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

    $(window).on("orientationchange", initBlur);
    $(window).resize(initBlur);
    $(document).ready(initLoad);
    </script>
</head>

<body id="body">
    <div id="all">
        <div id="bgDiv"></div>
        <div id="prompt" class="container">
            <div class="content">
                <input id="numIn" type="number" required max="30" min="1" placeholder="1 - 30" />
                <span style="font-size: 8px;"><br /><br /></span>
                <img class="goImg" src="./img/x.png" height="25px" onclick="back()" />
                <img class="goImg" src="./img/rand.png" height="25px" onclick="rand()" />
                <img class="goImg" src="./img/check.png" height="25px" onclick="check()" />
            </div>
        </div>
        <div id="menuBC" class="container">
            <div class="content"><img id="menuB" src="./img/menuB1.png" onclick="toggleMenu()" /></div>
        </div>
        <div id="menu">
            <div id="menuTain" class="container">
                <div id="menuTent" class="content">
                    <center>
                        <table>
                            <center>
                                <tr>
                                    <td><a href="#">
                                            <div onclick="toggleVol()" class="link" id="link1"><b><img height="60"
                                                        width="60" src="./img/volOn.png" class="linkImg"
                                                        id="linkImg1" /></b></div>
                                        </a></td>
                                    <td><a href="#">
                                            <div onclick="retIntPrompt()" class="link" id="link3"><b><img height="60"
                                                        width="60" src="./img/timer.png" class="linkImg"
                                                        id="linkImg3" /></b></div>
                                        </a></td>
                                    <td id="midBlock"><a href="#">
                                            <div onclick="toggleMenu()" class="link"><b id="midBlockb"><img
                                                        src="./img/warp2cll.png" /></b></div>
                                        </a></td>
                                    <td><a href="#">
                                            <div onclick="syncNow()" class="link" id="link2"><b><img height="60"
                                                        width="60" src="./img/sync2.png" class="linkImg"
                                                        id="linkImg2" /></b></div>
                                        </a></td>
                                    <td><a href="#">
                                            <div onclick="toggleLink()" class="link" id="link4"><b><img height="60"
                                                        width="60" src="./img/link.png" class="linkImg"
                                                        id="linkImg4" /></b></div>
                                        </a></td>
                                </tr>
                            </center>
                        </table>
                    </center>
                </div>
            </div>
            <!--
				<div id = "menuTain" class = "container">
					<div id = "menuTent" class = "content">
						SYNCSTREAM<br/>V1.0 BETA
					</div>
				</div>
				<div class = "container" id = "menuC">
					<div class = "content">
						<img src = "./img/MangoBWTransWarp.png" height = "70%" onclick = ""/>
					</div>
				</div>
				-->
        </div>
        <div class="container" id="main">
            <div class="content">
                <center>
                    <audio src="" id="ausrc" type="audio/mpeg" preload="none" autoplay>
                        Your browser does not support the audio element.
                    </audio>
                    <audio src="" id="ausrc2" type="audio/mpeg"></audio>
                    <div id="title"><b>Name Unknown</b></div>
                    <div id="time">0:00</div>
                    <br />
                    <div class="container" id="lc">
                        <div class="content">
                            <img id="load" src="./img/loading.gif" />
                        </div>
                    </div>
                    <!--<div class = "container" id = "lc">
							<div class = "content">
								<img id = "art" src = "./img/warp2L.png" style = "z-index: 20;"/>
							</div>
						</div>-->
                    <img id="art" src="./img/loading.gif" />
                    <br /><br />
                    <div id="artist">Artist Unknown</div>
                    <div id="album"><b>Album Unknown</b></div>
                    <br /><br />
                </center>
            </div>
        </div>
    </div>
</body>

</html>