

/* sample call for sendValue()
if(sendValue(0x00, 20, 100, 0x00)){
  Serial.println("Success!!");
} else {
  Serial.println("I failed sending the test values!");
}

*/


bool sendValue(int action, int valueTemp, int valueHumid, int comment){
  String payload; // recieved Value from json library
  HTTPClient http;

  //String action = itoa(action, HEX);
  char hexTemp[4], hexHumid[4], hexComment[4];
  //sprintf(hexTemp,"%0x", valueTemp);
  //sprintf(hexHumid,"%0x", valueHumid);
  //sprintf(hexComment,"%0x", comment);


  String url = SERVER_BASE_URL;
  url += "?key=";
  url += API_KEY;
  url += "&action=";

  //url += "handshake";

  switch(action){
    case 0x00:
      url += "writeValue&data={\"sensid\":\"";
      url += SENSID;
      url += "\",\"temp\":\"";
      url += String(valueTemp, HEX);
      url += "\",\"humid\":\"";
      url += String(valueHumid, HEX);
      url += "\",\"comment\":\"0x00\"}";   // comment will be the error handler in future
    break;
  }

  http.begin(SERVER_HOST, SERVER_PORT, url);

  Serial.print("Sending query to Server: ");
  Serial.println(url);

  int httpCode = http.GET();
  if(httpCode) {
    if(httpCode == 200){
      payload = http.getString();
      Serial.println("Recieved HTTP CODE 200");
      Serial.print("Recieved payload: ");
      Serial.println(payload);
    }
  }
  return true;
}
