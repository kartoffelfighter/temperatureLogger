void cycle()
{ // default loop()
  if (booted) // if not booted, do not operate
  {
    if ((millis() - lastMeasure) / 60000 >= INTERVALL)
    { // replaced minute() with millis(), minute will overurun after one hour and the sensor wont work anymore.
      sample();

      if (!sendValue(0x00, tempSample, humidSample, 0x01, accuSample))
      {
        Serial.println("query failed");
        return;
      }
      if (!writeSDValue(0x00, tempSample, humidSample, 0x01, accuSample))
      {
        Serial.println("write to SD failed.");
      }
      file.sync();
      lastMeasure = millis();
    }

    if ((millis() - lastMinute) / 60000 >= 5)
    {
      alive();
      if (AccuLoading)
      {
        if (loadingSince = 0)
        {
          // go ahe
        }
      }
      lastMinute = millis();
    }
    if (millis() - lastUpdateDay >= 24 * 60 * 60 * 1000)
    { // reinitialize after 1 Day
      //booted = false;
      file.close();
      if(writeSDheader()) {
        Serial.println(F("Created new file for today."));
        Serial.println(F("SD header wrote successfully."));
      }
      lastUpdateDay = millis();
    }
  }
}
