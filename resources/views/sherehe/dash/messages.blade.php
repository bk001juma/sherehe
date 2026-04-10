@extends('layouts.dash')

@section('template_title')
    My Classes
@endsection

@php
function last_seen($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
@endphp

@section('content')
<div class="dashboard__content bg-light-4">
  <div class="row pb-50 mb-10">
    <div class="col-auto">

      <h1 class="text-30 lh-12 fw-700">Messages</h1>

    </div>
  </div>


  <div class="row y-gap-30">
    <div class="col-xl-4">
      <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">
        <div class="d-flex items-center py-20 px-30 border-bottom-light">
          <h2 class="text-17 lh-1 fw-500">Recent Chats</h2>
        </div>

        <div class="py-30 px-30 overflow-scroll" style="height: 500px">
{{--            <form>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="text" placeholder="Search Contact">--}}
{{--                    <button type="submit">Search</button>--}}
{{--                </div>--}}
{{--            </form>--}}

            @foreach($unique_classes as $class)
                <div class="y-gap-30" onclick="event.preventDefault();
                                 document.getElementById('chat-with-{{$class->tutor->id}}').submit();">
                    <div class="d-flex justify-between" >
                        <div class="d-flex items-center">
                            <div class="shrink-0">
                                <img src="/img/avatars/small/2.png" alt="image" class="size-50">
                            </div>
                            <div class="ml-10">
                                <div class="lh-11 fw-500 text-dark-1">{{$class->tutor->name}} {{$class->tutor->last_name}}</div>
                                <div class="text-14 lh-11 mt-5">Tutor</div>
                            </div>
                        </div>

                        <div class="d-flex items-end flex-column pt-8">
                            <div class="text-13 lh-1">{{last_seen($class->tutor->last_online)}}</div>

                            @isset($to->id)
                                @if($class->tutor->id == $to->id)
                                    <div class="d-flex justify-center items-center size-20 bg-blue-3 rounded-full mt-8">
                                        <span class="text-11 lh-1 text-white fw-500"></span>
                                    </div>
                                @endif
                            @endisset

                        </div>
                    </div>
                </div>


                <form id="chat-with-{{$class->tutor->id}}" action="{{ route('my_messages') }}" method="POST" style="display: none;">
                    @csrf
                    <input hidden="" value="{{$class->tutor->id}}" name="to">
                </form>
            @endforeach
            @if($teaching_classes != null)
                @foreach($teaching_classes as $class)
                    <h1>
                        {{$class->title}}
                    </h1><br>
                        @if(count($class->paid_students) < 1)
                            <h3 style="color: darkred">No Students</h3>
                            <hr>
                        @else
                            @foreach($class->paid_students->unique('name') as $student)
                                @if($student->id != Auth::user()->id)
                                    <div class="y-gap-30" onclick="event.preventDefault();
                                             document.getElementById('chat-with-{{$student->id}}').submit();">
                                        <div class="d-flex justify-between" >
                                            <div class="d-flex items-center">
                                                <div class="shrink-0">
                                                    <img src="/img/avatars/small/2.png" alt="image" class="size-50">
                                                </div>
                                                <div class="ml-10">
                                                    <div class="lh-11 fw-500 text-dark-1">{{$student->name}} {{$student->last_name}}</div>
                                                    <div class="text-14 lh-11 mt-5">Tutor</div>
                                                </div>
                                            </div>

                                            <div class="d-flex items-end flex-column pt-8">
                                                <div class="text-13 lh-1">{{last_seen($student->last_online)}}</div>

                                                @isset($to->id)
                                                    @if($student->id == $to->id)
                                                        <div class="d-flex justify-center items-center size-20 bg-blue-3 rounded-full mt-8">
                                                            <span class="text-11 lh-1 text-white fw-500"></span>
                                                        </div>
                                                    @endif
                                                @endisset

                                            </div>
                                        </div>
                                    </div>


                                    <form id="chat-with-{{$student->id}}" action="{{ route('my_messages') }}" method="POST" style="display: none;">
                                        @csrf
                                        <input hidden="" value="{{$student->id}}" name="to">
                                    </form>
                                @endif

                            @endforeach
                            <hr>
                        @endif

                @endforeach
            @endif
        </div>
      </div>
    </div>

    <div class="col-xl-8">
      <div class="rounded-16 bg-white -dark-bg-dark-1 shadow-4 h-100">
        <div class="d-flex items-center justify-between py-20 px-30 border-bottom-light">
            @isset($to->id)
              <div class="d-flex items-center">
                <div class="shrink-0">
                  <img src="/img/avatars/small/2.png" alt="image" class="size-50">
                </div>
                <div class="ml-10">
                  <div class="lh-11 fw-500 text-dark-1">{{$to->name}}</div>
                  <div class="text-14 lh-11 mt-5 text-blue-1">{{last_seen($to->last_online)}}</div>
                </div>
              </div>
                <a href="#" class="text-14 lh-11 fw-500 text-orange-1 underline">Delete Conversation</a>
            @else
                <div class="d-flex items-center">
                <div class="ml-10">
                  <div class="lh-11 fw-500 text-dark-1">Select or search Contact</div>
{{--                  <div class="text-14 lh-11 mt-5">Active</div>--}}
                </div>
              </div>
            @endisset

        </div>

        <div class="py-40 px-40 overflow-scroll" id="scroll_me" style="height: 500px;">
              <livewire:chat.live-conversation :to="$to" :conversation="$conversation" :user="$user"/>
        </div>
          @isset($to->id)
              <livewire:chat.send :recipient="$to->id" :conversation="$conversation" :user="$user"/>
          @endisset
      </div>
    </div>
  </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
                // your code here
                var element = document.getElementById('scroll_me');
                element.scrollTop = element.scrollHeight;

        }, false);

    function toBottom() {
        var element = document.getElementById('scroll_me');
            element.scrollTop = element.scrollHeight;
    }

    @isset($to->id)
        function myFunction(){
            Livewire.emit('refreshComponent',{{$to->id}});
            console.log('refreshed')
        };

        setInterval(function(){
            myFunction()
        }, 9000)
    @endisset

        function sendMessage() {
            var to = document.getElementsByName('to')
            var message = document.getElementsByName('message')
        }
    </script>

@endsection

@section('page_js')
            <script>
                (() => {
  // The width and height of the captured photo. We will set the
  // width to the value defined here, but the height will be
  // calculated based on the aspect ratio of the input stream.

  const width = 320; // We will scale the photo width to this
  let height = 0; // This will be computed based on the input stream

  // |streaming| indicates whether or not we're currently streaming
  // video from the camera. Obviously, we start at false.

  let streaming = false;

  // The various HTML elements we need to configure or control. These
  // will be set by the startup() function.

  let video = null;
  let canvas = null;
  let photo = null;
  let startbutton = null;

  function showViewLiveResultButton() {
    if (window.self !== window.top) {
      // Ensure that if our document is in a frame, we get the user
      // to first open it in its own tab or window. Otherwise, it
      // won't be able to request permission for camera access.
      document.querySelector(".contentarea").remove();
      const button = document.createElement("button");
      button.textContent = "View live result of the example code above";
      document.body.append(button);
      button.addEventListener("click", () => window.open(location.href));
      return true;
    }
    return false;
  }

  function startup() {
    if (showViewLiveResultButton()) {
      return;
    }
    video = document.getElementById("video");
    canvas = document.getElementById("canvas");
    photo = document.getElementById("photo");
    startbutton = document.getElementById("startbutton");

    navigator.mediaDevices
      .getUserMedia({ video: true, audio: false })
      .then((stream) => {
        video.srcObject = stream;
        video.play();
      })
      .catch((err) => {
        console.error(`An error occurred: ${err}`);
      });

    video.addEventListener(
      "canplay",
      (ev) => {
        if (!streaming) {
          height = video.videoHeight / (video.videoWidth / width);

          // Firefox currently has a bug where the height can't be read from
          // the video, so we will make assumptions if this happens.

          if (isNaN(height)) {
            height = width / (4 / 3);
          }

          video.setAttribute("width", width);
          video.setAttribute("height", height);
          canvas.setAttribute("width", width);
          canvas.setAttribute("height", height);
          streaming = true;
        }
      },
      false
    );

    startbutton.addEventListener(
      "click",
      (ev) => {
        takepicture();
        ev.preventDefault();
      },
      false
    );

    clearphoto();
  }

  // Fill the photo with an indication that none has been
  // captured.

  function clearphoto() {
    const context = canvas.getContext("2d");
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    const data = canvas.toDataURL("image/png");
    photo.setAttribute("src", data);
  }

  // Capture a photo by fetching the current contents of the video
  // and drawing it into a canvas, then converting that to a PNG
  // format data URL. By drawing it on an offscreen canvas and then
  // drawing that to the screen, we can change its size and/or apply
  // other changes before drawing it.

  function takepicture() {
    const context = canvas.getContext("2d");
    if (width && height) {
      canvas.width = width;
      canvas.height = height;
      context.drawImage(video, 0, 0, width, height);

      const data = canvas.toDataURL("image/png");
      photo.setAttribute("src", data);
    } else {
      clearphoto();
    }
  }

  // Set up our event listener to run the startup process
  // once loading is complete.
  window.addEventListener("load", startup, false);
})();

@endsection
