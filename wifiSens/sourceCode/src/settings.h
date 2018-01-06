// ===== superglobal settings ===== //
#define STORE_ON_FLASH false        // enable settings to be stored in eeprom
#define SETUP false         // enable setup at first boot

// ===== DEBUG ===== (not possible in eeprom_settings mode)
#define WAITFORSERIAL false // debug information, waites to boot until serial monitor is connected
#define USE_SD_CRC 0  // debug information for CRC Check

// ===== WIFI ======
#define SSID "Volks.Funk"
#define WiFiPass "ungueltigeeingabe"

// ===== Server Connection in push mode =====
#define SENSID "0x005"      // Note: The SENSID is also the HOST-Name! (Host-Name: SENS[SENSID].local)
#define GMTplus 1 // time adjust plus the GMT in h
#define API_KEY "FucK4sch001"
#define SERVER_ADDR "http://kruemel:1337/temperatureLogger/html/api.php"
#define SERVER_HOST "kruemel"
#define SERVER_PORT 1337
#define SERVER_BASE_URL "/temperatureLogger/html/api.php"

// ===== Measurement related
#define INTERVALL 1   // time in minuts between two measures
#define SAMPLE_RATE  5  // how often should measure be called while sampling
#define SAMPLES 25 // Sampletime in Seconds before a value is valid
#define DHT_TYPE "DHT22"  // define the DHT Sensor Type (Lib supports DHT11 / DHT22 / DHT21)
#define OFFSET 273.15   // Offset for negative Temperatures (0K)

// ===== SD related

#define error(msg) sd.errorHalt(F(msg)) // Error messages stored in flash.
#define FILE_EXTENSION ".csv"   // file extension on logged data on sd card
#define MEASUREMENT_DIR "values"    // directory where files will be placed

// initialisation of globals 
// measured vallue store
long lastRenew = 0; // unix time for last renewed time
long loadingSince = 0;  // millis since accu is loading
float humidity, temperature, accu;
float humidSample, tempSample, accuSample;

// determination of accu is loading
bool AccuLoading, AccuFull; // booleans for loading accu or accu full

// counters
int lastMinute = 0;   // counter for status information
int lastUpdateDay = 0;  // counter for updating time
int lastMeasure = 0;  // counter for last measure
int millisBeginnSampling = 0;


// sd related
//int fileNameSize = sizeof(MEASUREMENT_DIR) + sizeof(FILE_EXTENSION) + 8;
char fileName[25];


// ====== Config dht Sensor for Temperature and humidity measurement
DHT dht; // init dht sensor

// ====== Config sd card and fatLib
SdFat sd;   // SD config
SdFile file; // File config



///////////////////////////////////////////////////////////////////////////////////////////////////////////
// Settings file on SD Card
//
/*  settings.conf layout:
// add credentials after = in brackets

// wifi related
[SSID=SSID]         // wifi SSID
[WiFiPass=PASS]     // the wifi password
[SENSID=0x001]      // has to be HEX value with starting 0x followed by 3 characters (000 - FFF is possible)
// client/server/sdlogmode
[mode=4]            // modes table: 0 = do not operate anything; 1 = client only; 2 = server only; 3 = sd card only; 4 = client + sd; 5 = server + sd; 6 = client + server + sd     (in all modes you need this sd card...)

// client config (ignore if client mode is not used)
[GMTplus=1]         // set time offset in hours (only positive values supported)
[API_KEY=KEY]       // API Key to connect do database server api
[SERVER_ADDR=http://server:port/path/to/api.php]    // full path to api file on webserver
[SERVER_HOST=server]   // hostname of webserver
[SERVER_PORT=1337]     // port of webserver
[SERVER_BASE_URL=/path/to/api.php]  // blank path to api

// measurement related settings
[INTERVAL=1]        // time in minutes between two measures
[SAMPLE_RATE=5]     // while sampling, sample rate defines how often a pure measure of values is called
[SAMPLES=25]        // time in seconds how long a samples duration is, until a value is valid
[DHT_TYPE=DHT22]    // Type of sensor, possible: DHT11, DHT21, DHT22 ; default: DHT22
[OFFSET=273.15]     // OFFSET, added to temperature to provide negative values (needs to be identical to API)


// SD card related settings
[MEASUREMENT_DIR=values]    // directory on SD-card, where measurefiles should be stored
[FILE_EXTENSION=.csv]       // file extension of stored values


// END OF FILE

*/
// EEPROM SETTINGS
/*
#define SSID "Volks.Funk"
#define WiFiPass "ungueltigeeingabe"

// ===== Server Connection in push mode =====
#define SENSID "0x005"      // Note: The SENSID is also the HOST-Name! (Host-Name: SENS[SENSID].local)
#define GMTplus 1 // time adjust plus the GMT in h
#define API_KEY "FucK4sch001"
#define SERVER_ADDR "http://kruemel:1337/temperatureLogger/html/api.php"
#define SERVER_HOST "kruemel"
#define SERVER_PORT 1337
#define SERVER_BASE_URL "/temperatureLogger/html/api.php"

// ===== Measurement related
#define INTERVALL 1   // time in minuts between two measures
#define SAMPLE_RATE  5  // how often should measure be called while sampling
#define SAMPLES 25 // Sampletime in Seconds before a value is valid
#define DHT_TYPE "DHT22"  // define the DHT Sensor Type (Lib supports DHT11 / DHT22 / DHT21)
#define OFFSET 273.15   // Offset for negative Temperatures (0K)

// ===== SD related

#define error(msg) sd.errorHalt(F(msg)) // Error messages stored in flash.
#define FILE_EXTENSION ".csv"   // file extension on logged data on sd card
#define MEASUREMENT_DIR "values"    // directory where files will be placed
*/