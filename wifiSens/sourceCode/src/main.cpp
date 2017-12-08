#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>    // http client
#include <ArduinoJson.h>      // JSON Library

#include "settings.h"  //settings file
#include "lang.h"   // language File
#include "pins.h" // pin config
#include "functions.h"  // functions file

#if(LCD_Enabled == true)
#include <Wire.h>
#include <LiquidCrystal.h>    //liquidCrystal library
#endif


#include "boot.h"     // startup relevant code



void setup() {
    // ignore setup, booting script running at loop
}

void loop() {
boot();   // call boot script to test system and establish wifi connection etc. can be recalled, if bool booted is set to false
Serial.println("Connection to API: ");
Serial.println(serverConnection);
Serial.println("loop");
delay(500);
if(millis() % 10000 == 0){
  Serial.println("Infostring following");
  Serial.print("IP-Adress: ");
  Serial.println(WiFi.localIP());
  Serial.print("Runtime: ");
  Serial.print(millis()/1000/60);
  Serial.println(" min");
  Serial.println("API Connection last renewed: ");
  Serial.println(serverConnection);
  Serial.println(".............................");
}

}
