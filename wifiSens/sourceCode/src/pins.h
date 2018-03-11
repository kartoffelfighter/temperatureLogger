// define default pin config

// NodeMCU 1.0


#define ADC A0
#define DHT_DATA_PIN 5 //NodeMCU: D1

#define WakeUp 16 // NodeMCU: D0
#define BUTTON 0 // NodeMCU: D3

#define accuLoading CHRG 
#define accuFull STDBY 

#define POWER_SOURCE 14 // NodeMCU: D5
#define SD_CS 15    // NodeMCU D2

#define SD_DETECT 11      // pin on SD Card, gets HIGH if SD Card is inserted

#define LOAD_EN 15        // pin on BatteryLoader, set to high to start loading
#define CHRG 4           // pin on BetteryLoader, becomes low while loading
#define STDBY 2          // pin on BatteryLoader, becomes low if battery full

#define ONOFF 7          // signal to turn on LDO chip

/*
 Different pin configs:                                      _________________
 || used as | Arduino IDE (identical to esp8266)|| NodeMCU ||O--------------O|| NodeMCU | Arduino IDE (identical to esp8266)|| used as ||
 || ADC     | A0                                || A0      ||||+          +|||| D0      | 16                                || WakueUp ||
 ||         | GND                               || GND     ||||+          +|||| D1      | 5                                 || DHT     ||
 ||         | Reserved                          || VU      ||||+          +|||| D2      | 4                                 || Loading ||
 ||         | 10                                || SD3     ||||+     N    +|||| D3      | 0                                 || Button  ||
 ||         | 9                                 || SD2     ||||+     o    +|||| D4      | 2                                 || Full    ||
 ||         | MOSI                              || SD1     ||||+     d    +|||| 3V3     | 3V3                               || DHT     ||
 ||         | CS                                || CMD     ||||+     e    +|||| GND     | GND                               || DHT     ||
 ||         | MISO                              || SDO     ||||+     M    +|||| D5      | 14                                ||         ||
 ||         | SCLK                              || CLK     ||||+     C    +|||| D6      | 12                                ||         ||
 ||         | GND                               || GND     ||||+     U    +|||| D7      | 13                                ||         ||
 ||         | 3V3                               || 3V3     ||||+          +|||| D8      | 15                                ||         ||
 ||         | EN                                || EN      ||||+          +|||| RX      | 3                                 ||         ||
 ||         | RESET                             || RST     ||||+          +|||| TX      | 1                                 ||         ||
 ||         | GND                               || GND     ||||+          +|||| GND     | GND                               ||         ||
 ||         | Vin                               || Vin     ||||+          +|||| 3V3     | 3V3                               ||         ||
 || used as | Arduino IDE (identical to esp8266)|| NodeMCU ||O--------------O|| NodeMCU | Arduino IDE (identical to esp8266)|| used as ||
*/
