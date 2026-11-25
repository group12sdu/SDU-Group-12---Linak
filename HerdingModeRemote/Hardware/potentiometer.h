#ifndef POTENTIOMETER_H
#define POTENTIOMETER_H

#include <cstdint>

namespace hardware{
    class Potentiometer{
        public:
            Potentiometer();
            ~Potentiometer() = default;
            static std::uint16_t Read();
    };
}
#endif