bool booted = false;  // boot init script was done
bool connection = false;    // WiFi Connection established

bool serverConnection = false;  // Server Connection established while booting ?

String payload = 0; // recieved Value from json library

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
    }
  }

  Serial.print("booting took us exactly ");
  Serial.print(millis());
  Serial.println("mS!");
  Serial.println("Ready to operate now");
}
booted = true;
}
