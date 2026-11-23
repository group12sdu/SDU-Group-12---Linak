#ifndef SSD1306_H
#define SSD1306_H

#include <array>
#include <cstddef>
#include <cstdint>
#include <string>

namespace hardware
{
    // The 128x64 OLED display. Like Shtc3, it is given the bus. It is not a Sensor.
    // Drawing changes a copy of the screen in memory. show() sends it to the screen.
    class Display
    {
    public:
        static constexpr std::uint8_t ADDRESS {0x3C};
        static constexpr std::uint32_t WIDTH {128};       // pixels
        static constexpr std::uint32_t PAGE_HEIGHT {8};   // a page is 8 rows: one line of text
        static constexpr std::uint32_t PAGES {8};         // 8 pages of 8 rows: 64 rows

        explicit Display();

        static void clear();  // all pixels off
        static void draw_text(std::uint32_t column, std::uint32_t page, const std::string& text);
        static bool show();   // true if the screen got it all

    private:
        static bool send_command(std::uint8_t command);
        static void draw_char(std::uint32_t column, std::uint32_t page, char character);
    };
}

#endif  // SSD1306_H
