bool booted = false;  // boot init script was done
bool connection = false;    // WiFi Connection established

bool serverConnection = false;  // Server Connection established while booting ?

String payload; // recieved Value from json library

void boot() {
if(!booted){
  #if(LCD_Enabled == true)    //LCD Support temporary discontinued, as my lcd broke
  LiquidCrystal lcd(LCD_RS, LCD_EN, LCD_D4, LCD_D5, LCD_D6, LCD_D7);    // Pin Config in pins.h
  // set up the LCD's number of columns and rows:
  lcd.begin(16, 2);
  // Print a message to the LCD.
  lcd.print(MSG_BOOT);
  lcd.setCursor(1, 2);
  lcd.print(MSG_BOOT_1);
  #endif



  if(!connection){  // if wifi_connection is not already established
    Serial.println(MSG_BOOT);
    Serial.println(MSG_BOOT_1);
    Serial.print("Connecting to ");
    Serial.print(SSID);

    WiFi.mode(WIFI_STA);  // this means, the esp will run as a client
    WiFi.begin(SSID,WiFiPass);  // start wifi connection with SSID and PASS (settings.h)

    while(WiFi.status() != WL_CONNECTED) {    // wait while esp connects to router
      delay(500);
      Serial.print("."); //print every 1/2 second a "." to symbolize, we didn't hang up
    }
    Serial.println("Success");  // success if we connected
    Serial.print("My IP Adress:");  // show IP-Adress
    Serial.println(WiFi.localIP());
    connection = true; // set connection to true
    }
  else {  // if wifi is already connected, but boot is called again
    Serial.println("WiFi already connected!");
    Serial.println("my ip:  ");
    Serial.println(WiFi.localIP());
  }
  Serial.println("Handshaking with Server"); // handshaking with server to see if server is available

  /*@@@@@@@@@@@@@@@@@@@@@
  @ Handshake will also give us the actual unix time as a json string
  @ i.e.: {"success":"true", "time":"29480324"}
  */


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
  if(httpCode) {
    if(httpCode == 200){
      payload = http.getString();
      Serial.println("Recieved HTTP CODE 200");
      Serial.print("Recieved payload: ");
      Serial.println(payload);
      Serial.println("decode payload");
      DynamicJsonBuffer jsonBuffer;   // Safe Buffer Space for Arduino JSON

      JsonObject& returned = jsonBuffer.parseObject(payload);
      String success = returned["success"];
      String rTime = returned["time"];
      long longTime = rTime.toInt();
      if(success == "true"){
        Serial.println("Server Connection established!");
        serverConnection = true;
      }
      delay(20);  // short delay, the serial console won't show correctly
      Serial.print("Servertime: ");
      Serial.println(longTime);
      time_t t = longTime;
      setTime(t);
      adjustTime(GMTplus*60*60);
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

      lastRenew = longTime;

    }
  }

  Serial.print("booting took us exactly ");
  Serial.print(millis());
  Serial.println("mS!");
  Serial.println("Ready to operate now");

}
booted = true;
}
