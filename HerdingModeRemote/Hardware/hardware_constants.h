#ifndef HARDWARE_CONSTANTS_H
#define HARDWARE_CONSTANTS_H

#include <cstdint>
#include "pico/stdlib.h"

namespace hardware_constants{
    
    constexpr uint LED_PIN{7};
    constexpr uint BUTTON_PIN{10};
    constexpr uint BUZZER_PIN{20};
    constexpr uint POT_PIN{26};

}

#endif