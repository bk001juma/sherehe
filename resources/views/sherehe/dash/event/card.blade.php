<html>
<style>
    #outerShell{
  height: 590px;
  width: 400px;
  background: #00425C;
  margin:auto;
  box-shadow: 10px 10px 10px #CCCCCC;
  }

#stars{
  width: 380px;
  margin:10px;
  z-index: 1;
}

#unicorn{
  width: 300px;
  margin-left:50px;
  z-index: 2;
  position: relative;
  top: -575px;
}

#infoBox{
  background-color: #00425C;
  border-radius: 100px;
  border: dotted 2px #EA65A4;
  box-shadow: 2px 2px 10px;
  position: relative;
  top: -575px;
  margin-left: 75px;
  width: 250px;
  height: 300px;
  text-align: center;
}

ul{
  margin-top: 60px;
  list-style: none;
  padding: 0px;
  font-family: "Arial Black", Gadget, sans-serif;
}

#name{
  font-size: 25px;
  color: #EB65A2;
  text-shadow: 5px 2px 10px black;
}

.whiteText{
  color: #F9F9F9;
  font-size: 10px;
}

#date{
  color: #F1B0C6;
  font-size: 20px;
  padding-bottom: 5px;
}

#place{
  color: #B76599;
  font-size: 14px;
}

#padding{
  padding-top: 8px;
}

#top{
  padding-bottom: 8px;
}

</style>

  <head>
    <title>Birthday Invitation</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
      <div id='outerShell' style="width: 100%">
        <img id="stars" src="https://github.com/RebekahAmerson/invitation-Mockup/blob/master/Stars.png?raw=true">
        <img id="unicorn" src="https://github.com/RebekahAmerson/invitation-Mockup/blob/master/Unicorn.png?raw=true">
        <div id="infoBox">
          <ul>
            <li id="top" class="whiteText">Join us to celebrate the birthday of</li>
            <li id="name">{{$event->event_name}}</li>
            <li class="whiteText">10:00 am</li>
            <li id="date">6.17.18</li>
            <li id="place">Serenity Park</li>
            <li class="whiteText">823 S Broadway</li>
            <li class="whiteText">Happy Town, USA</li>
            <li class="whiteText padding">RSVP to save your slice of cake.</li>
            <li class="whiteText padding">For more information, call 867-5309</li>
          </ul>
      </div>
      </div>
  </body>
</html>
