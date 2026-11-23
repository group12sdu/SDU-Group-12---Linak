#ifndef I2C_BUS_H
#define I2C_BUS_H

#include <cstddef>
#include <cstdint>

#include "hardware/i2c.h"

namespace hardware
{
    // One I2C bus: a controller and its SDA and SCL pins. Many devices can share it.
    class I2cBus
    {
    public:
        I2cBus(i2c_inst_t* i2c, std::uint32_t sda_pin, std::uint32_t scl_pin,
               std::uint32_t baudrate);

        // Both return true if all length bytes went through.
        bool write(std::uint8_t address, const std::uint8_t* data, std::size_t length);
        bool read(std::uint8_t address, std::uint8_t* data, std::size_t length);

        // true if a device answers at address
        bool probe(std::uint8_t address);

    private:
        i2c_inst_t* i2c_;  // i2c0 or i2c1
    };
}

#endif  // I2C_BUS_H
