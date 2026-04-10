<!DOCTYPE html>
<!-- saved from url=(0127)https://yari-demos.prod.mdn.mozit.cloud/en-US/docs/Web/API/Media_Capture_and_Streams_API/Taking_still_photos/_sample_.demo.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="robots" content="noindex, nofollow">

        <title>Taking still photos with getUserMedia() - demo - code sample</title>

    </head>
    <body>

        <div class="message">
        </div>

<script>
    function send_message() {
        // Set up an asynchronous communication channel that will be
        // used during the peer connection setup
        const signalingChannel = new SignalingChannel(remoteClientId);
        signalingChannel.addEventListener('message', message => {
            // New message from remote client received
        });

        // Send an asynchronous message to the remote client
        signalingChannel.send('Hello!');
    }

</script>
</body>
</html>
