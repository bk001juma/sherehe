<html>
<style>
     /* Container holding the image and the text */
.container {
  position: relative;
  text-align: center;
  color: white;
}

/* Bottom left text */
.bottom-left {
  position: absolute;
  bottom: 8px;
  left: 16px;
}

/* Top left text */
.top-left {
  position: absolute;
  top: 8px;
  left: 16px;
}

/* Top right text */
.top-right {
  position: absolute;
  top: 8px;
  right: 16px;
}

/* Bottom right text */
.bottom-right {
  position: absolute;
  bottom: 8px;
  right: 16px;
}

/* Centered text */
.centered {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
<body>
<div class="container">
    <img src="{{url('/')}}/card.png" alt="Snow" style="width:100%;">
{{--    <div class="bottom-left">Bottom Left</div>--}}
{{--    <div class="top-left">Top Left</div>--}}
{{--    <div class="top-right">Top Right</div>--}}
{{--    <div class="bottom-right">Bottom Right</div>--}}
    <div class="centered" style="color: darkgoldenrod">{{$event->event_name}}</div>
</div>
</body>
</html>
