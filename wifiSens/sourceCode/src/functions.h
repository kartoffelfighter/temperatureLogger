

/* sample call for sendValue()
if(sendValue(0x00, 20, 100, 0x00)){
  Serial.println("Success!!");
} else {
  Serial.println("I failed sending the test values!");
}

*/


bool sendValue(int action, float valueTemp, float valueHumid, int comment, float valueAccu){
  String payload; // recieved Value from json library
  HTTPClient http;

  valueTemp = (valueTemp+OFFSET)*1000;
  valueHumid = (valueHumid)*1000;
  valueAccu = (valueAccu);

  long dbTemp = (long)valueTemp;
  long dbHumid = (long)valueHumid;
  long dbAccu = (long)valueAccu;
  //String action = itoa(action, HEX);
  String hexcomment;
  //sprintf(hexTemp,"%0x", valueTemp);
  //sprintf(hexHumid,"%0x", valueHumid);
  //sprintf(hexComment,"%0x", comment);


  String url = SERVER_BASE_URL;
  url += "?key=";
  url += API_KEY;
  url += "&action=";

  //url += "handshake";

  switch(comment){
    case '0':
    hexcomment = "0";
    case '1':
    hexcomment = "0x001"; // Marker set
    break;
  }

  switch(action){
    case 0x00:
      url += "writeValue&data={\"sensid\":\"";
      url += SENSID;
      url += "\",\"temp\":\"";
      url += String(dbTemp, HEX);
      url += "\",\"humid\":\"";
      url += String(dbHumid, HEX);
      url += "\",\"comment\":\"";
      url += hexcomment;
      url += "\",\"accu\":\"";
      url += String(dbAccu, HEX);
      url += "\"}";   // comment will be the error handler in future
    break;
  }

  http.begin(SERVER_HOST, SERVER_PORT, url);

  Serial.println("Sending query to Server: ");
  Serial.println(url);

  int httpCode = http.GET();
  if(httpCode) {
    if(httpCode == 200){
      payload = http.getString();
      Serial.println("Recieved HTTP CODE 200");
      Serial.print("Decoding payload (raw): ");
      Serial.println(payload);
      DynamicJsonBuffer jsonBuffer;   // Safe Buffer Space for Arduino JSON

      JsonObject& returned = jsonBuffer.parseObject(payload);
      String success = returned["success"];
      if(success == "true"){
        Serial.println("Successfully send value to Server!");
          return true;
      }
      else {
        return false;
      }
    }
  }
}


void alive() {
  Serial.println("5 minutes passed, see my information ");
  Serial.print("IP-Adress: ");
  Serial.println(WiFi.localIP());
  Serial.print("Runtime: ");
  Serial.print(millis()/1000/60);
  Serial.println(" min");
  Serial.println("API Connection last renewed and time updated: ");
  Serial.print(hour(lastRenew));  Serial.print(":"); Serial.print(minute(lastRenew)); Serial.print(":"); Serial.print(second(lastRenew)); Serial.print(" @ "); Serial.print(day(lastRenew)); Serial.print("."); Serial.print(month(lastRenew)); Serial.print("."); Serial.println(year(lastRenew));
  Serial.println(".............................");
  Serial.print("Current time (unix): ");
  Serial.println(now());
  Serial.print("Human Readable: ");
  Serial.print(hour(now()));
  Serial.print(":");
  Serial.print(minute(now()));
  Serial.print(":");
  Serial.print(second(now()));
  Serial.print(" Day: ");
  Serial.print(day(now()));
  Serial.print(".");
  Serial.print(month(now()));
  Serial.print(".");
  Serial.print(year(now()));
  Serial.println(" !");

}


void measure() {
  //Serial.println("measure called");
  humidity = dht.getHumidity();
  temperature = dht.getTemperature();
}

void sample() {
  float tempTemp = 0;
  float tempHumid = 0;
  int iSample = 0;
  millisBeginnSampling = millis();
  int millisSamplingRate = 0;
  while(millis() <= millisBeginnSampling + SAMPLES*1000){
    if(millis() >= millisSamplingRate + SAMPLE_RATE*1000){
      measure();
      tempTemp += temperature;
      tempHumid += humidity;
      iSample++;
      Serial.print("Sample nb. ");
      Serial.println(iSample, HEX);
      delay(100);
    }
  }
  tempSample = tempTemp/iSample;
  humidSample = tempHumid/iSample;
  Serial.println("Sampled some temp and humid values");
  Serial.print("Temp: ");
  Serial.print(tempSample);
  Serial.println("°C");
  Serial.print("humid: ");
  Serial.print(humidSample);
  Serial.println("%");

}
