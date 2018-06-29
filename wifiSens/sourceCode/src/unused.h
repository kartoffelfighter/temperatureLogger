boot.h backup

bool booted = false;
bool connection = false;

bool serverConnection = false;

void boot() {
if(!booted){
  #if(LCD_Enabled == true)
  LiquidCrystal lcd(LCD_RS, LCD_EN, LCD_D4, LCD_D5, LCD_D6, LCD_D7);
  // set up the LCD's number of columns and rows:
  lcd.begin(16, 2);
  // Print a message to the LCD.
  lcd.print(MSG_BOOT);
  lcd.setCursor(1, 2);
  lcd.print(MSG_BOOT_1);
  #endif

  Serial.begin(9600);
  Serial.println(MSG_BOOT);
  Serial.println(MSG_BOOT_1);

  if(!connection){
    Serial.print("Connecting to ");
    Serial.print(SSID);

    WiFi.mode(WIFI_STA);  // this means, the esp will run as a client
    WiFi.begin(SSID,WiFiPass);

    while(WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
    }
    Serial.println("Success");
    Serial.print("My IP Adress:");
    Serial.println(WiFi.localIP());
    connection = true;
    }
  else {
    Serial.println("WiFi already connected!");
    Serial.println("my ip:  ");
    Serial.println(WiFi.localIP());
  }
  Serial.println("Handshaking with Server");

  WiFiClient client;
  if(!client.connect(SERVER_HOST, SERVER_PORT)) {
    Serial.println("Connection failed");
    return ;
  }

  String url = SERVER_BASE_URL;
  url += "?key=";
  url += API_KEY;
  url += "&action=";
  url += "handshake";

  Serial.print("handshake query: ");
  Serial.println(url);

  client.print(String("GET ") + url + " HTTP/1.1\r\n" + "Host: " + SERVER_HOST + "\r\n" + "Connection: close \r\n\r\n");  // needed " " before HTTP
  unsigned long timeout = millis();
  if(trys <= 5){
    while (client.available() == 0){
      if(millis() - timeout > 7000) {
        Serial.println(">>>> Client timed out");
        client.stop();
        trys++;
        return ;
      }
    }
  }
  else {
    Serial.println(">>> Client timed 5 times out. No connection to client possible!");
    Serial.println("Waiting 25s...");
    int millisbegin = millis();
    int millisbegin2 = 0;
    while(millisbegin2 + millis() <= millisbegin + 25000){
      Serial.println("wating... (already ");        // waiting cycle
      Serial.print((millis() - millisbegin)/1000);
      Serial.println(" Seconds )");
      delay(1000);
    }
    trys = 0;
    return ;
  }

  while(client.available()){
    String line  = client.readStringUntil('\r');
    Serial.println("  --- Handshake raw return: ---");
    Serial.println(line);
    Serial.println("  --- END raw return --- ");

  }

  Serial.print("booting took us exactly ");
  Serial.print(millis());
  Serial.println("mS!");
  Serial.println("Ready to operate now");
}
booted = true;
}
