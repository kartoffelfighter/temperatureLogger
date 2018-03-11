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
