#include "i2c_bus.h"

#include <cstddef>
#include <cstdint>

#include "hardware/gpio.h"
#include "hardware/i2c.h"

hardware::I2cBus::I2cBus(i2c_inst_t* i2c, std::uint32_t sda_pin, std::uint32_t scl_pin,
                   std::uint32_t baudrate)
    : i2c_ {i2c}
{
    i2c_init(i2c_, baudrate);
    gpio_set_function(sda_pin, GPIO_FUNC_I2C);
    gpio_set_function(scl_pin, GPIO_FUNC_I2C);
    gpio_pull_up(sda_pin);
    gpio_pull_up(scl_pin);
}

bool hardware::I2cBus::write(std::uint8_t address, const std::uint8_t* data, std::size_t length)
{
    const int written {i2c_write_blocking(i2c_, address, data, length, false)};
    return written == static_cast<int>(length);
}

bool hardware::I2cBus::read(std::uint8_t address, std::uint8_t* data, std::size_t length)
{
    const int received {i2c_read_blocking(i2c_, address, data, length, false)};
    return received == static_cast<int>(length);
}

bool hardware::I2cBus::probe(std::uint8_t address)
{
    // a device that is there answers a read of one byte
    std::uint8_t ignored {0};
    return read(address, &ignored, 1);
}