#include "hardware/gpio.h"
#include <iostream>

#include "button.h"
namespace hw = hardware;
#include "hardware_constants.h"
namespace hwc = hardware_constants;

namespace{
    volatile bool initialized{false};
}

void button_isr(uint gpio, std::uint32_t events){
    if (gpio != hwc::BUTTON_PIN || (events & GPIO_IRQ_EDGE_RISE) == 0u)
    {
        return;
    }
    // do stuff
    std::cout << "button pressed";
    return;
}   

hw::Button::Button(){
    if (!initialized){
        gpio_init(hwc::BUTTON_PIN);
        gpio_set_dir(hwc::BUTTON_PIN, GPIO_IN);
        gpio_pull_down(hwc::BUTTON_PIN);
        gpio_set_irq_enabled_with_callback(hwc::BUTTON_PIN, GPIO_IRQ_EDGE_RISE, true, button_isr);
        initialized = !initialized;
    }
}