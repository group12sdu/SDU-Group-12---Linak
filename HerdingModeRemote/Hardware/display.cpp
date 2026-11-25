#include <algorithm>
#include <array>
#include <cstddef>
#include <cstdint>
#include <string>
#include <iostream>

#include "display.h"
#include "font.h"
#include "i2c_bus.h"
namespace hw = hardware;
#include "hardware_constants.h"
namespace hwc = hardware_constants;

namespace
{
    // the first byte of each write: what the rest is
    constexpr std::uint8_t COMMAND_FOLLOWS {0x00};
    constexpr std::uint8_t PIXELS_FOLLOW {0x40};

    // commands and their values, from the SSD1306 datasheet
    constexpr std::uint8_t DISPLAY_OFF {0xAE};
    constexpr std::uint8_t DISPLAY_ON {0xAF};
    constexpr std::uint8_t SET_CLOCK {0xD5};
    constexpr std::uint8_t CLOCK_DEFAULT {0x80};
    constexpr std::uint8_t SET_MULTIPLEX {0xA8};
    constexpr std::uint8_t ROWS_64 {0x3F};  // 64 rows, counted from 0
    constexpr std::uint8_t SET_OFFSET {0xD3};
    constexpr std::uint8_t NO_OFFSET {0x00};
    constexpr std::uint8_t START_LINE_0 {0x40};
    constexpr std::uint8_t CHARGE_PUMP {0x8D};
    constexpr std::uint8_t CHARGE_PUMP_ON {0x14};
    constexpr std::uint8_t MEMORY_MODE {0x20};
    constexpr std::uint8_t HORIZONTAL {0x00};  // left to right, then the next page
    constexpr std::uint8_t MIRROR_COLUMNS {0xA1};
    constexpr std::uint8_t MIRROR_ROWS {0xC8};
    constexpr std::uint8_t COM_PINS {0xDA};
    constexpr std::uint8_t COM_PINS_128X64 {0x12};
    constexpr std::uint8_t CONTRAST {0x81};
    constexpr std::uint8_t CONTRAST_HIGH {0xCF};
    constexpr std::uint8_t PRECHARGE {0xD9};
    constexpr std::uint8_t PRECHARGE_DEFAULT {0xF1};
    constexpr std::uint8_t VCOM_DETECT {0xDB};
    constexpr std::uint8_t VCOM_DEFAULT {0x40};
    constexpr std::uint8_t SHOW_MEMORY {0xA4};
    constexpr std::uint8_t NOT_INVERTED {0xA6};
    constexpr std::uint8_t COLUMN_RANGE {0x21};
    constexpr std::uint8_t PAGE_RANGE {0x22};

    constexpr std::array INIT_COMMANDS {
        DISPLAY_OFF,
        SET_CLOCK, CLOCK_DEFAULT,
        SET_MULTIPLEX, ROWS_64,
        SET_OFFSET, NO_OFFSET,
        START_LINE_0,
        CHARGE_PUMP, CHARGE_PUMP_ON,
        MEMORY_MODE, HORIZONTAL,
        MIRROR_COLUMNS,
        MIRROR_ROWS,
        COM_PINS, COM_PINS_128X64,
        CONTRAST, CONTRAST_HIGH,
        PRECHARGE, PRECHARGE_DEFAULT,
        VCOM_DETECT, VCOM_DEFAULT,
        SHOW_MEMORY,
        NOT_INVERTED,
        DISPLAY_ON,
    };

    constexpr std::uint32_t CHAR_STEP {font::CHAR_WIDTH + 1};  // one empty column between
    constexpr std::size_t COMMAND_BYTES {2};
    
    constexpr std::size_t PIXEL_BYTES {hardware::Display::WIDTH * hardware::Display::PAGES};
    std::array<std::uint8_t, 1 + PIXEL_BYTES> buffer_{};
    
    hw::I2cBus bus_{i2c0, hwc::SDA_PIN, hwc::SCL_PIN, hwc::I2C_BAUDRATE};

    volatile bool initialized{false};
}

hw::Display::Display()
{
    if (!initialized){
        buffer_[0] = PIXELS_FOLLOW;
        for (const std::uint8_t command : INIT_COMMANDS)
        {
            send_command(command);
        }
        initialized = !initialized;
    }
}

bool hw::Display::send_command(std::uint8_t command)
{
    const std::array<std::uint8_t, COMMAND_BYTES> bytes {COMMAND_FOLLOWS, command};
    return bus_.write(ADDRESS, bytes.data(), bytes.size());
}

void hw::Display::clear()
{
    if (!initialized){
        std::cout << "display clear called not initialized\n";
        return;
    }
    // keep byte 0, PIXELS_FOLLOW
    std::fill(buffer_.begin() + 1, buffer_.end(), 0);
}

void hw::Display::draw_char(std::uint32_t column, std::uint32_t page, char character)
{
    if (character < font::FIRST_CHAR || character > font::LAST_CHAR)
    {
        character = '?';
    }
    const font::Glyph& glyph {font::GLYPHS[character - font::FIRST_CHAR]};

    for (std::size_t i {0}; i < font::CHAR_WIDTH && column + i < WIDTH; ++i)
    {
        buffer_[1 + page * WIDTH + column + i] = glyph[i];
    }
}

void hw::Display::draw_text(std::uint32_t column, std::uint32_t page, const std::string& text)
{
    if (!initialized){
        std::cout << "display draw_text called not initialized\n";
        return;
    }
    if (page >= PAGES)
    {
        return;
    }
    for (const char character : text)
    {
        draw_char(column, page, character);
        column += CHAR_STEP;
    }
}

void hw::Display::highlight_section(std::uint32_t column_from, std::uint32_t column_to, std::uint32_t page){
    std::uint32_t start = page*128 + column_from;
    std::uint32_t end = page*128 + column_to;
    for (std::size_t i{1+start}; i < end; ++i){
        buffer_[i] = ~buffer_[i];
    }
}

bool hw::Display::show()
{
    if (!initialized){
        std::cout << "display show called not initialized\n";
        return false;
    }
    // the whole screen: columns 0 .. WIDTH - 1, pages 0 .. PAGES - 1
    const bool range_set {send_command(COLUMN_RANGE) && send_command(0) &&
                          send_command(WIDTH - 1) && send_command(PAGE_RANGE) &&
                          send_command(0) && send_command(PAGES - 1)};
    if (!range_set)
    {
        return false;
    }
    // TODO 1: write all of buffer_ to ADDRESS. Return true if it all went through.
    return bus_.write(ADDRESS, buffer_.data(), buffer_.size()); 
}
