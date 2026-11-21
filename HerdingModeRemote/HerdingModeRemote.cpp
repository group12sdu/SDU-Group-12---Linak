#include <stdio.h>
#include "pico/stdlib.h"
#include <iostream>

#include "./hardware/buzzer.h"
#include "./hardware/button.h"

int main()
{
    stdio_init_all();
    hardware::Buzzer();
    hardware::Button();
    while (true) {
        sleep_ms(1000);
        std::cout << "good\n"; 
        hardware::Buzzer::Good();
        sleep_ms(1000);
        std::cout << "bad\n";
        hardware::Buzzer::Bad();
    }
}
