#ifndef HARDWARE_CONSTANTS_H
#define HARDWARE_CONSTANTS_H

#include <cstdint>
#include "pico/stdlib.h"

namespace hardware_constants{
    
    //gpio pins
    constexpr uint LED_PIN{7};
    constexpr uint BUTTON_PIN{10};
    constexpr uint BUZZER_PIN{20};
    constexpr uint POT_PIN{26};

    //potentiometer stuff
    constexpr uint POT_ADC_INPUT {0};

    //display stuff
    constexpr std::uint32_t SDA_PIN {4};
    constexpr std::uint32_t SCL_PIN {5};
    constexpr std::uint32_t I2C_BAUDRATE {400000};
}

#endif