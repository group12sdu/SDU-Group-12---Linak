#include <cstdint>
#include <iostream>
#include "hardware/adc.h"

#include "hardware_constants.h"
namespace hwc = hardware_constants;
#include "potentiometer.h"
namespace hw = hardware;

namespace{
    volatile bool initialized{false};
}

hw::Potentiometer::Potentiometer(){
    if (!initialized){
        adc_init();
        adc_gpio_init(hwc::POT_PIN);
        adc_select_input(hwc::POT_ADC_INPUT);
        initialized = !initialized;
    }
}

std::uint16_t hw::Potentiometer::Read(){
    if (!initialized){
        std::cout << "potentiometer read called not initialized\n";
        return 0;
    }
    return adc_read();
}