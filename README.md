temperatureLogger Firmware alphaV2
-

**Table of contents**

 - Firmware description
 - Function description
 - New Functions
 - Comparison
 - Possibility for new milestone


**Firmware description**

temperatureLogger alphaV2 ist planned to be used for a temperature logging device based on esp8266.
The device sends its measured values using a json string to a php server api to be stored and viewed from users. temperatureLogger alphaV2 uses the new and changing pcb-layout, alphaV2 is work in progress and may be not stable to use.

alphaV2 will be recoded to a tick based firmware powered by interrupts by timer0. 
at this time alphaV2 runs millis() loop based, checking the lasted time running code, for the new sleep() function a tick based firmware is needed.

**Function description**

based on alphaV1 the firmware can already measure, sample and send values to the API (Server). 
In future, the functions should be better described here.

**New functions**
in alphaV2 especially the error handling an the improvin of new hardware-designed milestone should be implemented.

For future releases the should be a function to configure editable values from "settings.h" by API and interface.
A ping-pong communication should be implemented


**Comparison (to alphaV1)**

alphaV1 is made for the old pcb layout, some pins have changed.
alphaV2 has no more LCD support, also LCD support is not planned for the future.
alphaV2 implements new features, as a multiplexed ADC input for measuring multiple Analog Values with just one ADC.
*primary change* alphaV2 will be a tick (interrupt) based firmware for compatibility reasons to new boards in the future.


**next milestone for alphaV3**
the next milestone is planned for the first, etched pcb in january '18.
