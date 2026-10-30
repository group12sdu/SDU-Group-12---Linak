//Main
#include <stdio.h>
#include "pico/stdlib.h"

#include "./management/preset.h"
#include "./management/special_mode.h"
#include "./management/code_generator.h"
#include "./management/desk.h"

management::Desk init_desk();

int main()
{
    stdio_init_all();

    management::Desk desk = init_desk();

    while(true){

    }
}

management::Desk init_desk(){ // all numbers are dummy numbers right now
    using namespace management;
    Preset default_preset(130, 100);
    Special_mode default_cleaning(150, "cleaning");
    Special_mode default_presentation(110, "presentation");
    return Desk(default_preset, default_cleaning, default_presentation);
}