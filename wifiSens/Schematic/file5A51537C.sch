EESchema Schematic File Version 2
LIBS:power
LIBS:device
LIBS:transistors
LIBS:conn
LIBS:linear
LIBS:regul
LIBS:74xx
LIBS:cmos4000
LIBS:adc-dac
LIBS:memory
LIBS:xilinx
LIBS:microcontrollers
LIBS:dsp
LIBS:microchip
LIBS:analog_switches
LIBS:motorola
LIBS:texas
LIBS:intel
LIBS:audio
LIBS:interface
LIBS:digital-audio
LIBS:philips
LIBS:display
LIBS:cypress
LIBS:siliconi
LIBS:opto
LIBS:atmel
LIBS:contrib
LIBS:valves
LIBS:wifiSens
LIBS:switches
LIBS:pl2303
LIBS:schematics-cache
EELAYER 25 0
EELAYER END
$Descr A4 11693 8268
encoding utf-8
Sheet 3 7
Title "temperatureLogger - USB->TTL "
Date "Jan 2018"
Rev "0"
Comp "Marc"
Comment1 ""
Comment2 ""
Comment3 ""
Comment4 ""
$EndDescr
$Comp
L pl2303 U1
U 1 1 5A52D9F4
P 4400 2650
F 0 "U1" H 4400 2750 70  0000 C CNN
F 1 "pl2303" H 4400 2550 70  0000 C CNN
F 2 "Housings_SSOP:SSOP-28_5.3x10.2mm_Pitch0.65mm" H 4400 2650 60  0001 C CNN
F 3 "" H 4400 2650 60  0001 C CNN
	1    4400 2650
	1    0    0    -1  
$EndComp
$Comp
L C C4
U 1 1 5A52DA35
P 1000 2250
F 0 "C4" H 1025 2350 50  0000 L CNN
F 1 "16pF" H 1025 2150 50  0000 L CNN
F 2 "Capacitors_SMD:C_0603_HandSoldering" H 1038 2100 50  0001 C CNN
F 3 "" H 1000 2250 50  0001 C CNN
	1    1000 2250
	1    0    0    -1  
$EndComp
$Comp
L C C5
U 1 1 5A52DA4E
P 1650 2250
F 0 "C5" H 1675 2350 50  0000 L CNN
F 1 "16pF" H 1675 2150 50  0000 L CNN
F 2 "Capacitors_SMD:C_0603_HandSoldering" H 1688 2100 50  0001 C CNN
F 3 "" H 1650 2250 50  0001 C CNN
	1    1650 2250
	1    0    0    -1  
$EndComp
$Comp
L Crystal Y1
U 1 1 5A52DA69
P 1350 1950
F 0 "Y1" H 1350 2100 50  0000 C CNN
F 1 "Crystal" H 1350 1800 50  0000 C CNN
F 2 "Crystals:Crystal_SMD_HC49-SD_HandSoldering" H 1350 1950 50  0001 C CNN
F 3 "" H 1350 1950 50  0001 C CNN
	1    1350 1950
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR06
U 1 1 5A52DB10
P 1350 2650
F 0 "#PWR06" H 1350 2400 50  0001 C CNN
F 1 "GND" H 1350 2500 50  0000 C CNN
F 2 "" H 1350 2650 50  0001 C CNN
F 3 "" H 1350 2650 50  0001 C CNN
	1    1350 2650
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR07
U 1 1 5A52DBD8
P 4350 3950
F 0 "#PWR07" H 4350 3700 50  0001 C CNN
F 1 "GND" H 4350 3800 50  0000 C CNN
F 2 "" H 4350 3950 50  0001 C CNN
F 3 "" H 4350 3950 50  0001 C CNN
	1    4350 3950
	1    0    0    -1  
$EndComp
$Comp
L +3.3V #PWR08
U 1 1 5A52DC9F
P 4600 1300
F 0 "#PWR08" H 4600 1150 50  0001 C CNN
F 1 "+3.3V" H 4600 1440 50  0000 C CNN
F 2 "" H 4600 1300 50  0001 C CNN
F 3 "" H 4600 1300 50  0001 C CNN
	1    4600 1300
	1    0    0    -1  
$EndComp
$Comp
L +5V #PWR09
U 1 1 5A52DCBB
P 4300 1300
F 0 "#PWR09" H 4300 1150 50  0001 C CNN
F 1 "+5V" H 4300 1440 50  0000 C CNN
F 2 "" H 4300 1300 50  0001 C CNN
F 3 "" H 4300 1300 50  0001 C CNN
	1    4300 1300
	1    0    0    -1  
$EndComp
Text GLabel 5950 3250 2    60   Input ~ 0
TXD
Text GLabel 5950 2950 2    60   Input ~ 0
RXD
Text GLabel 2600 2800 0    60   Input ~ 0
USB_D+
Text GLabel 2600 2650 0    60   Input ~ 0
USB_D-
$Comp
L C C6
U 1 1 5A52DE2C
P 5700 1200
F 0 "C6" H 5725 1300 50  0000 L CNN
F 1 "10µF" H 5725 1100 50  0000 L CNN
F 2 "Capacitors_SMD:C_0603_HandSoldering" H 5738 1050 50  0001 C CNN
F 3 "" H 5700 1200 50  0001 C CNN
	1    5700 1200
	1    0    0    -1  
$EndComp
$Comp
L C C7
U 1 1 5A52DE4B
P 6000 1200
F 0 "C7" H 6025 1300 50  0000 L CNN
F 1 "100nF" H 6025 1100 50  0000 L CNN
F 2 "Capacitors_SMD:C_0603_HandSoldering" H 6038 1050 50  0001 C CNN
F 3 "" H 6000 1200 50  0001 C CNN
	1    6000 1200
	1    0    0    -1  
$EndComp
$Comp
L C C8
U 1 1 5A52DE70
P 6500 1200
F 0 "C8" H 6525 1300 50  0000 L CNN
F 1 "10µF" H 6525 1100 50  0000 L CNN
F 2 "Capacitors_SMD:C_0603_HandSoldering" H 6538 1050 50  0001 C CNN
F 3 "" H 6500 1200 50  0001 C CNN
	1    6500 1200
	1    0    0    -1  
$EndComp
$Comp
L C C9
U 1 1 5A52DE93
P 6800 1200
F 0 "C9" H 6825 1300 50  0000 L CNN
F 1 "100nF" H 6825 1100 50  0000 L CNN
F 2 "Capacitors_SMD:C_0603_HandSoldering" H 6838 1050 50  0001 C CNN
F 3 "" H 6800 1200 50  0001 C CNN
	1    6800 1200
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR010
U 1 1 5A52DEB8
P 5850 1450
F 0 "#PWR010" H 5850 1200 50  0001 C CNN
F 1 "GND" H 5850 1300 50  0000 C CNN
F 2 "" H 5850 1450 50  0001 C CNN
F 3 "" H 5850 1450 50  0001 C CNN
	1    5850 1450
	1    0    0    -1  
$EndComp
$Comp
L GND #PWR011
U 1 1 5A52DEDC
P 6650 1450
F 0 "#PWR011" H 6650 1200 50  0001 C CNN
F 1 "GND" H 6650 1300 50  0000 C CNN
F 2 "" H 6650 1450 50  0001 C CNN
F 3 "" H 6650 1450 50  0001 C CNN
	1    6650 1450
	1    0    0    -1  
$EndComp
$Comp
L +5V #PWR012
U 1 1 5A52DF00
P 5850 950
F 0 "#PWR012" H 5850 800 50  0001 C CNN
F 1 "+5V" H 5850 1090 50  0000 C CNN
F 2 "" H 5850 950 50  0001 C CNN
F 3 "" H 5850 950 50  0001 C CNN
	1    5850 950 
	1    0    0    -1  
$EndComp
$Comp
L +3.3V #PWR013
U 1 1 5A52DF24
P 6650 950
F 0 "#PWR013" H 6650 800 50  0001 C CNN
F 1 "+3.3V" H 6650 1090 50  0000 C CNN
F 2 "" H 6650 950 50  0001 C CNN
F 3 "" H 6650 950 50  0001 C CNN
	1    6650 950 
	1    0    0    -1  
$EndComp
Wire Wire Line
	1000 1750 1000 2100
Wire Wire Line
	1000 1950 1200 1950
Wire Wire Line
	1500 1950 1900 1950
Wire Wire Line
	1650 1950 1650 2100
Wire Wire Line
	1000 2400 1000 2550
Wire Wire Line
	1000 2550 1650 2550
Wire Wire Line
	1650 2550 1650 2400
Wire Wire Line
	1350 2650 1350 2550
Connection ~ 1350 2550
Wire Wire Line
	1900 1950 1900 2350
Wire Wire Line
	1900 2350 3400 2350
Connection ~ 1650 1950
Wire Wire Line
	1000 1750 2050 1750
Wire Wire Line
	2050 1750 2050 2250
Wire Wire Line
	2050 2250 3400 2250
Connection ~ 1000 1950
Wire Wire Line
	4200 3650 4200 3800
Wire Wire Line
	4200 3800 4500 3800
Wire Wire Line
	4300 3800 4300 3650
Wire Wire Line
	4400 3800 4400 3650
Connection ~ 4300 3800
Wire Wire Line
	4500 3800 4500 3650
Connection ~ 4400 3800
Wire Wire Line
	4350 3950 4350 3800
Connection ~ 4350 3800
Wire Wire Line
	4300 1650 4300 1300
Wire Wire Line
	4600 1650 4600 1300
Wire Wire Line
	5950 2950 5400 2950
Wire Wire Line
	5400 3250 5950 3250
Wire Wire Line
	2600 2650 2900 2650
Wire Wire Line
	2900 2650 2900 2450
Wire Wire Line
	2900 2450 3400 2450
Wire Wire Line
	2600 2800 3000 2800
Wire Wire Line
	3000 2800 3000 2550
Wire Wire Line
	3000 2550 3400 2550
Wire Wire Line
	6500 1050 6500 1000
Wire Wire Line
	6500 1000 6800 1000
Wire Wire Line
	6800 1000 6800 1050
Wire Wire Line
	6650 1000 6650 950 
Connection ~ 6650 1000
Wire Wire Line
	6500 1350 6500 1400
Wire Wire Line
	6500 1400 6800 1400
Wire Wire Line
	6800 1400 6800 1350
Wire Wire Line
	6650 1450 6650 1400
Connection ~ 6650 1400
Wire Wire Line
	5700 1050 5700 1000
Wire Wire Line
	5700 1000 6000 1000
Wire Wire Line
	6000 1000 6000 1050
Wire Wire Line
	5850 1000 5850 950 
Connection ~ 5850 1000
Wire Wire Line
	5700 1350 5700 1400
Wire Wire Line
	5700 1400 6000 1400
Wire Wire Line
	6000 1400 6000 1350
Wire Wire Line
	5850 1450 5850 1400
Connection ~ 5850 1400
Text GLabel 8600 4000 2    60   Input ~ 0
nRST
Text GLabel 8750 5700 2    60   Input ~ 0
GPIO0
Text GLabel 5950 2850 2    60   Input ~ 0
RI
Text GLabel 5950 2750 2    60   Input ~ 0
DSR
Text GLabel 5950 2650 2    60   Input ~ 0
DCD
Text GLabel 5950 2550 2    60   Input ~ 0
CTS
Text GLabel 5950 3050 2    60   Input ~ 0
RTS
Text GLabel 5950 3150 2    60   Input ~ 0
DTR
Wire Wire Line
	5400 2550 5950 2550
Wire Wire Line
	5400 2650 5950 2650
Wire Wire Line
	5400 2750 5950 2750
Wire Wire Line
	5400 2850 5950 2850
Wire Wire Line
	5400 3050 5950 3050
Wire Wire Line
	5400 3150 5950 3150
$Comp
L Q_NPN_BCE Q1
U 1 1 5A52E6EE
P 8200 4400
F 0 "Q1" H 8400 4450 50  0000 L CNN
F 1 "MMBT8050" H 8400 4350 50  0000 L CNN
F 2 "TO_SOT_Packages_SMD:SOT-23" H 8400 4500 50  0001 C CNN
F 3 "" H 8200 4400 50  0001 C CNN
	1    8200 4400
	1    0    0    -1  
$EndComp
$Comp
L Q_NPN_BCE Q2
U 1 1 5A52E729
P 8200 5250
F 0 "Q2" H 8400 5300 50  0000 L CNN
F 1 "MMBT8050" H 8400 5200 50  0000 L CNN
F 2 "TO_SOT_Packages_SMD:SOT-23" H 8400 5350 50  0001 C CNN
F 3 "" H 8200 5250 50  0001 C CNN
	1    8200 5250
	1    0    0    1   
$EndComp
$Comp
L R R5
U 1 1 5A52EFAB
P 7650 5250
F 0 "R5" V 7730 5250 50  0000 C CNN
F 1 "12" V 7650 5250 50  0000 C CNN
F 2 "Resistors_SMD:R_0603" V 7580 5250 50  0001 C CNN
F 3 "" H 7650 5250 50  0001 C CNN
	1    7650 5250
	0    1    1    0   
$EndComp
$Comp
L R R4
U 1 1 5A52EFF4
P 7650 4400
F 0 "R4" V 7730 4400 50  0000 C CNN
F 1 "12k" V 7650 4400 50  0000 C CNN
F 2 "Resistors_SMD:R_0603" V 7580 4400 50  0001 C CNN
F 3 "" H 7650 4400 50  0001 C CNN
	1    7650 4400
	0    1    1    0   
$EndComp
Wire Wire Line
	7800 4400 8000 4400
Wire Wire Line
	7800 5250 8000 5250
Text GLabel 6900 4400 0    60   Input ~ 0
DTR
Text GLabel 6950 5250 0    60   Input ~ 0
RTS
Wire Wire Line
	6950 5250 7500 5250
Wire Wire Line
	8300 4600 7200 4600
Wire Wire Line
	7200 4600 7200 5250
Connection ~ 7200 5250
Wire Wire Line
	6900 4400 7500 4400
Wire Wire Line
	8300 5050 7100 5050
Wire Wire Line
	7100 5050 7100 4400
Connection ~ 7100 4400
Wire Wire Line
	8300 5450 8300 5700
Wire Wire Line
	8300 5700 8750 5700
Wire Wire Line
	8300 4200 8300 4000
Wire Wire Line
	8300 4000 8600 4000
$EndSCHEMATC
