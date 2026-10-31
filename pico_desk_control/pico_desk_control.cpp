//Main
#include <stdio.h>
#include "pico/stdlib.h"

#include "./management/preset.h"
#include "./management/special_mode.h"
#include "./management/code_generator.h"
#include "./management/desk.h"

constexpr std::uint32_t DEBOUNCE_US {80000}; 
constexpr std::uint32_t DOUBLEPRESS_US {250000}; 

namespace
{
    volatile bool pending_press{false};
    volatile bool doublepress{false};
    volatile std::uint32_t last_press_us{0};
}

management::Desk init_desk();
void button_isr(uint gpio, std::uint32_t events);
void init_button();

int main()
{
    stdio_init_all();

    init_button();
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

void button_isr(uint gpio, std::uint32_t events){
    if (gpio != 10 || (events & GPIO_IRQ_EDGE_RISE) == 0u){ // check if the pin and edge type is correct
        return;
    }

    const std::uint32_t now_us {time_us_32()}; // get time since boot
    
    if (now_us - last_press_us < DEBOUNCE_US){ // prevent single press from counting twice
        return;
    }
    pending_press = true;
    
    if (now_us - last_press_us < DOUBLEPRESS_US){ // see if there was a double press
        doublepress = true;
    }

    last_press_us = now_us; // make this the last press
    return;
}

void init_button(){ // 10 is a button pin on the pico board provided
    gpio_init(10);
    gpio_set_dir(10, GPIO_IN);
    gpio_pull_down(10);
    gpio_set_irq_enabled_with_callback(10, GPIO_IRQ_EDGE_RISE, true, button_isr);
}
