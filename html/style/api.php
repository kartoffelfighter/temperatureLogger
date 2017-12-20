<div class="container">
  <div class="card" style=";">
    <div class="card-header">
      Api description
    </div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a href="#errorcodes">Overall Error codes</a></li>
      <li class="list-group-item"><a href="#key">Api Key</a></li>
      <li class="list-group-item"><a href="#login">Login</a></li>
      <li class="list-group-item"><a href="#register">Register</a></li>
      <li class="list-group-item"><a href="#deleteUser">deleteUser</a></li>
      <li class="list-group-item"><a href="#writeValue">writeValue</a></li>
      <li class="list-group-item"><a href="#readValue">readValue</a></li>
      <li class="list-group-item"><a href="#handshake">handshake</a></li>
    </ul>
  </div>
  <div class="card" id="errorcodes">
    <div class="card-header">
      <h3>Overall Error Codes</h3>
    </div>
    <div class="card-body">
      <h4> Template </h4>
      json string <code>{"success":"false","errorcode":"xXX","description":"foobar"}</code>
      <h4> Overall </h4>
      0   =>    given API key is wrong
      <h4> User related </h4>
       10  =>    Email is already in use<br>
       <br>
       20  =>    The to the email belonging user was deleted from database<br>
       21  =>    The given email does not exist in database, user can not be deleted<br>
      <h4> ReadValue related </h4>
       100 => Sensor not found<br>
      <h4> mysql related </h4>
       900 =>    MYSQL Connection error<br>
       910 =>    email query failed<br>
       911 =>    register query failed<br>
      <h4> µC related </h4>
       0x001 => write values to database query failed<br>
</div>
</div>
<div class="card" id="key">
  <div class="card-header">
    <h3>API Key</h3>
  </div>
  <div class="card-body">
    Overall it is important to send always the <code>key=API_KEY</code>.<br>
    API accepts the key as <code>_GET</code> also as <code>_POST</code><br>
</div>

<div class="card" id="login">
  <div class="card-header">
    <h3>Login</h3>
  </div>
  <div class="card-body">
    <i>not implemented yet.</i>
  </div>
</div>
<div class="card" id="register">
  <div class="card-header">
    <h3>Register</h3>
  </div>
  <div class="card-body">
    <code>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
      @     register API Call<br>
      @-----------------------------<br>
      @<br>
      @ register can handle to kinds of calls:<br>
      @ standard-post:<br>
      @ register expects a _POST value as following:<br>
      @ username, password, email<br>
      @-----------------------<br>
      @json:<br>
      @ Post a value named "credits" by following template:<br>
      @ {"username":"foo","password":"bar","email":"foobar@foobar";}<br>
      @<br>
      @-------------------------<br>
      @   returned value is always json<br>
      @   Template for success:<br>
      @   {"success":"true","newUserID":"35","newUserEmail":"test1@mail.com"}<br>
      @<br>
      @   Template for failure:<br>
      @   {"success":"false","errorcode":"10","description":"Email bereits belegt"}<br>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
</code>
  </div>
</div>
<div class="card" id="deleteUser">
  <div class="card-header">
    <h3>deleteUser</h3>
  </div>
  <div class="card-body">
    <i>not implemented yet.</i>
  </div>
</div>
<div class="card" id="writeValue">
  <div class="card-header">
    <h3>writeValue</h3>
  </div>
  <div class="card-body">
    <code>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
      @     writeValue API Call<br>
      @-----------------------------<br>
      @ writeValue handles calls from the wifi-sensor.<br>
      @<br>
      @------------------------------<br>
      @ Example Call:<br>
      @ ?action=writeValue&data={"sensid":"0x001","temp":"0x014","humid":"0x063","comment":"0x00","accu":"0x00"}<br>
      @-----------------------<br>
      @json:<br>
      @ Post a value named "credits" by following template:<br>
      @ {"sensid":"[HEX]0x001","temp":"[HEX]0x014","humid":"[HEX]0x063","comment":"[TEXT] hello","accu":"[HEX]0x00"}<br>
      @<br>
      @-------------------------<br>
      @ writeValue return a json string:<br>
      @ if the string fits and all values are passed:<br>
      @ {"success":"true"}<br>
      @ if the string missfits<br>
      @ {"success":"false","errorcode":"0x001"} // returnes hex-codes to decode on µC<br>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
    </code>
  </div>
</div>
<div class="card" id="readValue">
  <div class="card-header">
    <h3>readValue</h3>
  </div>
  <div class="card-body">
    <code>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
      @     readValue API Call<br>
      @-----------------------------<br>
      @ readValue handles calls from the wifi-sensor.<br>
      @<br>
      @------------------------------<br>
      @ Example Call:<br>
      @ ?action=readValue&sens=0x001<br>
      @-----------------------<br>
      @ blank/html _GET<br>
      @-------------------------<br>
      @ writeValue return a json string:<br>
      @<br>
      @ returns json like {"success":"true", "data":[{"id":"1","sensor":"0x001","temp":"0x014","humid":"0x063","time":"1512323121","comment":"0x00"},....]}<br>
      @<br>
      @<br>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
    </code>
  </div>
</div>
<div class="card" id="handshake">
  <div class="card-header">
    <h3>handshake</h3>
  </div>
  <div class="card-body">
    <code>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
      @ Handshake with sensor<br>
      @ Sensor sends nothing to api (just the call)<br>
      @ Server returns true & current server time in unix-format<br>
      @-------------------------<br>
      @ json return string format:<br>
      @ {"success":"true","time":"1512751312"}<br>
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@<br>
    </code>
  </div>
</div>
