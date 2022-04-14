<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=.7">
    <meta name="og:image" content="VCNU1.png">
		<link rel="shortcut icon" type="image/png" href="VCNU1.png"/>
    <title>magic playlist</title>
		<meta name="description" content="universal jukebox...">
    <style>
      body, html{
        border: 0;
        background: linear-gradient(45deg, #000, #103);
        background-repead: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        overflow: hidden;
        color:#cfd;
        font-size: .85em;
        font-family: courier;
      }
      .main{
        text-align: center;
        padding-bottom: 100px;
        z-index: 10;
        top: 0;
        left: 0;
        width: 100%;
      }
      .songButton{
        border-radius: 15px;
        display: inline-block;
        border: none;
        width: 95%;
        min-width: 600px;
        cursor: pointer;
        color: #afa;
        font-family: courier;
        font-size: 2em;
        padding: 10px;
        margin: 10px;
        text-align: left;
        text-shadow: 1px 1px 2px #000;
        padding-left: 60px;
        padding-right: 5px;
        background-image: url(2ftyk1.png);
        background-color: #004;
        background-repeat: no-repeat;
        background-position: 10px center;
        background-size: 45px 45px;
      }
      #playerFrame{
        left: 0;
        top:0;
        margin-top:0px;
        width:100%;
        min-width: 600px;
        max-width: 70%;
        height:290px;
        border:none;
        vertical-align:top;
      }
      input[type=checkbox]{
        transform: scale(2.0);
      }
      label{
        font-size: 2em;
      }
      .jumpButton{
        position: fixed;
        left: 0;
        top: 0;
        margin: 20px;
        border-radius: 5px;
        font-size: 16px;
        background: #408;
        color: #fff;
        border: none;
        z-index: 20;
        font-weight: 900;
        font-family: courier;
        cursor: pointer;
      }
      .trackButtons{
        margin-top: 0px;
        width:100%;
        min-width: 600px;
        max-width: 75%;
        display: inline-block;
        max-height: calc(100vh - 370px);
        overflow-x: hidden;
        overflow-y: auto;
      }
    </style>
  </head>
  <body>
    <div class="main">
      <br>
      <label for="shuffle">
        <input type="checkbox" checked id="shuffle" oninput="toggleShuffle(this)">
        shuffle
      </label>
      <br><br>
      <iframe
        id="playerFrame"
        src=""
      ></iframe>
      <br><br>
    <script>
      Rn=Math.random
      userInteracted = false
      
      scrollUp=()=>{
        window.scrollTo(0, 0)
      }
      
      playTrack=idx=>{
        let el
        (el = document.querySelector('#playerFrame'))
        postMessage(JSON.stringify({'userInteracted': userInteracted}))
        el.src = 'https://audioplayer.dweet.net/' + window.location.href + tracks[idx] + (userInteracted ? '?autoplay' : '')
        
      }
    </script>
      <div class="trackButtons">
      <?
        $idx=0;
        foreach (glob("tracks/*.mp3") as $filename) {
          $file = str_replace("tracks/", "", $filename);
          ?><button class="songButton" onclick="playTrack(curIDX = <?echo $idx;?>)"><?
          echo "$file" . "<br>";
          ?></button><br><?
          $idx++;
        }
      ?>
      </div>
      
    <script>
      curIDX = 0
      postMessage=msg=>{
        let el = document.querySelector('#playerFrame')
        if(el.src.indexOf('https://audioplayer.dweet.net') != -1){
          el.contentWindow.postMessage(msg, 'https://audioplayer.dweet.net')
        }
      }
      window.addEventListener('message', e => {
        const key = e.message ? 'message' : 'data';
        const data = e[key];
        switch(data){
          case 'ended':
            playTrack(shuffle ? Rn()*tracks.length|0 : curIDX=(curIDX+1)%tracks.length)
          break
          case 'playing':
            userInteracted = true
          break
        }
      },false);
      shuffle = true
      toggleShuffle=e=>{
        shuffle = e.checked
      }
      tracks = [
        <?
          foreach (glob("tracks/*.mp3") as $filename) {
            $file = str_replace('/','', str_replace("tracks/", "", $filename));
            echo "'/tracks/".rawurlencode("$file") . "'" . ",";
          }
        ?>
      ]
      
      vid = document.createElement('video')
      vid.style.position = 'absolute'
      vid.style.opacity = '0'
      vid.style.top = '0'
      vid.style.zIndex=-1;
      vid.style.mouseEvents = 'none'
      vid.style.left = '0'
      document.body.appendChild(vid)
      vid.src='sleepBuster.mp4'
      vid.loop=true
      vid.muted=true
      window.onclick=()=>{
        vid.play()
      }
      vid.play()
      playTrack(shuffle ? Rn()*tracks.length|0 : curIDX=(curIDX+1)%tracks.length)
    </script>
    </div>
  </body>
</html>
