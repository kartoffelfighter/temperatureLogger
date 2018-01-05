void enableOTA(){
    // hard Reset Pins to disconnect Power Supply after OTA_Update
  pinMode(POWER_SOURCE, OUTPUT);
  digitalWrite(POWER_SOURCE, LOW);
  

  ArduinoOTA.onStart([]() {
    String type;
  });
  ArduinoOTA.onProgress([](unsigned int progress, unsigned int total) {
    Serial.printf("Progress: %u%%\r", (progress / (total / 100)));
  });
  ArduinoOTA.onEnd([]() {
    Serial.println("\nEnd");
    Serial.println("hard-reset, shutting down");
    digitalWrite(POWER_SOURCE, HIGH);
  });
  ArduinoOTA.onError([](ota_error_t error) {
    Serial.printf("Error[%u]: ", error);
    if (error == OTA_AUTH_ERROR)
      Serial.println("Auth Failed");
    else if (error == OTA_BEGIN_ERROR)
      Serial.println("Begin Failed");
    else if (error == OTA_CONNECT_ERROR)
      Serial.println("Connect Failed");
    else if (error == OTA_RECEIVE_ERROR)
      Serial.println("Receive Failed");
    else if (error == OTA_END_ERROR)
      Serial.println("End Failed");
  });
 ArduinoOTA.begin();

}
