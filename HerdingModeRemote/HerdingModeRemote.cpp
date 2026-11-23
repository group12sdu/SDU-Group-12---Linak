#include <stdio.h>
#include "pico/stdlib.h"
#include <iostream>

#include "./hardware/buzzer.h"
#include "./hardware/button.h"
#include "./hardware/display.h"
namespace hw = hardware;

int main()
{
    stdio_init_all();
    hw::Buzzer{};
    hw::Button{};
    hw::Display{};
    hw::Display::clear();
    if (hw::Display::show()){std::cout << "clean screen\n";}

    while (true) {
        hw::Display::draw_text(0, 0, "Good!");
        sleep_ms(1000);
        if (hw::Display::show()){std::cout << "good\n";}
        hw::Display::clear();
        hw::Buzzer::Good();
        hw::Display::draw_text(97, 7, ")Bad!");
        sleep_ms(1000);
        if (hw::Display::show()){std::cout << "bad\n";}
        hw::Display::clear();
        hw::Buzzer::Bad();
    }
}
