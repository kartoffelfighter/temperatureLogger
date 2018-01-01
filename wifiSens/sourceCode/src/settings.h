#define LCD_Enabled false

#define SSID "Volks.Funk"
#define WiFiPass "ungueltigeeingabe"

#define GMTplus 1 // time adjust plus the GMT in h

#define API_KEY "FucK4sch001"
#define SERVER_ADDR "http://kruemel:1337/temperatureLogger/html/api.php"

#define SERVER_HOST "kruemel"
#define SERVER_PORT 1337
#define SERVER_BASE_URL "/temperatureLogger/html/api.php"

#define SENSID "0x005"


#define INTERVALL 1   // time in minuts between two measures
#define SAMPLE_RATE  5  // how often should measure be called while sampling
#define SAMPLES 25 // Sampletime in Seconds before a value is valid


#define DHT_TYPE "DHT22"  // define the DHT Sensor Type (Lib supports DHT11 / DHT22 / DHT21)

#define OFFSET 273.15   // Offset for negative Temperatures (0K)
// global integers

long lastRenew = 0; // unix time for last renewed time
long loadingSince = 0;  // millis since accu is loading
float humidity, temperature, accu;
float humidSample, tempSample, accuSample;

int millisBeginnSampling = 0;

DHT dht; // init dht sensor

bool AccuLoading, AccuFull; // booleans for loading accu or accu full

int lastMinute = 0;   // counter for status information
int lastUpdateDay = 0;  // counter for updating time
int lastMeasure = 0;  // counter for last measure
