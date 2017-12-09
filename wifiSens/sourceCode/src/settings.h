#define LCD_Enabled false

#define SSID ""
#define WiFiPass ""

#define GMTplus 1 // time adjust plus the GMT in h
#define GMTmius 0 // time adjust minus the GMT in h

#define API_KEY "FucK4sch001"
#define SERVER_ADDR "http://kruemel:1337/temperatureLogger/html/api.php"

#define SERVER_HOST "kruemel"
#define SERVER_PORT 1337
#define SERVER_BASE_URL "/temperatureLogger/html/api.php"

#define SENSID "0x001"

#define INTERVALL 15   // time in minuts between two measures
#define SAMPLE_RATE  5  // how often should measure be called while sampling
#define SAMPLES 25 // Sampletime in Seconds before a value is valid

#define DHT_TYPE DHT11  // define the DHT Sensor Type (Lib supports DHT11 / DHT22 / DHT21)

// global integers

long lastRenew = 0; // unix time for last renewed time
float humidity, temperature;
float humidSample, tempSample;

int millisBeginnSampling = 0;

DHT dht; // init dht sensor
