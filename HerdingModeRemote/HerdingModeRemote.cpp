#include <stdio.h>
#include "pico/stdlib.h"
#include <iostream>

#include "./Software/Menu.h"
namespace sw = software;

int main()
{
    stdio_init_all();
    sw::Menu menu{};
    std::string options[8]{"Option 1", "Option 2", "Option 3", "Option 4", "Option 5", "Option 6", "Option 7", "Option 8"};
    menu.SetOptions(options);
    while (true) {
        menu.ShowOptions();
        sleep_ms(1000);
    }
}
