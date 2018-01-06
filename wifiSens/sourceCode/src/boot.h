/*
boot.h contains booting related functions and boot-function

functions.h is needed here

*/


bool booted = false;     // boot init script was done
bool connection = false; // WiFi Connection established || !== WL_CONNECTED

bool serverConnection = false; // Server Connection established while booting ?

String payload; // recieved Value from json library

bool handshake()
{
  HTTPClient http;
  String url = SERVER_BASE_URL;
  url += "?key=";
  url += API_KEY;
  url += "&action=";
  url += "handshake";

  http.begin(SERVER_HOST, SERVER_PORT, url); //"http://SERVER_HOST:SERVER_PORT/SERVER_BASE_URL?key=API_KEY");

  Serial.print("handshake query: ");
  Serial.println(url);

  int httpCode = http.GET();
  if (httpCode)
  {
    if (httpCode == 200)
    {
      payload = http.getString();
      Serial.println("Recieved HTTP CODE 200");
      Serial.print("Recieved payload: ");
      Serial.println(payload);
      Serial.println("decode payload");
      DynamicJsonBuffer jsonBuffer; // Safe Buffer Space for Arduino JSON

      JsonObject &returned = jsonBuffer.parseObject(payload);
      String success = returned["success"];
      String rTime = returned["time"];
      long longTime = rTime.toInt();
      if (success == "true")
      {
        Serial.println("Server Connection established!");
        serverConnection = true;
      }
      delay(20); // short delay, the serial console won't show correctly
      Serial.print("Servertime: ");
      Serial.println(longTime);
      time_t t = longTime;
      if (longTime >= 1000000000)
      {
        setTime(t);
        adjustTime(GMTplus * 60 * 60);
        Serial.print("adjusted local time to: ");
        Serial.println(now());
        Serial.print("In fact, this is: ");
        Serial.print(hour(now()));
        Serial.print(":");
        Serial.print(minute(now()));
        Serial.print(":");
        Serial.print(second(now()));
        Serial.print(" on ");
        Serial.print(day(now()));
        Serial.print(".");
        Serial.print(month(now()));
        Serial.print(".");
        Serial.print(year(now()));
        Serial.println(" !");

        lastRenew = now();      // fixed bug, so last renew time was set to GMT standard unix time (early: lastRenew = longTime)
        return true;
      }
      else
      {
        return false;
      }
    }
  }
}


/* initialisation of sd card */
void initSD() {
  // init SD-Card
  if (!sd.begin(SD_CS, SD_SCK_MHZ(50)))
  {
      sd.initErrorHalt();
    }
 Serial.println(F("SD card found"));
if(!writeSDheader()) {
  Serial.println(F("error writing header to sd!"));
}
}




void boot()
{

#if(WAITFORSERIAL == true)
    // Wait for USB Serial
  while (!Serial) {
    SysCall::yield();
  }
  while (!Serial.available()) {
    SysCall::yield();
  }
#endif

  if (!booted)
  {
    Serial.println(MSG_BOOT);
    Serial.println(MSG_BOOT_1);
    if(WiFi.status() == WL_CONNECTED) { connection = true;}
    if (!connection)
    { // if wifi_connection is not already established
      // init wiFi
      Serial.print("Connecting to ");
      Serial.print(SSID);

      WiFi.mode(WIFI_STA);        // this means, the esp will run as a client
      WiFi.begin(SSID, WiFiPass); // start wifi connection with SSID and PASS (settings.h)

      while (WiFi.status() != WL_CONNECTED)
      { // wait while esp connects to router
        delay(500);
        Serial.print("."); //print every 1/2 second a "." to symbolize, we didn't hang up
      }
      connection = true;
      Serial.println("Success"); // success if we connected
    }
    else
    { // if wifi is already connected, but boot is called again
      Serial.println("WiFi already connected!");
    }

    // handshake with server
    Serial.println("Handshaking with Server"); // handshaking with server to see if server is available
    if (!handshake())
    {
      Serial.println("handshake failed, no time sent");
      return;
    }
    else
    {
      Serial.println("Handshake run. starting MDNS");
    }

    //init mDNS
    String hostname = "SENS";
    hostname += SENSID;
    const char* dnshostname = hostname.c_str();
    if (MDNS.begin(dnshostname))
    {
      MDNS.addService("http", "tcp", 80);
      Serial.println("MDNS started. try connecting");
      Serial.print("http://SENS");
      Serial.print(SENSID);
      Serial.println(".local");
    }
    else
    {
      Serial.println("mDNS failed");
    }

    initSD();

    Serial.print("My IP Adress:"); // show IP-Adress
    Serial.println(WiFi.localIP());
    Serial.print("booting took us exactly ");
    Serial.print(millis());
    Serial.println("mS!");
    Serial.println("Ready to operate now");
  }
  booted = true;
}
