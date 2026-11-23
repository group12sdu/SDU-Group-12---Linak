#ifndef HARDWARE_CONSTANTS_H
#define HARDWARE_CONSTANTS_H

#include <cstdint>
#include "pico/stdlib.h"
#include "hardware/i2c.h"
#include "i2c_bus.h"

namespace hardware_constants{
    
    constexpr uint LED_PIN{7};
    constexpr uint BUTTON_PIN{10};
    constexpr uint BUZZER_PIN{20};
    constexpr uint POT_PIN{26};

    constexpr std::uint32_t SDA_PIN {4};
    constexpr std::uint32_t SCL_PIN {5};
    constexpr std::uint32_t I2C_BAUDRATE {400000};
}

#endif