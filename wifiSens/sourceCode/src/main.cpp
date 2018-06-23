#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h> // http client
#include <ESP8266mDNS.h>       // mDNS package resolver for wifi_Server
#include <ESP8266WebServer.h>  // Webserver for wifi_Server
#include <SPI.h>               // SPI for SD-Card
#include <SdFat.h>                // SD-Card
#include <ArduinoJson.h>       // JSON Library
#include <TimeLib.h>           // Time library
#include <DHT.h>               // DHT Sensor Library
#include <ArduinoOTA.h>        // OTA Update library

#include "settings.h"  //settings file
#include "lang.h"      // language File
#include "pins.h"      // pin config
#include "functions.h" // functions file
#include "boot.h" // startup relevant code
#include "loop.h"      // the loop (cycle())
#include "OTA.h"  // OTA Updates relevant Code

void setup()
{
    Serial.begin(115200);
    pinMode(ADC, INPUT);
    pinMode(accuLoading, INPUT);
    pinMode(accuFull, INPUT);
    dht.setup(DHT_DATA_PIN);
    // ignore setup, booting script running at loop
    enableOTA();
}

void loop()
{
    boot();              // call boot script to test system and establish wifi connection etc. can be recalled, if bool booted is set to false
    cycle();             // the "loop"
    ArduinoOTA.handle(); // OTA Update Handler
}
=======
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

#include "boot.h"     // startup relevant code

int lastMinute = 0;   // counter for status information
int lastUpdateDay = 0;  // counter for updating time
int lastMeasure = 0;  // counter for last measure



void setup() {
    Serial.begin(115200);
    pinMode(ADC, INPUT);
    dht.setup(DHT_DATA_PIN);
    // ignore setup, booting script running at loop
}

void loop() {
boot();   // call boot script to test system and establish wifi connection etc. can be recalled, if bool booted is set to false
sample();
if((millis() - lastMeasure)/60000 >= INTERVALL){     // replaced minute() with millis(), minute will overurun after one hour and the sensor wont work anymore.
  if(!sendValue(0x00, tempSample, humidSample, 0x01, accuSample)){
    Serial.println("query failed");
      return;
  }
  lastMeasure = millis();
}

if((millis() - lastMinute)/60000 >= 5){
  alive();
  lastMinute = millis();
}
if(millis() - 24*60*60*1000 >= lastUpdateDay) {   // reinitialize after 1 Day
  //booted = false;
  lastUpdateDay = millis();
}
}
