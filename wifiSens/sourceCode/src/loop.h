void cycle(){ // default loop()
  sample();
  if((millis() - lastMeasure)/60000 >= INTERVALL){     // replaced minute() with millis(), minute will overurun after one hour and the sensor wont work anymore.
    if(!sendValue(0x00, tempSample, humidSample, 0x01, accuSample)){
      Serial.println("query failed");
        return;
    }
    lastMeasure = millis();
  }

  if((millis() - lastMinute)/60000 >= 5){
    alive();
    if(AccuLoading) {
        if(loadingSince = 0){
          // go ahe
        }
    }
    lastMinute = millis();
  }
  if(millis() - 24*60*60*1000 >= lastUpdateDay) {   // reinitialize after 1 Day
    //booted = false;
    lastUpdateDay = millis();
  }

}
