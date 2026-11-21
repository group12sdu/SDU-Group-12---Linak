#include "hardware/gpio.h"

#include "buzzer.h"
#include "hardware_constants.h"

namespace hwc = hardware_constants;


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

hardware::Buzzer::Buzzer(){
    gpio_init(hwc::BUZZER_PIN);
    gpio_set_dir(hwc::BUZZER_PIN, GPIO_OUT);
}

void hardware::Buzzer::Tone(std::uint32_t frequency, std::uint32_t length){
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

void hardware::Buzzer::Good(){
    Tone(440, 100);
}
void hardware::Buzzer::Bad(){
    Tone(262, 100);
    sleep_ms(100);
    Tone(262, 100);
}