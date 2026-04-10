<script src="https://player.vimeo.com/api/player.js"></script>

<script>
    function play_me() {
        var iframe = document.querySelector('iframe');
        var player = new Vimeo.Player(iframe);
        var student = {{Auth::user()->id}};

        player.on('play', function () {
            var time = 1;

            var interval = setInterval(function() {
               if (time <= 300) {
                   // alert(time);
                   progress_update()
                   const m_url = "/api/master_class/get_progress/"+{{$current_material->lesson->master_class->id}}+"/"+{{Auth::user()->id}};
                   var class_prog = getProgress(m_url);
                   document.getElementById('class_progress').style.width = class_prog + "%";
                   document.getElementById('cp').innerHTML = class_prog + "%";

                   const p_url = "/api/lesson/get_progress/"+{{$current_material->lesson->id}}+"/"+{{Auth::user()->id}};
                   var less_prog = getProgress(p_url);

                   document.getElementById('lsps_{{$current_material->lesson->id}}').style.width = less_prog + "%";
                   document.getElementById('lsp_{{$current_material->lesson->id}}').innerHTML = less_prog + "%";

                   const mat_url = "/api/material/get_progress/"+{{$current_material->id}}+"/"+{{Auth::user()->id}};
                   var mat_prog = getProgress(mat_url);

                   document.getElementById('mp_{{$current_material->id}}').innerHTML = mat_prog + "%";
                   time++;
               }
               else {
                  clearInterval(interval);
               }
            }, 10000);
            console.log('Played the video');
        });

        player.on('ended', function(data) {
            player.getVideoId().then(function (id) {
                const url = "/student/"+student+"/material/"+id+"/update_video_progress/"+100
                console.log(url)
                httpGet(url);
            });
        });

        player.getVideoTitle().then(function (title) {
            console.log('title:', title);
        });

        player.getVideoId().then(function (id) {
            const url = "/student/"+student+"/material/"+id+"/update_video_progress/"+1
            console.log(url)
            httpGet(url);
        });

    }

    function progress_update() {
        var iframe = document.querySelector('iframe');
        var player = new Vimeo.Player(iframe);
        var student = {{Auth::user()->id}};

        var dur;
        var cur;

        player.getDuration().then(function(duration) {
          // `duration` indicates the duration of the video in seconds
            dur = duration;
            // console.log(duration+"j")
        });

        player.getCurrentTime().then(function(seconds) {
          // `seconds` indicates the current playback position of the video
            cur = seconds;
            // console.log(seconds)
        });

        player.getVideoId().then(function (id) {
                const url = "/student/"+student+"/material/"+id+"/update_video_progress/"+(cur/dur*100)
                console.log(url)
                httpGet(url);
        });
    }

    function update(student,material) {
        const url = "/student/"+student+"/material/"+material+"/update_video_progress"
        console.log(url)
        httpGet(url);
    }
    function httpGet(theUrl)
    {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
        xmlHttp.setRequestHeader('Content-Type', 'application/json');
        xmlHttp.send(JSON.stringify({
            value: "value"
        }));
        return xmlHttp.responseText;
    }

    function getProgress(theUrl)
    {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
        xmlHttp.send( null );
        return xmlHttp.responseText;
    }

    function go_to(id) {
        window.location.href = '?material='+id;
    }

</script>
