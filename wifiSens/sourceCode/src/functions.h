bool debounce(int pin)
{ // feed with pin. Will block the prozessor for about 200mS
  int BMillis;
  int BBMillis = BMillis = millis();
  int Breturn = 0;
  while (BMillis + 200 <= millis())
  {
    if (BBMillis + 10 >= millis())
    {
      if (digitalRead(pin))
      {
        Breturn++;
      }
    }
  }
  if (Breturn >= 11)
  {
    return true;
  }
  else
  {
    return false;
  }
}

void wifiSetup()
{
  yield();
}

void readAccuLoad()
{
  AccuLoading = debounce(accuLoading); //AccuLoading = set, if accuLoading pin is high
  AccuFull = debounce(accuFull);       //AccuLoading = set, if accuLoading pin is high
}

bool sendValue(int action, float valueTemp, float valueHumid, int comment, float valueAccu)
{
  String payload; // recieved Value from json library
  HTTPClient http;

  valueTemp = (valueTemp + OFFSET) * 1000;
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

  switch (comment)
  {
  case '0':
    hexcomment = "0";
  case '1':
    hexcomment = "0x001"; // Marker set
    break;
  }

  switch (action)
  {
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
    url += "\"}"; // comment will be the error handler in future
    break;
  }

  http.begin(SERVER_HOST, SERVER_PORT, url);

  Serial.println("Sending query to Server: ");
  Serial.println(url);

  int httpCode = http.GET();
  if (httpCode)
  {
    if (httpCode == 200)
    {
      payload = http.getString();
      Serial.println("Recieved HTTP CODE 200");
      Serial.print("Decoding payload (raw): ");
      Serial.println(payload);
      DynamicJsonBuffer jsonBuffer; // Safe Buffer Space for Arduino JSON

      JsonObject &returned = jsonBuffer.parseObject(payload);
      String success = returned["success"];
      if (success == "true")
      {
        Serial.println("Successfully send value to Server!");
        return true;
      }
      else
      {
        return false;
      }
    }
  }
}

void alive()
{
  Serial.println("i am alive");
  Serial.print("IP-Adress: ");
  Serial.println(WiFi.localIP());
  Serial.print("Runtime: ");
  Serial.print(millis() / 1000 / 60);
  Serial.println(" min");
  Serial.println("API Connection last renewed and time updated: ");
  Serial.print(hour(lastRenew));
  Serial.print(":");
  Serial.print(minute(lastRenew));
  Serial.print(":");
  Serial.print(second(lastRenew));
  Serial.print(" @ ");
  Serial.print(day(lastRenew));
  Serial.print(".");
  Serial.print(month(lastRenew));
  Serial.print(".");
  Serial.println(year(lastRenew));
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

void measure()
{
  //Serial.println("measure called");
  delay(dht.getMinimumSamplingPeriod());
  humidity = dht.getHumidity();
  temperature = dht.getTemperature();
}

void analogMeasure()
{
  accu = (3.3 / 1024 * analogRead(ADC)) * 2000;
}

void sample()
{
  float tempTemp = 0;
  float tempHumid = 0;
  float tempAccu = 0;
  int iSample = 0;
  millisBeginnSampling = millis();
  int millisSamplingRate = 0;
  while (millis() <= millisBeginnSampling + SAMPLES * 1000)
  {
    if (millis() >= millisSamplingRate + SAMPLE_RATE * 1000)
    {
      measure();
      analogMeasure();
      tempTemp += temperature;
      tempHumid += humidity;
      tempAccu += accu;
      iSample++;
      Serial.print("Sample nb. ");
      Serial.println(iSample, DEC);
    }
  }
  tempSample = tempTemp / iSample;
  humidSample = tempHumid / iSample;
  accuSample = tempAccu / iSample;
  Serial.println("Sampled some temp and humid values");
  Serial.print("Temp: ");
  Serial.print(tempSample);
  Serial.println("°C");
  Serial.print("humid: ");
  Serial.print(humidSample);
  Serial.println("%");
  Serial.print(" Accu:");
  Serial.print(accuSample);
  Serial.println("mV");
}

void sleep()
{
  ESP.deepSleep((INTERVALL - 1) * 60);
}

bool writeSDheader()
{
  // create directory for value files on sd card
  if (!sd.exists(MEASUREMENT_DIR))
  {
    sd.mkdir(MEASUREMENT_DIR);
    Serial.println(F("created new directory for measurements"));
  }
  // generate file name from string  (File name should be: DIR/daymonthyear.EXTENSION)
  String fileNameString = MEASUREMENT_DIR; // dir name
  fileNameString += "/";                   // string
  fileNameString += String(day(now()));
  fileNameString += String(month(now()));
  fileNameString += String(year(now()));
  fileNameString += FILE_EXTENSION;
  fileName[fileNameString.length() + 1];
  fileNameString.toCharArray(fileName, fileNameString.length() + 1);

  Serial.print(F("new file name:"));
  Serial.println(fileName);

  // create file on sd card
  file.open(fileName, O_CREAT | O_WRITE | O_EXCL);
  file.println(F("sep=,"));
  file.println(F("Sensor,Uhrzeit,Temperatur,Feuchtigkeit,Kommentar,Akkuspannung"));

  Serial.println("wrote header");
  file.sync();
  return true;
}

bool writeSDValue(int action, float valueTemp, float valueHumid, int comment, float valueAccu)
{
  String hexcomment;
  String valueString;

  switch (comment)
  {
  case '0':
    hexcomment = "0";
  case '1':
    hexcomment = "0x001"; // Marker set
    break;
  }

  switch (action)
  {
  case 0x00:
    valueString = SENSID;
    valueString += ",";
    valueString += year(now());
    valueString += ".";
    valueString += month(now());
    valueString += ".";
    valueString += day(now());
    valueString += " ";
    valueString += hour(now());
    valueString += ":";
    valueString += minute(now());
    valueString += ",";
    valueString += String(valueTemp);
    valueString += ",";
    valueString += String(valueHumid);
    valueString += ",";
    valueString += hexcomment;
    valueString += ",";
    valueString += String(valueAccu);
    break;
  }

  file.open(fileName, O_WRITE);
  file.println(valueString);
  file.sync();
  Serial.println("wrote values to SD");
  return true;
}

void stop()
{
  Serial.println(F("preparing to shutdown"));
  file.sync();
  file.close();
  Serial.println(F("file stored on SD"));
  SysCall::halt();
}

void SerialEvent()
{
  // this is called if something happens on the Serial Console
  // warning: interrupt!!
  
  if (Serial.readString() == "stop")
    {
      stop();
    }

}
=======


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
  Serial.println("i am alive");
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


void analogMeasure(){
  accu = (3.3/1024*analogRead(ADC))*2000;
}

void sample() {
  float tempTemp = 0;
  float tempHumid = 0;
  float tempAccu = 0;
  int iSample = 0;
  millisBeginnSampling = millis();
  int millisSamplingRate = 0;
  while(millis() <= millisBeginnSampling + SAMPLES*1000){
    if(millis() >= millisSamplingRate + SAMPLE_RATE*1000){
      measure();
      analogMeasure();
      tempTemp += temperature;
      tempHumid += humidity;
      tempAccu += accu;
      iSample++;
      Serial.print("Sample nb. ");
      Serial.println(iSample, HEX);
      delay(100);
    }
  }
  tempSample = tempTemp/iSample;
  humidSample = tempHumid/iSample;
  accuSample = tempAccu/iSample;
  Serial.println("Sampled some temp and humid values");
  Serial.print("Temp: ");
  Serial.print(tempSample);
  Serial.println("°C");
  Serial.print("humid: ");
  Serial.print(humidSample);
  Serial.println("%");
  Serial.print(" Accu:");
  Serial.print(accuSample);
  Serial.println("mV");

}


void sleep() {
  ESP.deepSleep((INTERVALL-1)*60);
}