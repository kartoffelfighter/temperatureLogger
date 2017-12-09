#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>    // http client
#include <ArduinoJson.h>      // JSON Library
#include <TimeLib.h>      // Time library
#include <dht.h>  // DHT Sensor Library


#include "settings.h"  //settings file
#include "lang.h"   // language File
#include "pins.h" // pin config
#include "functions.h"  // functions file

#if(LCD_Enabled == true)
#include <Wire.h>
#include <LiquidCrystal.h>    //liquidCrystal library
#endif


#include "boot.h"     // startup relevant code

int lastMinute = 0;   // counter for status information
int lastUpdateDay = 0;  // counter for updating time
int lastMeasure = 0;  // counter for last measure



void setup() {
    Serial.begin(115200);
    dht.setup(DHT_DATA_PIN);
    // ignore setup, booting script running at loop
}

void loop() {
boot();   // call boot script to test system and establish wifi connection etc. can be recalled, if bool booted is set to false
sample();
if(minute(now()) - lastMeasure >= INTERVALL){
//  measure();
  if(!sendValue(0x00, tempSample, humidSample, 0x00)){
      return;
  }
  lastMeasure = minute(now());
}

if(lastMinute >= 55 && minute(now()) >= 55){
  lastMinute = 0;
}

if(minute(now()) - lastMinute >= 5){
  alive();
  lastMinute = minute(now());
}
if(millis() - 24*60*60*1000 >= lastUpdateDay) {   // reinitialize after 1 Day
  //booted = false;
  lastUpdateDay = millis();
}
}
