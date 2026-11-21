#ifndef BUZZER_H
#define BUZZE_H

#include "hardware/gpio.h"
#include <cstdint>

namespace hardware{
    class Buzzer{
        private:
            static void Tone(std::uint32_t frequency, std::uint32_t length);
        public:
            Buzzer();
            ~Buzzer() = default;
            static void Good();
            static void Bad();
    };
}

#endif