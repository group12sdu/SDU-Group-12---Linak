#include "hardware/gpio.h"
#include <iostream>
#include "pico/stdlib.h"

#include "buzzer.h"
namespace hw = hardware;
#include "hardware_constants.h"
namespace hwc = hardware_constants;

namespace{
    volatile bool initialized{false};
}

struct buzzer_info
{
    uint pin;
    volatile bool state{};
};

namespace{
    buzzer_info buzzer{hwc::BUZZER_PIN, false};
}

bool toggle_buzzer(repeating_timer_t* t)
{
    auto* b{static_cast<buzzer_info*>(t->user_data)};
    b->state = !b->state;
    gpio_put(hwc::BUZZER_PIN, b->state);
    return true;
}

hw::Buzzer::Buzzer(){
    if (!initialized){
        gpio_init(hwc::BUZZER_PIN);
        gpio_set_dir(hwc::BUZZER_PIN, GPIO_OUT);
        initialized = !initialized;
    }
}

void hw::Buzzer::Tone(std::uint32_t frequency, std::uint32_t length){
    if (frequency == 0){ // no frequency == silence
        sleep_ms(length + 30); // adding the gap
        return;
    }
    const std::int64_t half_period_us {1000000 / (2 * frequency)};
    repeating_timer_t timer{};
    add_repeating_timer_us(-half_period_us, toggle_buzzer, &buzzer, &timer);
    sleep_ms(length);
    cancel_repeating_timer(&timer);

    gpio_put(hwc::BUZZER_PIN, false);
    sleep_ms(30); // tiny gap so notes are distinct
    return;
}

void hw::Buzzer::Good(){
    if (!initialized){
        std::cout << "buzzer good called not initialized\n";
        return;
    }
    Tone(440, 100);
}
void hw::Buzzer::Bad(){
    if (!initialized){
        std::cout << "buzzer bad called not initialized\n";
        return;
    }
    Tone(262, 100);
    sleep_ms(100);
    Tone(262, 100);
}