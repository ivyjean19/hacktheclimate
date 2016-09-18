#include <DHT.h>
#include <SD.h>

#define DHTPIN 2   
#define DHTTYPE DHT22   
int sample = 0;
int counter = 0;
DHT dht(DHTPIN, DHTTYPE);
int pin = 8;
unsigned long duration;
unsigned long starttime;
unsigned long sampletime_ms = 5000;
unsigned long lowpulseoccupancy = 0;
float ratio = 0;
float concentration = 0;
int led = A4;
// sd card 
File myFile;

void setup() {
  Serial.begin(9600);
  //DHT initialization
   Serial.println("Soil Moisture sensor initialization");
   Serial.println("Air quality Sensor Initialization");
  Serial.println("Temperature Sensor Initialization");
  Serial.println("Humidity sensor Initialization");
  dht.begin();
  
//  LED LIGHT
   pinMode(led, OUTPUT);   
  //Dust sensor starts here
  pinMode(8,INPUT);
  //sd card
  pinMode(10, OUTPUT);
  
  if (!SD.begin(27)) {
    Serial.println("Card failed, or not present");
    // don't do anything more:
    return;
  }
   Serial.println("card initialized.");

  
  starttime = millis();//get the current time;
   
//  myFile = SD.open("test"+sample+".txt", FILE_WRITE);
}

void loop() {

  // Wait a few seconds between measurements.
  delay(5000);


  float h = dht.readHumidity();
  // Read temperature as Celsius (the default)
  float t = dht.readTemperature();
  // Read temperature as Fahrenheit (isFahrenheit = true)
  float f = dht.readTemperature(true);
myFile = SD.open("pcube.csv", FILE_WRITE);
if (myFile) {
    Serial.print("Writing to SD : ");
   for (counter = 0; counter < 5; ) {
 	  // close the file:
      

      myFile.print(analogRead(0)); 
      myFile.print(",");         
  //myFile.print("Humidity: ");
      myFile.print(h); 
      myFile.print(",");
      //myFile.print("Temperature: ");
      myFile.print(t);
      myFile.print(",");
      myFile.print( ((-42.379 + 2.04901523*(t*9/5+32) + 10.14333127*h - .22475541*(t*9/5+32)*h - .00683783*(t*9/5+32)*(t*9/5+32) - .05481717*h*h + .00122874*(t*9/5+32)*(t*9/5+32)*h + .00085282*(t*9/5+32)*h*h - .00000199*(t*9/5+32)*(t*9/5+32)*h*h)-32)*5/9);
      myFile.print(",");
      myFile.print();
      /*myFile.print(f);
      myFile.print(" *F;\t");*/
      //myFile.print("PM 2.5 Reading = ");
      myFile.print(concentration);
      myFile.print(",");
      myFile.print("\n");
        myFile.close();
        counter++;
    }
} else {
  // if the file didn't open, print an error:
  Serial.println("error logging");
}

 
  // Check if any reads failed and exit early (to try again).
  if (isnan(h) || isnan(t) || isnan(f)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }
  
  duration = pulseIn(pin, LOW);
  lowpulseoccupancy = lowpulseoccupancy+duration;
 
  if ((millis()-starttime) >= sampletime_ms)//if the sampel time = = 30s
  {
     ratio = lowpulseoccupancy/(sampletime_ms*10.0);  // Integer percentage 0=&gt;100
    concentration = 1.1*pow(ratio,3)-3.8*pow(ratio,2)+520*ratio+0.62; // using spec sheet curve
   
    lowpulseoccupancy = 0;
    starttime = millis();
  }

  // Compute heat index in Fahrenheit (the default)
  float hif = dht.computeHeatIndex(f, h);
  // Compute heat index in Celsius (isFahreheit = false)
  float hic = dht.computeHeatIndex(t, h, false);


  Serial.print("  Moisture Sensor Value:  ");
  Serial.print(analogRead(0));  
  Serial.print(",\t");
  Serial.print("Humidity: ");
  Serial.print(h); 
  Serial.print(",\t");
  Serial.print("Temperature: ");
  Serial.print(t);
  Serial.print(",\t");
  Serial.print("Heat Index:  ");
 Serial.print( ((-42.379 + 2.04901523*(t*9/5+32) + 10.14333127*h - .22475541*(t*9/5+32)*h - .00683783*(t*9/5+32)*(t*9/5+32) - .05481717*h*h + .00122874*(t*9/5+32)*(t*9/5+32)*h + .00085282*(t*9/5+32)*h*h - .00000199*(t*9/5+32)*(t*9/5+32)*h*h)-32)*5/9);
  Serial.print(",\t");
    /*Serial.print(f);
  Serial.print("; *F\t");*/
 Serial.print("PM 2.5 Quantity = ");
 Serial.print(concentration);
 Serial.print(" pcs/0.01cf  ");
 Serial.print("\n");
 //SD print
counter++;
//led light for indicator
  digitalWrite(led, HIGH);   // turn the LED on (HIGH is the voltage level)
  delay(1000);               // wait for a second
  digitalWrite(led, LOW);    // turn the LED off by making the voltage LOW
  delay(1000);
  

}