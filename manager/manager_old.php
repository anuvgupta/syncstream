<html>

<head>
    <title>
        SyncStream Manager
    </title>
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="MangoBW.png" />
    <script src="./js/jquery-2.1.4.min.js"></script>
    <script src="./js/id3-minimized.js"></script>
    <script src="./js/background-blur.min.js"></script>
    <script src="./js/jquery.mb.audio.js"></script>
    <script src="./js/functions.js"></script>
    <style>
    .container {
        display: table;
        height: 100%;
        width: 100%;
        text-align: center;
        position: absolute;
    }

    .container2 {
        display: table;
        height: 100%;
        width: 100%;
        text-align: center;
    }

    .content {
        display: table-cell;
        vertical-align: middle;
        height: 100%;
        width: 100%;
    }

    .content2 {
        display: table-cell;
        vertical-align: middle;
        width: 0px;
    }

    body {
        background-color: black;
        color: white;
        text-decoration: none;
        text-align: center;
        font-family: Verdana;
        font-size: 1.15em;
        text-shadow: 0px 0px 30px #000000;
        overflow-y: scroll;
        overflow-x: hidden;
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
        opacity: 1;
        height: 45;
        padding: 0vw 0vw 0vw 0vw;
    }

    #load {
        -webkit-transition: opacity 0.4s;
        -moz-transition: opacity 0.4s;
        -o-transition: opacity 0.4s;
        transition: opacity 0.4s;
        opacity: 1;
        height: 45;
        padding: 0vw 0vw 0vw 0vw;
    }

    #lc {
        top: 0;
        left: 0;
    }

    #dock {
        position: fixed;
        width: 100%;
        height: 25%;
        background-color: gray;
        padding: 5px;
        bottom: 8px;
        text-align: center;
    }

    table {
        text-align: center;
    }
    </style>
    <script>
    function initBlur() {
        $('#bodyDiv').backgroundBlur({
            imageURL: './blackbox.jpeg',
            blurAmount: 50,
            imageClass: 'bg-blur',
            duration: 1010,
            endOpacity: 0.7
        });
    }

    function loadBlur(imgPath) {
        $('#bodyDiv').backgroundBlur(imgPath);
    }

    function setSongInfo(path) {
        console.log(path);
        //_$("ausrc").src = path;
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

    function initLoad() {
        _$("load").style.opacity = "1";
        _$("art").style.opacity = "0";
        setTimeout(initBlur, 50);
        //setTimeout(retrieve, 100);
        //setInterval(retrieve, Math.floor(Math.random() * 2000) + 1000);
        //setInterval(setTimeDisp, 500);

        setSongInfo("./audio/1-01 The 1975.mp3");
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
    <div id="dock">
        <table border="5px" align="center">
            <tr>
                <td>
                    <div id="artist">
                        <div class="container2">
                            <div class="content" style="text-align: right;">
                                Artist Unknown
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div id="time"><b>0:00</b></div>
                        <br />
                        <div class="container2" id="lc">
                            <div class="content">
                                <img id="load" src="./loading.gif" />
                            </div>
                        </div>
                        <img id="art" src="./loading.gif" />
                        <br /><br />
                        <div id="title"><b>Name Unknown</b></div>
                    </div>
                </td>
                <td>
                    <div id="album">
                        <div class="container2">
                            <div class="content" style="text-align: left;">
                                Album Unknown
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>